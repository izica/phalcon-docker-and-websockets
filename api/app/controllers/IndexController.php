<?php

use Izica\Response;
use Phalcon\Mvc\Controller;

class IndexController extends Controller
{
    public function indexAction()
    {
        Response::show(['asda' => 'asd']);
    }
}
