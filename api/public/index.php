<?php
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    header("Access-Control-Allow-Credentials: true");
    header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
    header('Access-Control-Max-Age: 1000');
    header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token, Authorization');
    header('Access-Control-Allow-Origin: *');
    die();
}

require '../../vendor/autoload.php';
require '../app/plugins/autoloader.php';

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

/**
 * Very simple MVC structure
 */

$loader = new Loader();

$loader->registerDirs(
    [
        "../app/controllers/",
        "../app/models/"
    ]
);

$loader->register();

$di = new Di();

// Registering a router
$di->set("router", Router::class);

// Registering a dispatcher
$di->set("dispatcher", MvcDispatcher::class);

// Registering a Http\Response
$di->set("response", Response::class);

// Registering a Http\Request
$di->set("request", Request::class);

// Registering the view component
$di->set(
    "view",
    function () {
        $view = new View();

        $view->setViewsDir("../app/views/");

        return $view;
    }
);

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

//Registering the Models-Metadata
$di->set("modelsMetadata", ModelMetadata::class);

//Registering the Models Manager
$di->set("modelsManager", ModelManager::class);

try {
    $application = new Application($di);

    $response = $application->handle();

    $response->send();
} catch (Exception $e) {
    echo $e->getMessage();
}
