<?php

class View 
{
    public $data;

    function show_nav ()
    {
        $nav_html = fopen( "views/nav.html", "r" );

        $nav = "";
        while ( $temp = fgets( $nav_html ) )
            $nav = $nav . $temp;

        fclose( $nav_html );

        echo $nav;
    }

    function show_company ()
    {
        

        echo "";
    }

    public function set_data( $data ) 
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