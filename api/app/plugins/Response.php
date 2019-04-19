<?
namespace Izica;

class Response {
    public static function show($arData = [], $sCode = 200){
        $response = new \Phalcon\Http\Response();
        $response->setHeader('Access-Control-Allow-Origin', '*');
        $response->setHeader('Access-Control-Allow-Headers', 'X-Requested-With');
        $response->setJsonContent($arData);
        $response->setStatusCode($sCode);
        $response->send();
        die();
    }

    public static function success($arData = []) {
        $arData['status'] = 'success';
        self::show($arData, 200);
    }

    public static function error($arData = []) {
        $arData['status'] = 'error';
        self::show($arData, 400);
    }
}
