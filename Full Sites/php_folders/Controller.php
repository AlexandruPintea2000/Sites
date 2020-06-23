<?php

include_once "View.php";

class Controller 
{
    public $model;
    public $view;

    public function loadView( $controllerFile, $viewFile, $params = null ) 
    {
        $view = new View();
        $view->setData( $params );
        $view->display( $controllerFile, $viewFile );
    }
}