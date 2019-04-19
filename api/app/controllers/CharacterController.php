<?php

use Izica\Response;
use Izica\Validation;
use Phalcon\Mvc\Controller;

class CharacterController extends Controller
{
    public function createAction()
    {
        $obValidation = new Validation([
            'user_id' => [Validation::required()],
            'floor_id' => [Validation::required()]
        ]);
        $arMessages = $obValidation->validate($_POST);
        if ($arMessages) {
            Response::error([
                'messages' => $arMessages
            ]);
        }
        $obCharacter = new Character($_POST);
        $obCharacter->save();

        Response::success([
            'character' => $obCharacter->toArray()
        ]);
    }

    public function updateAction()
    {
        $obValidation = new Validation([
            'id' => [Validation::required()],
            'floor_id' => [Validation::required()]
        ]);
        $arMessages = $obValidation->validate($_POST);
        if ($arMessages) {
            Response::error([
                'messages' => $arMessages
            ]);
        }
        $obCharacter = Character::findFirst($_POST['id']);
        $obCharacter->floor_id = $_POST['floor_id'];
        $obCharacter->save();

        Response::success([
            'character' => $obCharacter->toArray()
        ]);
    }
}
