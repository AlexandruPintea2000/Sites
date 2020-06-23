<?php

include_once "Controller.php";

class Config_controller extends Controller
{

	// For "Unavailable"

	function unavailable ()
	{
	   	$data = [
            'company'=>$this->get_company()
            ];

	   	$this->load_view( "config", "unavailable", $data );		
	}


	// For "Configure: servername, username, password, company_name and Make: database, tables"


	function config ()
	{
		if ( $this->config_file_available() ) // prevent reconfig
		{
			$this->replace_location( "index.php?path=config_controller/config_complete" );
			return;
		}

    	$this->load_view( "config", "config", null );
	}

	function config_complete ()
	{
    	$this->load_view( "config", "config_complete", null );
	}

	function complete_config ( $data )
	{
		if ( $this->config_file_available() ) // prevent reconfig
		{
			$this->replace_location( "index.php?path=config_controller/config_complete" );
			return;
		}




		$params = explode( "(@)", $data );


		// check for "empty" or replace "empty" with "" 


		if ( $params[ 0 ] == "empty" or $params[ 1 ] == "empty"  )
		{
			$this->alert( "Please fill all fields" );
			$this->config();
			return;
		}

		if ( $params[ 2 ] == "empty" )
			$params[ 2 ] = "";




		// set configure file to: servername user




		$configure_file = fopen( 'configure', 'w' ); // clears file
		fclose( $configure_file );


		$configure_file = fopen( 'configure', 'w' );

		fwrite( $configure_file, $this->add_separators( $params[ 0 ] ) . ' ' ); // servername
		fwrite( $configure_file, $this->add_separators( $params[ 1 ] ) . ' ' ); // user
		fwrite( $configure_file, $this->add_separators( $params[ 2 ] ) . ' ' ); // password

		fclose( $configure_file );



		// make database and tables



		$conn = new mysqli( $params[ 0 ], $params[ 1 ], $params[ 2 ] );
		$conn->query( "CREATE DATABASE application_database;" );


		include_once "Model.php";
		$db = new Database( $params[ 0 ], $params[ 1 ], $params[ 2 ], "application_database" );

		// make "contracts" table
		$columns = [ "id", "contract_name", "details", "client_id", "contract_date", "deadline_date" ];
		$types = [ "int", "varchar(500)", "varchar(500)", "int", "date", "date" ];
		$db->make_table( "contracts", $columns, $types );

		// make "parts" table
		$columns = [ "id", "part_name", "contract_id", "progress"  ];
		$types = [ "int", "varchar(500)", "int", "int" ];
		$db->make_table( "parts", $columns, $types );


		// make "users" table
		$columns = [ "id", "username", "password", "firstname", "lastname", "email", "type" ];
		$types = [ "int", "varchar(500)", "varchar(500)", "varchar(500)", "varchar(500)", "varchar(500)", "varchar(500)" ];
		$db->make_table( "users", $columns, $types );

		// make "tasks" table
		$columns = [ "id", "user_id", "part_id" ];
		$types = [ "int", "int", "int" ];
		$db->make_table( "tasks", $columns, $types );

		$this->config_admin();

	}



	// For "Configure: admin user"




	function config_admin ()
	{
		if ( $this->config_file_available() ) // prevent reconfig
		{
			$this->replace_location( "index.php?path=config_controller/config_complete" );
			return;
		}

    	$this->load_view( "config", "config_admin", null );
	}

	function complete_config_admin ( $data )
	{
		if ( $this->config_file_available() ) // prevent reconfig
		{
			$this->replace_location( "index.php?path=config_controller/config_complete" );
			return;
		}





		$params = explode( "(@)", $data );


		// check for "empty" or replace "empty" with "" 

		foreach ( $params as $param )
		{
			if ( $param == "empty"  )
			{
				$this->alert( "Please fill all fields" );
				$this->config_admin();
				return;
			}
		}

		// add company name to "configure" file

		$server_and_user = $this->get_config_file();

		$configure_file = fopen( 'configure', 'w' );

		fwrite( $configure_file, $server_and_user . $this->add_separators( $params[ 5 ] ) );

		fclose( $configure_file );



		// make database and tables

		include_once "Model.php";
		$db = new Database( $this->get_server(), $this->get_user(), $this->get_password(), "application_database" );
		$users_table = new Table( "users", $db );

		$admin_row = [ 0, $params[ 0 ], $params[ 1 ], $params[ 2 ], $params[ 3 ], $params[ 4 ], "admin" ];
		$users_table->add_row( $admin_row );

		$this->replace_location( "index.php?path=user_controller/view/0" );

	}



