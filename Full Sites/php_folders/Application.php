<?php


class Application 
{
    public static $application; // static so as to be usable without the class
    public $path=[];
    public $controller;

 
    public function __construct() 
    {
        self::$application = $this; // makes an object of type "Application" ( usable without the class )
    }
 

    public function deploy () 
    {
        $this->get_params();

        echo $this->path['controller'] . ' ';
        echo $this->path['subprogram'] . ' ';
        echo $this->path['param'];

        include_once "controllers/" . ucfirst( $this->path['controller']) . ".php"; // includes the controller class we want

        $ctrl = ucfirst($this->path['controller']); // make an object of that class
        $this->controller = new $ctrl();

        $subprogram = $this->path['subprogram']; // call subprogram of that class
        $this->controller->$subprogram( $this->path['param'] );
    }


    private function get_params() 
    {
        if( ! isset( $_GET['f'] ) ) // for 0 parameters
        {
            $this->path['controller'] = "default_controller";
            $this->path['subprogram'] = "index";
            $this->path['param'] = null;
        } 
        else // for 1, 2, 3 or more parameters
        {
            $parts = explode('/',$_GET['f']);

            echo count( $parts ) . ' ';

            if ( count( $parts ) == 1 )
            {
                $this->path['controller'] = $parts[0];
                $this->path['subprogram'] = "index";
                $this->path['param'] = null;
            }

            if ( count( $parts ) == 2 )
            {
                $this->path['controller'] = $parts[0];
                $this->path['subprogram'] = $parts[1];
                $this->path['param'] = null;
            }

            if ( count( $parts ) >= 3 )
            {
                $this->path['controller'] = $parts[0];
                $this->path['subprogram'] = $parts[1];
                $this->path['param'] = $parts[2];
            }
        }
    }


}

// path is:  controller/action/param