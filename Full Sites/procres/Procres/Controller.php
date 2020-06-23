<?php

include_once "View.php";

class Controller 
{
    public $model;
    public $view;



    // config file



    function config_file_available ()
    {
        if ( empty( $this->get_config_file() ) or $this->get_config_file()[0] != '"' or explode( ' ', $this->get_config_file() )[0] == "went" or explode( ' ', $this->get_config_file() )[0] == "complete" or count( explode( ' ', $this->get_config_file() ) ) < 4 )
            return false;

        return true;
    }

    public function get_config_file ()
    {
 		$configure_file = fopen( 'configure', 'r' );

 		$strings = fgets( $configure_file );

		fclose( $configure_file );

		return $strings;
    }

    public function add_separators( string $a )
    {
    	return '"' . $a . '"';
    }

    public function remove_separators( string $a )
    {

        return explode( '"', $a )[1];

    	// $i = 0;

    	// $result = "";

    	// $once = true;
    	// while ( $a[ $i ]  )
    	// {
    	// 	if ( $a[ $i ] == '"' and $once == true )
    	// 	{
    	// 		$i = $i + 1;
    	// 		$once = false;
    	// 		continue;
    	// 	}

	   	// 	if ( $a[ $i ] == '"' and $once == false )
    	// 		return $result;

    	// 	$result = $result . $a[ $i ];

  			// $i = $i + 1;
    	// }

    	// return -1;
    }




    public function static_alert ( string $a )
    {
        echo "<script> alert( \"" . $a . "\" ); </script>";
    }

    public function alert ( string $a )
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

    public function replace_location ( string $a )
    {
        echo "<script> location.replace( \"" . $a . "\" ); </script>";
    }

    public function unavailable()
    {
        $this->replace_location( "index.php?path=config_controller/unavailable" );
    }

    public function signed_in ()
    {
        if ( !isset( $_SESSION[ 'signed_in' ] ) or $_SESSION[ 'signed_in' ] == false )
        {
            $this->static_alert( "You are signed out." );

            $this->replace_location( "index.php?path=sign_in_controller/sign_in" );

            return false;
        }

        if ( $_SESSION[ 'type' ] == "client" )
        {
            $this->replace_location( "index.php?path=user_controller/client_view/" . $_SESSION[ 'id' ] );

            return false;
        }

        return true;
    }

    public function signed_in_is_admin ()
    {
        if ( $this->signed_in() == false )
            return false;


        if ( $_SESSION[ 'type' ] != "admin" )
        {
            $this->alert( "Please leave this task to admins." );

            $this->replace_location( "index.php?path=user_controller/view/" . $_SESSION[ 'id' ] );

            return false;
        }

        return true;
    }







    public function get_server ()
    {
    	return $this->remove_separators( explode( ' ', $this->get_config_file() )[ 0 ] );
    }

    public function get_user ()
    {
    	return $this->remove_separators( explode( ' ', $this->get_config_file() )[ 1 ] );
    }

    public function get_password ()
    {
        return $this->remove_separators( explode( ' ', $this->get_config_file() )[ 2 ] );
    }

    public function get_company ()
    {
        return ucfirst( $this->remove_separators( explode( ' ', $this->get_config_file() )[ 3 ] ) );
    }




    public function load_view( $controllerFile, $viewFile, $params = null ) 
    {
        $view = new View();
        $view->set_data( $params );
        $view->display( $controllerFile, $viewFile );
    }
}