	// For "Set Company Name"



	function set_company_name( string $company_name )
	{
		$config_file_strings = $this->get_config_file();
		$parts = explode( ' ', $config_file_strings );

		$parts_that_remain = $parts[ 0 ] . ' ' . $parts[ 1 ] . ' ' . $parts[ 2 ] . ' ' . $this->add_separators( $company_name );



		$configure_file = fopen( 'configure', 'w' );
		fclose( $configure_file ); // clears "configure" file



		$configure_file = fopen( 'configure', 'w' );

		fwrite( $configure_file, $parts_that_remain );

		fclose( $configure_file );

		$this->replace_location( "index.php?path=user_controller/view/" . $_SESSION[ 'id' ] );
	}



	// For "Save Database and Load Database"



	function includes ()
	{
		include_once "models/Part.php";
		include_once "models/User.php";
		include_once "models/Task.php";
		include_once "models/Contract.php";
	}

	function save_database ()
	{

		$this->includes();

		$parts = get_parts();
		$users = get_users();
		$tasks = get_tasks();
		$contracts = get_contracts();



		$sql = "";

		foreach ( $parts as $part )
		{
			$sql = $sql . "INSERT INTO parts ( id, part_name, contract_id, progress ) VALUES ( ";
			$sql = $sql . $part->get_id() . ", ";
			$sql = $sql . "\"" . $part->get_part_name() . "\", ";
			$sql = $sql . $part->get_contract_id() . ", ";
			$sql = $sql . $part->get_progress() . " );\n";
		}

		$sql = $sql . "\n";

		foreach ( $users as $user )
		{
			$sql = $sql . "INSERT INTO users ( id, username, password, firstname, lastname, email, type ) VALUES ( ";
			$sql = $sql . $user->get_id() . ", ";
			$sql = $sql . "\"" . $user->get_username() . "\", ";
			$sql = $sql . "\"" . $user->get_password() . "\", ";
			$sql = $sql . "\"" . $user->get_firstname() . "\", ";
			$sql = $sql . "\"" . $user->get_lastname() . "\", ";
			$sql = $sql . "\"" . $user->get_email() . "\", ";
			$sql = $sql . "\"" . $user->get_type() . "\" );\n ";
		}

		$sql = $sql . "\n";

		foreach ( $tasks as $task )
		{
			$sql = $sql . "INSERT INTO tasks ( id, user_id, part_id ) VALUES ( ";
			$sql = $sql . $task->get_id() . ", ";
			$sql = $sql . $task->get_user_id() . ", ";
			$sql = $sql . $task->get_part_id() . " );\n";
		}

		$sql = $sql . "\n";

		foreach ( $contracts as $contract )
		{
			$sql = $sql . "INSERT INTO contracts ( id, contract_name, details, client_id, contract_date, deadline_date ) VALUES ( ";
			$sql = $sql . $contract->get_id() . ", ";
			$sql = $sql . "\"" . $contract->get_contract_name() . "\", ";
			$sql = $sql . "\"" . $contract->get_details() . "\", ";
			$sql = $sql . $contract->get_client_id() . ", ";
			$sql = $sql . $contract->get_contract_date() . ", ";
			$sql = $sql . $contract->get_deadline_date() . " );\n";
		}


		$save_database_file = fopen( 'database', 'w' );

		fwrite( $save_database_file, $sql );

		fclose( $save_database_file );

		echo "Please make sure to: <a href=\"database\" style=\"font-size: 20px;\" download> Download Database</a> and store it. When done, <a href=\"index.php?path=user_controller/view/" . $_SESSION[ 'id' ] . "\" style=\"font-size: 20px;\" > Return</a>.";
	}

	function load_database ( string $file )
	{
		$this->includes();

		$db = new Database( $this->get_server(), $this->get_user(), $this->get_password(), "application_database" );

		echo $file;

		$db->sql( $file );

		$this->alert( "Loaded" );

//		$this->replace_location( "index.php?path=user_controller" );
	}

}