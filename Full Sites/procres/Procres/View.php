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

    function static_alert ( string $a )
    {
        echo "<script> alert( \"" . $a . "\" ); </script>";
    }

    function alert ( string $a )
    {
        echo "<script> 

                function dismiss_alert ()
                {
                    document.getElementById( \"alert_div\" ).style.display = \"none\";
                }


         </script>";

        echo "<style> 

            #alert
            {
                position: fixed;

                max-width: 1000px;
                max-height: 700px;

                min-width: 200px;
                min-height: 100px;

                padding: 0px 10px;

                top: 20%;

                text-align: center;

                background-color: rgb( 234, 234, 234 );
                border: 3px solid rgb( 123, 123, 123 );
                border-radius: 10px;

                z-index: 1400;
            }

         </style>";

        echo "<div class=\"div_center\" id=\"alert_div\">";
        echo "<div id=\"alert\" onclick=\"dismiss_alert()\" >";

        echo "<p> ( click to dismiss ) </p>";
        echo "<h3>" . $a . "</h3>";

        echo "</div>";
        echo "</div>";
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