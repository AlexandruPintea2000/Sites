<?php

class View 
{
    public $data;

    public function setData( $data ) 
    {
        $this->data = $data;
    }
    
    public function display( $controllerFile, $viewFile ) 
    {
        ob_start();
        include_once('views/' . $controllerFile . '/' . $viewFile . '.php');
        $out = ob_get_clean();
        echo $out;
    }
}