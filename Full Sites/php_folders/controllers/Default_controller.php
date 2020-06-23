<?php

include_once "Controller.php";

class Default_controller extends Controller 
{
    public function index() 
    {
        $this->loadView( "default", "index", null );
    }
}