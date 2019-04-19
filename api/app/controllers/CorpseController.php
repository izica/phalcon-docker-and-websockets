<?php

use Izica\Response;
use Izica\Validation;
use Phalcon\Mvc\Controller;

class CorpseController extends Controller
{
    public function createAction()
    {
        $obValidation = new Validation([
            'character_id' => [Validation::required()],
            'floor_id'     => [Validation::required()],
            'x'            => [Validation::required()],
            'y'            => [Validation::required()],
            'data'         => [Validation::required()]
        ]);
        $arMessages = $obValidation->validate($_POST);
        if ($arMessages) {
            Response::error([
                'messages' => $arMessages
            ]);
        }
        $obCorpse = new Corpse($_POST);
        $obCorpse->status = 'active';
        $obCorpse->save();

        Response::success([
            'corpse' => $obCorpse->toArray()
        ]);
    }

    public function pickupAction()
    {
        $obValidation = new Validation([
            'id' => [Validation::required()],
        ]);
        $arMessages = $obValidation->validate($_POST);
        if ($arMessages) {
            Response::error([
                'messages' => $arMessages
            ]);
        }
        $obCorpse = Corpse::findFirst($_POST['id']);
        $obCorpse->status = 'picked';
        $obCorpse->save();

        Response::success([
            'corpse' => $obCorpse->toArray()
        ]);
    }

    public function floorAction()
    {
        $obValidation = new Validation([
            'floor_id' => [Validation::required()],
        ]);
        $arMessages = $obValidation->validate($_POST);
        if ($arMessages) {
            Response::error([
                'messages' => $arMessages
            ]);
        }
        $arCorpse = Corpse::query()
            ->where("floor_id = " . $_POST['floor_id'] . " AND status = 'active'")
            ->execute()
            ->toArray();

        Response::success([
            'corpses' => $arCorpse
        ]);
    }


}
