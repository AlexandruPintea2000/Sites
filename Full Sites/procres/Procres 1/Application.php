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
        if ( empty( $this->get_config_file() ) or $this->get_config_file()[0] != '"' or count( explode( ' ', $this->get_config_file() ) ) < 4 )
            return false;
        echo "echo";
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
			if ( $this->config_file_available() == true )
			{
				$this->controller = "sign_in_controller";
				$this->subprogram = "sign_in";
				$this->params = null;
				return;
			}

			$this->controller = "controller";
			$this->subprogram = "";
			$this->params = null;
		}
		else 
		{
			$path = explode( '/', $_GET[ 'path' ] );


			if ( count( $path ) == 1 )
			{
				$this->controller = $path[0];
				$this->subprogram = "index";
				$this->params = null;
			}

			if ( count( $path ) == 2 )
			{
				$this->controller = $path[0];
				$this->subprogram = $path[1];
				$this->params = null;
			}

			if ( count( $path ) >= 3 )
			{
				$this->controller = $path[0];
				$this->subprogram = $path[1];
				$this->params = $path[2];
			}
		}
	}


	function configure ()
	{

		$configure_file = fopen( 'configure', 'r' );

		$configure_file_strings = [];
		$configure_file_strings = explode( ' ', fgets( $configure_file ) );

		fclose( $configure_file );



		if ( empty( $configure_file_strings[0] ) )
		{
	        include_once "controllers/Config_controller.php";

	        $config_controller = new Config_controller(); // make an object of that class

	        $config_controller->load_view( "config", "config", null );


			$configure_file = fopen( 'configure', 'w' );
			fwrite( $configure_file, "went to config" );
			fclose( $configure_file );


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

		try
		{
	        include_once "controllers/" . ucfirst( $this->controller ) . ".php"; // includes the controller class we want

	        $ctrl = ucfirst( $this->controller ); // make an object of that class
	        $cntrl = new $ctrl();

	        $subprog = $this->subprogram; // call subprogram of that class
	        $cntrl->$subprog( $this->params );
    	}
		catch ( Exception $e )
		{
        	echo "<script> location.replace( \"index.php?path=config_controller/unavailable\" ); </script>";
        	return;
		} // if path is incorrect


	}
}