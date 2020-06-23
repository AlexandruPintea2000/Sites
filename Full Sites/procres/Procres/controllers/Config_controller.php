<?php

include_once "Controller.php";
include_once "models/User.php";


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
//		$this->alert( "complete_config" );

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
		fwrite( $configure_file, $this->add_separators( $params[ 2 ] ) ); // password

		fclose( $configure_file );


//		$this->alert( "config" );



		// make database and tables



		$conn = new mysqli( $params[ 0 ], $params[ 1 ], $params[ 2 ] );
		$conn->query( "CREATE DATABASE application_database;" );


		include_once "Model.php";
		$db = new Database( $params[ 0 ], $params[ 1 ], $params[ 2 ], "application_database" );

		// make "contracts" table
		$columns = [ "id", "contract_name", "details", "client_id", "contract_date", "deadline_date" ];
		$types = [ "int", "varchar(500)", "varchar(500)", "int", "date", "date" ];
		$db->make_table( "contracts", $columns, $types );

		// make "deleted_contracts" table
		$columns = [ "id", "deleted_contract_name", "contract_details" ];
		$types = [ "int", "varchar(500)", "varchar(500)" ];
		$db->make_table( "deleted_contracts", $columns, $types );

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
    	$this->load_view( "config", "config_admin", null );
	}

	function complete_config_admin ( $data )
	{
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

		if( username_taken( $params[ 0 ] ) )
		{
			$this->alert( "Username taken. Please retry." );
			$this->config_admin();
			return;
		}

		// add company name to "configure" file

		$server_and_user = $this->get_config_file();

		$configure_file = fopen( 'configure', 'w' );

		fwrite( $configure_file, $server_and_user . ' ' . $this->add_separators( $params[ 5 ] ) );

		fclose( $configure_file );



		// make database and tables

		include_once "Model.php";
		$db = new Database( $this->get_server(), $this->get_user(), $this->get_password(), "application_database" );
		$users_table = new Table( "users", $db );

		$admin_id = get_user_id();

		$admin_row = [ $admin_id, $params[ 0 ], $params[ 1 ], $params[ 2 ], $params[ 3 ], $params[ 4 ], "admin" ];
		$users_table->add_row( $admin_row );


        $_SESSION[ 'signed_in' ] = true;
        $_SESSION[ 'id' ] = $admin_id;
        $_SESSION[ 'name' ] = $$params[ 2 ] . ' ' . $params[ 3 ];
        $_SESSION[ 'email' ] = $params[ 4 ];
        $_SESSION[ 'username' ] = $params[ 0 ];
        $_SESSION[ 'type' ] = "admin";


        $this->load_config_data();
//		$this->replace_location( "index.php?path=user_controller/view/" . $admin_id );
	}


	function load_config_data ()
	{
		if ( $this->signed_in() == false ) // make sure the user is signed in and is not a client
			return;

		if ( $this->signed_in_is_admin() == false ) // make sure that signed in user is an admin
			return;



		$this->includes();

		$users = get_users();
		$contracts= get_contracts();
		$parts = get_parts();

		$deleted_contracts = get_deleted_contracts();
		$tasks = get_tasks();

		if ( count( $users ) != 1 or
			 count( $contracts ) != 0 or 
			 count( $parts ) != 0 or
			 count( $deleted_contracts ) != 0 or
			 count( $tasks ) != 0 )

		{
			$this->replace_location( "index.php?path=user_controller/view/" . $_SESSION[ 'id' ] );
			return;
		}



		$this->alert( "Default data loaded!" );

		// make database and tables

		include_once "Model.php";
		$db = new Database( $this->get_server(), $this->get_user(), $this->get_password(), "application_database" );

		// Users
		for ( $i = 0; $i < 30; $i = $i + 1 )
		{
			$sql = "INSERT INTO users ( id, username, password, firstname, lastname, email, type ) VALUES ( ";

			$sql = $sql . (string) ( $i - 31 ) . ", ";

			if ( $i % 3 == 0 or $i % 5 == 0 )
			{
				if ( $i % 3 == 0 and $i % 5 != 0 )
					$sql = $sql . "\"employee_" . (string) ( $i + 1 ). "\", ";
				else
					$sql = $sql . "\"admin_" . (string) ( $i + 1 ) . "\", ";
			}
			else
			{
				if ( $i + 1 > 17 and $i + 1 < 19 )
					$sql = $sql . "\"client_" . (string) ( 65 ) . "\", ";
				else
					$sql = $sql . "\"client_" . (string) ( $i + 1 ) . "\", ";
			}

			if ( $i + 1 > 17 and $i + 1 < 19 )
			{
				$sql = $sql . "\"password_" . (string) ( 65 ) . "\", ";
				$sql = $sql . "\"Firstname_" . (string) ( 65 ) . "\", ";
				$sql = $sql . "\"Lastname_" . (string) ( 65 ) . "\", ";
				$sql = $sql . "\"email_" . (string) ( 65 ) . "@email.com\", ";
			}
			else
			{
				$sql = $sql . "\"password_" . (string) ( $i + 1 ) . "\", ";
				$sql = $sql . "\"Firstname_" . (string) ( $i + 1 ) . "\", ";
				$sql = $sql . "\"Lastname_" . (string) ( $i + 1 ) . "\", ";
				$sql = $sql . "\"email_" . (string) ( $i + 1 ) . "@email.com\", ";
			}

			if ( $i % 3 == 0 or $i % 5 == 0 )
			{
				if ( $i % 3 == 0 and $i % 5 != 0 )
					$sql = $sql . "\"employee\" ); ";
				if ( $i % 5 == 0 )
					$sql = $sql . "\"admin\" ); ";
			}
			else
				$sql = $sql . "\"client\" ); ";

			$db->sql( $sql );
		}



		// Parts
		for ( $i = 0; $i < 40; $i = $i + 1 )
		{
			$sql = "INSERT INTO parts ( id, part_name, contract_id, progress ) VALUES ( ";

			$sql = $sql . (string) ( $i - 45 ) . ", ";

			if ( $i != 36 )
				$sql = $sql . "\"Part_" . (string) ( $i + 1 ) . "\", ";
			else
				$sql = $sql . "\"Part_" . (string) (45) . "\", ";

			// $sql = $sql . (string) ( - ( $i % 17 + 4 ) ) . ", ";
			$sql = $sql . (string) ( - ( $i % 17 + 4 ) ) . ", ";


			if ( $i > 10 and $i <= 21 )
			{
					if ( $i + 64 > 76 and $i + 64 < 79 )
						$sql = $sql . (string) ( 65 ) . " ); ";
					else
						$sql = $sql . (string) ( $i + 64 ) . " ); ";
			}
			else
			{
				if ( ( $i + 5 > 36 and $i + 5 < 39 ) or ( $i + 5 > 36 and $i + 5 < 39 )  or ( $i + 30 > 36 and $i + 30 < 39 ) or ( $i + 30 > 36 and $i + 30 < 39 ) or ( $i + 30 > 16 and $i + 30 < 21 ) or ( $i + 30 > 16 and $i + 30 < 21 )  or ( $i + 5 > 16 and $i + 5 < 21 ) or ( $i + 5 > 16 and $i + 5 < 21 ) or ( $i + 30 > 76 and $i + 30 < 79 ) or ( $i + 30 > 76 and $i + 30 < 79 )  or ( $i + 5 > 76 and $i + 5 < 79 ) or ( $i + 5 > 76 and $i + 5 < 79 ) )
					$sql = $sql . (string) ( 65 ) . " ); ";
				else
				{
					if ( $i > 31 )
						$sql = $sql . (string) ( $i + 5 ) . " ); ";
					else
						$sql = $sql . (string) ( $i + 30 ) . " ); ";
				}
			}

			$db->sql( $sql );
		}




		// Contracts

		$client_id = -4;
		for ( $i = 0; $i <= 40; $i = $i + 1 )
		{
			$sql = "INSERT INTO contracts ( id, contract_name, details, client_id, contract_date, deadline_date ) VALUES ( ";

			$sql = $sql . (string) ( - ( $i % 17 + 4 ) ) . ", ";

			if ( $i % 17 + 4 > 17 and  $i % 17 + 4 < 19 )
			{
				$sql = $sql . "\"Contract " . (string) 65 . "\", ";
				$sql = $sql . "\"details\nof:\n\nContract " . (string) 65 . "\", ";				
			}
			else
			{
				$sql = $sql . "\"Contract " . (string) ( (int) ( $i % 17 + 4 ) ) . "\", ";
				$sql = $sql . "\"details\nof:\n\nContract " . (string) ( (int) ( $i % 17 + 4 ) ) . "\", ";

			}

			$client_id = $client_id - 4;
			if ( $client_id != $client_id % 21 )
				$client_id = - 4;
			$sql = $sql . (string) $client_id . ", ";



			$sql = $sql . "\"2010-0" . (string) ( $i % 5 + 1 ) . "-0" . (string) ( ( 40 - $i ) % 9 + 1 ) . "\", ";
			$sql = $sql . "\"2021-0" . (string) ( ( 40 - $i ) % 5 + 1 ) . "-0" . (string) ( $i % 9 + 1 ) . "\" ); ";

			$db->sql( $sql );
		}


		// Tasks

		$user_id = [ -3, -5, -6, -10, -29, -9, -21, -15, -31, -5 ];
		for ( $i = 0; $i < 40; $i = $i + 1 )
		{
			$sql = "INSERT INTO tasks ( id, user_id, part_id, details ) VALUES ( ";

			$sql = $sql . (string) ( $i - 45 ) . ", ";


			// Give Admin


			// if ( $i % 10 != 0 )
				$sql = $sql . (string) $user_id[ $i % 10 ] . ", ";
			// else			
			// 	$sql = $sql . (string) $_SESSION[ 'id' ] . ", ";



			$sql = $sql . (string) ( $i - 45 ) . ", ";

			$sql = $sql . "\"";

			$increase = 0;
			for ( $l = 2; $l <= ( $increase + $i ) % 5; $l = $l + 1 )
			{
				$increase = $increase - 2;
				$sql = $sql . "{(}given{)}task " . (string) $l;
			}

			for ( $l = 2; $l <= ( $increase + $i ) % 9; $l = $l + 1 )
			{
				$increase = $increase - 4;
				$sql = $sql . "{(}completed{)}task " . (string) ( $l + 21 );
			}


			$sql = $sql . "\" ); ";

			$db->sql( $sql );
		}


		$this->replace_location( "index.php?path=user_controller/view/" . $_SESSION[ 'id' ] );
	}

	function delete_config_data ()
	{
		include_once "models/Part.php";
		include_once "models/User.php";
		include_once "models/Task.php";
		include_once "models/Contract.php";

		$users = get_users();
		$contracts = get_contracts();
		$parts = get_parts();
		$tasks = get_tasks();


		$this->alert( "Default data deleted!" );

		// make database and tables

		include_once "Model.php";
		$db = new Database( $this->get_server(), $this->get_user(), $this->get_password(), "application_database" );

		$users_table = new Table( "users", $db );
		foreach ( $users as $user )
			if ( $user->get_id() < 0 )
				$users_table->remove_row_through_id( $user->get_id() );
			
		$contracts_table = new Table( "contracts", $db );
		foreach ( $contracts as $contract )
			if ( $contract->get_id() < 0 )
				$contracts_table->remove_row_through_id( $contract->get_id() );
			
		$parts_table = new Table( "parts", $db );
		foreach ( $parts as $part )
			if ( $part->get_id() < 0 )
				$parts_table->remove_row_through_id( $part->get_id() );
			
		$tasks_table = new Table( "tasks", $db );
		foreach ( $tasks as $task )
			if ( $task->get_id() < 0 )
				$tasks_table->remove_row_through_id( $task->get_id() );


		$this->replace_location( "index.php?path=user_controller/view/" . $_SESSION[ 'id' ] );
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
		$deleted_contracts = get_deleted_contracts();


		$this->save_database_loadable();


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
			$sql = $sql . "INSERT INTO tasks ( id, user_id, part_id, details ) VALUES ( ";
			$sql = $sql . $task->get_id() . ", ";
			$sql = $sql . $task->get_user_id() . ", ";
			$sql = $sql . $task->get_part_id() . ", ";
			$sql = $sql . "\"" . $task->get_details() . "\" );\n ";
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

		$sql = $sql . "\n";

		foreach ( $deleted_contracts as $deleted_contract )
		{
			$sql = $sql . "INSERT INTO deleted_contracts ( id, deleted_contract_name, contract_details ) VALUES ( ";
			$sql = $sql . $deleted_contract->get_id() . ", ";
			$sql = $sql . "\"" . $deleted_contract->get_deleted_contract_name() . "\", ";
			$sql = $sql . "\"" . $deleted_contract->get_contract_details() . "\" );\n";
		}


		$save_database_file = fopen( 'database', 'w' );

		fwrite( $save_database_file, $sql );

		fclose( $save_database_file );

		echo "Please make sure to download: <a href=\"database\" style=\"font-size: 20px;\" download> Database</a>, <a href=\"database_loadable\" style=\"font-size: 20px;\" download> Loadable Database</a> and store them. When done, <a href=\"index.php?path=user_controller/view/" . $_SESSION[ 'id' ] . "\" style=\"font-size: 20px;\" > Return</a>.";
	}


	function save_database_loadable ()
	{

		$this->includes();

		$parts = get_parts();
		$users = get_users();
		$tasks = get_tasks();
		$contracts = get_contracts();
		$deleted_contracts = get_deleted_contracts();


		$file = "";



		// user{()}90'username'password'first name'last name'email'client({})user{()}70'username'password'first name'last name'email'client

		// task{()}49'1'0'details

		// contract{()}70'contract_name'details'0'2020-03-04'2020-04-10

		// part{()}940'part_name'0'45

		// deleted_contract{()}90'deleted_contract_name'details'



		foreach ( $parts as $part )
		{
			$file = $file . "part";

			$file = $file . "{()}" . $part->get_id() . "'";
			$file = $file . $part->get_part_name() . "'";
			$file = $file . $part->get_contract_id() . "'";
			$file = $file . $part->get_progress() . "'";

			$file = $file . "({})";
		}


		foreach ( $users as $user )
		{
			$file = $file . "user";

			$file = $file . "{()}" . $user->get_id() . "'";
			$file = $file . $user->get_username() . "'";
			$file = $file . $user->get_password() . "'";
			$file = $file . $user->get_firstname() . "'";
			$file = $file . $user->get_lastname() . "'";
			$file = $file . $user->get_email() . "'";
			$file = $file . $user->get_type() . "'";
	
			$file = $file . "({})";
		}


	


		foreach ( $contracts as $contract )
		{
			$file = $file . "contract";

			$file = $file . "{()}" . $contract->get_id() . "'";
			$file = $file . $contract->get_contract_name() . "'";

			$details = explode( "\n", $contract->get_details() );

			foreach ( $details as $detail )
			{
				if ( $detail == "" or $detail == "\n" )
					continue;

				$file = $file . $detail . "(}";
			}
			$file = $file . "'";

			$file = $file . $contract->get_client_id() . "'";
			$file = $file . $contract->get_contract_date() . "'";
			$file = $file . $contract->get_deadline_date() . "'";

			$file = $file . "({})";
		}


		foreach ( $tasks as $task )
		{
			$file = $file . "task";

			$file = $file . "{()}" . $task->get_id() . "'";
			$file = $file . $task->get_part_id() . "'";
			$file = $file . $task->get_user_id() . "'";


			$details = explode( "\n", $task->get_details() );


			foreach ( $details as $detail )
			{
				if ( $detail == "" )
					continue;

				$file = $file . $detail . "(}";
			}


			$file = $file . "({})";
		}


		foreach ( $deleted_contracts as $deleted_contract )
		{
			$file = $file . "deleted_contract";

			$file = $file . "{()}" . $deleted_contract->get_id() . "'";
			$file = $file . $deleted_contract->get_deleted_contract_name() . "'";


			$details = explode( "\n", $deleted_contract->get_contract_details() );

			foreach ( $details as $detail )
			{
				if ( $detail == "" )
					continue;

				$file = $file . $detail . "(}";
			}

			$file = $file . "\n";
		}



		$save_database_loadable_file = fopen( 'database_loadable', 'w' );

		fwrite( $save_database_loadable_file, $file );

		fclose( $save_database_loadable_file );
	}



	function load_database (  )
	{

		// user{()}90'username'password'first name'last name'email'client({})user{()}70'username'password'first name'last name'email'client

		// task{()}49'1'0'details

		// contract{()}70'contract_name'details'0'2020-03-04'2020-04-10

		// part{()}940'part_name'0'45

		// deleted_contract{()}90'deleted_contract_name'details'

		// $file = $_SESSION[ 'database_file' ];

		$this->includes();





		$file = "";

		$file_loaded = fopen( 'user_loaded', 'r' ); // clears file

		while ( $temp = fgets( $file_loaded ) )
			$file = $file . $temp;

		fclose( $file_loaded );	



		$db = new Database( $this->get_server(), $this->get_user(), $this->get_password(), "application_database" );

		$sql = "";

		$lines = explode( "\n", $file );
		foreach ( $lines as $line )
		{
			$data = explode( '{()}', $line );

			$line_data = explode( "'", $data[ 1 ] );

			if ( $data[ 0 ] == "user" )
			{
				$sql = $sql . "INSERT INTO users ( id, username, password, firstname, lastname, email, type ) VALUES ( ";

				$sql = $sql . $line_data[ 0 ] . ", ";
				$sql = $sql . "\"" . $line_data[ 1 ] . "\", ";
				$sql = $sql . "\"" . $line_data[ 2 ] . "\", ";
				$sql = $sql . "\"" . $line_data[ 3 ] . "\", ";
				$sql = $sql . "\"" . $line_data[ 4 ] . "\", ";
				$sql = $sql . "\"" . $line_data[ 5 ] . "\", ";
				$sql = $sql . "\"" . $line_data[ 6 ] . "\" ";

				$sql = $sql . " ); \n";


				$db->sql( $sql );
				$sql = "";

			}


			if ( $data[ 0 ] == "contract" )
			{
				$sql = $sql . "INSERT INTO contracts ( id, contract_name, details, client_id, contract_date, deadline_date ) VALUES ( ";

				$sql = $sql . $line_data[ 0 ] . ", ";
				$sql = $sql . "\"" . $line_data[ 1 ] . "\", \"";
				$details = explode( '(}', $line_data[ 2 ] );

				foreach ( $details as $detail )
				{
					if ( $detail == "" )
						continue;

					$sql = $sql + $detail . '\n';
				}
				$sql = $sql . "\", " . $line_data[ 3 ] . ", ";
				$sql = $sql . "\"" . $line_data[ 4 ] . "\", ";
				$sql = $sql . "\"" . $line_data[ 5 ] . "\" ";


				$sql = $sql . " ); \n";


				$db->sql( $sql );
				$sql = "";

			}


			if ( $data[ 0 ] == "part" )
			{
				$sql = $sql . "INSERT INTO parts ( id, part_name, contract_id, progress ) VALUES ( ";

				$sql = $sql . $line_data[ 0 ] . ", ";
				$sql = $sql . "\"" . $line_data[ 1 ] . "\", ";
				$sql = $sql . $line_data[ 2 ] . ", ";
				$sql = $sql . $line_data[ 3 ] . " ";

				$sql = $sql . " ); \n";

				$db->sql( $sql );
				$sql = "";

			}


			if ( $data[ 0 ] == "deleted_contract" )
			{
				$sql = $sql . "INSERT INTO deleted_contracts ( id, deleted_contract_name, contract_details ) VALUES ( ";

				$sql = $sql . $line_data[ 0 ] . ", ";
				$sql = $sql . "\"" . $line_data[ 1 ] . "\", \"";

				$details = explode( '(}', $line_data[ 2 ] );

				foreach ( $details as $detail )
				{
					if ( $detail == "" )
						continue;

					$sql = $sql + $detail . '\n';
				}


				$sql = $sql . "\" ); \n";


				$db->sql( $sql );
				$sql = "";

			}

			if ( $data[ 0 ] == "task" )
			{
				$sql = $sql . "INSERT INTO tasks ( id, part_id, user_id, details ) VALUES ( ";

				$sql = $sql . $line_data[ 0 ] . ", ";
				$sql = $sql . $line_data[ 1 ] . ", ";
				$sql = $sql . $line_data[ 2 ] . ", \"";
				$details = explode( '(}', $line_data[ 5 ] );

				foreach ( $details as $detail )
				{
					if ( $detail == "" )
						continue;

					$sql = $sql + $detail . '\n';
				}

				$sql = $sql . "\" ); \n";


				$db->sql( $sql );
				$sql = "";

			}


		}



//		$this->static_alert( $sql );
//		$this->replace_location( "index.php?path=user_controller" );
	}

}