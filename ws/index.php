<?php
require '../vendor/autoload.php';

use Phalcon\Di;
use Phalcon\Loader;
use Phalcon\Mvc\Application;
use Phalcon\Mvc\View;
use Phalcon\Mvc\Router;
use Phalcon\Http\Request;
use Phalcon\Http\Response;
use Phalcon\Mvc\Dispatcher as MvcDispatcher;
use Phalcon\Db\Adapter\Pdo\Mysql as Database;
use Phalcon\Mvc\Model\Manager as ModelManager;
use Phalcon\Mvc\Model\Metadata\Memory as ModelMetadata;
use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;
use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;

class WsApp implements MessageComponentInterface {
    protected $arClients;

    public function __construct() {
        $this->arClients = [];

        $loader = new Loader();
        $loader->registerDirs(["../api/app/models/"]);
        $loader->register();

        $di = new Di();
        $di->set(
            "db",
            function () {
                return new Database(
                    [
                        "host"     => "mysql",
                        "username" => "root",
                        "password" => "root",
                        "dbname"   => "app_db",
                    ]
                );
            }
        );
        $di->set("modelsMetadata", ModelMetadata::class);
        $di->set("modelsManager", ModelManager::class);
    }

    public function onOpen(ConnectionInterface $conn) {}

    public function onMessage(ConnectionInterface $obConnection, $sMessage) {
        $arMessage = json_decode($sMessage, true);
        switch ($arMessage['action']){
            case 'register':
                $this->arClients[$obConnection->resourceId] = [
                    'user_id' => $arMessage['user_id'],
                    'connection' => $obConnection
                ];
                $obConnection->send(json_encode([
                    'status' => 'success'
                ]));
                break;
            case 'death':
                $arCorpse = Corpse::findFirst($arMessage['corpse_id'])->toArray();
                $arMessage = json_encode([
                    'event' => 'corpse_add',
                    'corpse' => $arCorpse
                ]);
                foreach ($this->arClients as $arClient){
                    $arClient['connection']->send($arMessage);
                }
                break;
            case 'pickup':
                $obConnection->send(json_encode([
                    'event' => 'pickup_ok',
                    'status' => 'success'
                ]));

                $arMessage = json_encode([
                    'event' => 'corpse_delete',
                    'corpse_id' => $arMessage['corpse_id']
                ]);
                foreach ($this->arClients as $arClient){
                    $arClient['connection']->send($arMessage);
                }
                break;
            default:
                $obConnection->send(json_encode([
                    'status' => 'error',
                    'reason' => 'unknown action'
                ]));
        }
    }

    public function onClose(ConnectionInterface $conn) {
        // The connection is closed, remove it, as we can no longer send it messages
        $this->clients->detach($conn);

        echo "Connection {$conn->resourceId} has disconnected\n";
    }

    public function onError(ConnectionInterface $conn, \Exception $e) {
        echo "An error has occurred: {$e->getMessage()}\n";

        $conn->close();
    }
}

$server = IoServer::factory(
    new HttpServer(
        new WsServer(
            new WsApp()
        )
    ),
    8081
);

$server->run();
