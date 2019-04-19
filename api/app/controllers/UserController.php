<?php

use Izica\Response;
use Izica\Validation;
use Phalcon\Mvc\Controller;

class UserController extends Controller
{
    public function registerAction()
    {
        $obValidation = new Validation([
            'device_id' => [Validation::required()]
        ]);
        $arMessages = $obValidation->validate($_POST);
        if ($arMessages) {
            Response::error([
                'messages' => $arMessages
            ]);
        }
        $obUser = new User($_POST);
        $obUser->save();

        Response::success([
            'user' => $obUser->toArray()
        ]);
    }
}
