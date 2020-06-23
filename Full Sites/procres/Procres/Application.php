<?php

class Application 
{
	private $controller = "";
	private $subprogram = "";
	private $params = "";

	public static $application;


	function set_session ()
	{
		session_start();
	}

	function __construct ()
	{
		$this->set_session();

		self::$application = $this;
	}





    function config_file_available ()
    {
        if ( empty( $this->get_config_file() ) or $this->get_config_file()[0] != '"' or explode( ' ', $this->get_config_file() )[0] == "went" or explode( ' ', $this->get_config_file() )[0] == "complete" or count( explode( ' ', $this->get_config_file() ) ) < 4 )
            return false;

        return true;
    }

    function get_config_file ()
    {
 		$configure_file = fopen( 'configure', 'r' );

 		$strings = fgets( $configure_file );

		fclose( $configure_file );

		return $strings;
    }





	function get_path ()
	{
		if ( ! isset( $_GET[ 'path' ] ) )
		{
			if ( isset( $_SESSION[ 'id' ] ) )
			{
				$this->controller = "user_controller";
				$this->subprogram = "view";
				$this->params = $_SESSION[ 'id' ];
				return;
			}		

			if ( $this->config_file_available() == true )
			{
				$this->controller = "sign_in_controller";
				$this->subprogram = "sign_in";
				$this->params = null;
				return;
			}

			$this->controller = "config";
			$this->subprogram = "config";
			$this->params = null;
		}
		else 
		{
			$path = explode( '/', $_GET[ 'path' ] );

			if ( count( $path ) < 1 )
			{
				echo "Path empty";
			}

			if ( $path[ 0 ] == "error" )
			{
				if ( isset( $_SESSION[ 'id' ] ) )
				{
			        echo "<script> location.replace( \"index.php?path=user_controller/view/" . $_SESSION[ 'id' ] . "\" ); </script>";
					return;
				}

				if ( $this->config_file_available() == true )
				{
			        echo "<script> location.replace( \"index.php?path=sign_in_controller/sign_in\" ); </script>";
					return;
				}

			        echo "<script> location.replace( \"index.php?path=config_controller/config\" ); </script>";
			}




			if ( count( $path ) == 1 )
			{
				$this->controller = $path[0];
				$this->subprogram = "index";
				$this->params = null;
				return;				
			}

			if ( count( $path ) == 2 )
			{
				$this->controller = $path[0];
				$this->subprogram = $path[1];
				$this->params = null;
				return;				
			}

			if ( count( $path ) >= 3 )
			{
				$this->controller = $path[0];
				$this->subprogram = $path[1];
				$this->params = $path[2];
				return;				
			}
		}
	}


	function configure ()
	{
		$configure_file = fopen( 'configure', 'r' );

		$configure_file_strings = [];
		$configure_file_strings = explode( ' ', fgets( $configure_file ) );

		fclose( $configure_file );

//		echo $configure_file_strings[0];

		if ( empty( $configure_file_strings[0] ) or $configure_file_strings[0] == "went" )
		{

	        include_once "controllers/Config_controller.php";

	        $config_controller = new Config_controller(); // make an object of that class


			$configure_file = fopen( 'configure', 'w' );
			fwrite( $configure_file, "went to config" );
			fclose( $configure_file );



	        $config_controller->config();

	        return "went to config";
		}


		if ( $configure_file_strings[0] == "complete" )
		{
	        include_once "controllers/Config_controller.php";

	        $config_controller = new Config_controller(); // make an object of that class


//			$configure_file = fopen( 'configure', 'w' );
//			fwrite( $configure_file, "now in complete config" );
//			fclose( $configure_file );


	        $data = get_config_file();

	        $config_controller->complete_config( $data );

	        return "went to config";
	      }


	}



	function deploy ()
	{
		if ( $this->configure() == "went to config" )
			return;

		$this->get_path();



//		echo $this->controller . " ";
//		echo $this->subprogram . " ";
//		echo $this->params . " <br>";


//		foreach ( $_SESSION as $session )
//			echo '<br>"' . $session . '" ';

			echo "
	
			<link rel=\"stylesheet\" href=\"/Procres/files/style.css\">

			<style>

				html 
				{
					padding: 0px;
				}

				#error 
				{
					width: 70%;
					font-size: 50px;
					height: 90%;		
					text-align: center;
					border: 3px solid rgb( 234, 234, 234 );
					background-color: rgb( 243, 243, 243 );
					padding: 30px 0px 10px 0px;
					border-radius: 10px;
				}

				#error p
				{
					padding: 10px;
					font-size: 20px;
				}


			</style>

			";



			if ( isset( $_SESSION[ 'id' ] ) )
			{
				if ( $_SESSION[ 'type' ] != "client" )
				{


					// Show nav

			        $nav_html = fopen( "views/nav.html", "r" );

			        $nav = "";
			        while ( $temp = fgets( $nav_html ) )
			            $nav = $nav . $temp;

			        fclose( $nav_html );

			        echo $nav;



			        // Show Company

			 		$configure_file = fopen( 'configure', 'r' );

			 		$strings = fgets( $configure_file );

					fclose( $configure_file );

					$parts = explode( ' ', $strings );

					$company = explode( '"', $parts[ 3 ] )[ 1 ];


					echo "<div class=\"company\"> " . ucfirst( $company ) . " </div>";
				}
			}




			echo "<div class=\"div_center\">";
			echo "	<div id=\"error\">";

			echo "		<h2> Loading </h2> ";
			echo "		<p> If not loading, Please <a href=\"index.php?path=error\"> Return</a>. </p> ";

			echo "	</div>";
			echo "</div>";



			echo " <div class=\"application_div\">"; // div for application / errors

//	        echo "<style> .application_div { display: none; } </style>"; // hide errors

	        include_once "controllers/" . ucfirst( $this->controller ) . ".php"; // includes the controller class we want

	        $ctrl = ucfirst( $this->controller ); // make an object of that class
	        $cntrl = new $ctrl();

	        $subprog = $this->subprogram; // call subprogram of that class
	        $cntrl->$subprog( $this->params );
	        
	        echo "</div> <style> .application_div{ display: initial; } #error { display: none !important; } </style>"; // show application without errors
	}
}