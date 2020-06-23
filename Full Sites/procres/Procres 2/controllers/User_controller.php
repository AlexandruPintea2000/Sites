<?php

include_once "Controller.php";
include_once "models/Task.php";
include_once "models/Part.php";
include_once "models/Contract.php";
include_once "models/User.php";


class User_controller extends Controller
{
	// For "Visualizing User as admin / employee or as client"


	function view ( int $id )
	{
		if ( $this->signed_in() == false ) // make sure the user is signed in and is not a client
			return;

		$user = get_user_through_id( $id );

		if ( empty( $user ) )
		{
			$this->unavailable();
		}

		$users = get_users();
		$parts = get_parts();
		$tasks = get_tasks();
		$contracts = get_contracts();

    	$data = [
            'title'=> $user->get_firstname() . ' ' . $user->get_lastname() . ' (' . $user->get_username() . ')',
            'company'=>$this->get_company(),
            'users'=>$users,
            'contracts'=>$contracts,
            'parts'=>$parts,
            'tasks'=>$tasks,
            'id'=>$user->get_id(),
            'username'=>$user->get_username(),
            'password'=>$user->get_password(),
            'firstname'=>$user->get_firstname(),
            'lastname'=>$user->get_lastname(),
            'email'=>$user->get_email(),
            'type'=>$user->get_type()
            ];

    	$this->load_view( "user", "view", $data );
	}

	function client_view ( int $id )
	{
		if ( $_SESSION[ 'signed_in' ] == false or !isset( $_SESSION[ 'signed_in' ] ) )
		{
			$this->alert( "You are signed out." );

			$this->replace_location( "index.php?path=sign_in_controller/sign_in" );
			return;
		}



		$user = get_user_through_id( $id );

		if ( empty( $user ) )
		{
			$this->unavailable();
		}

		$contracts = get_contracts_through_client_id( $id );
		$parts = [];

		$k = 0;
		foreach ( $contracts as $contract )
		{
			$temp_parts = get_parts_through_contract_id( $contract->get_id() );

			foreach ( $temp_parts as $temp_part )
			{
				$parts[ $k ] = $temp_part;

				$k =  $k + 1;
			}
		}

		$users = get_users();
		$tasks = get_tasks();

    	$data = [
            'title'=> $user->get_firstname() . ' ' . $user->get_lastname() . ' (' . $user->get_username() . ')',
            'users'=> $users,
            'tasks'=> $tasks,
            'contracts'=> $contracts,
            'parts'=> $parts,
            'company'=>$this->get_company(),
            'id'=>$user->get_id(),
            'username'=>$user->get_username(),
            'password'=>$user->get_password(),
            'firstname'=>$user->get_firstname(),
            'lastname'=>$user->get_lastname(),
            'email'=>$user->get_email(),
            'type'=>$user->get_type()
            ];

    	$this->load_view( "user", "client_view", $data );
	}

	function admin_view ()
	{
		if ( $this->signed_in() == false ) // make sure the user is signed in and is not a client
			return;

		if ( $this->signed_in_is_admin() == false ) // make sure that the signed in user is an admin
			return;


		$users = [];

		$i = 0;

		$clients = get_users( "client" );
		$employees = get_users( "employee" );
		$admins = get_users( "admin" );

		$parts = get_parts();
		$tasks = get_tasks();
		$contracts = get_contracts();

    	$data = [
            'company'=>$this->get_company(),
            'clients'=> $clients,
            'employees'=> $employees,
            'admins'=> $admins,
            'parts'=> $parts,
            'tasks'=> $tasks,
            'contracts'=> $contracts
            ];


    	$this->load_view( "user", "admin_view", $data );		
	}


	// For "Create User"



	function create ( $type = null )
	{
		if ( $this->signed_in() == false ) // make sure the user is signed in and is not a client
			return;

		if ( $this->signed_in_is_admin() == false ) // make sure that the signed in user is an admin
			return;


	   	$data = [
            'company'=>$this->get_company(),
            'user_id'=>get_user_id(),
            'type'=>$type
            ];

    	$this->load_view( "user", "create", $data );
	}

	function create_user ( $data )
	{
		if ( $this->signed_in() == false ) // make sure the user is signed in and is not a client
			return;

		if ( $this->signed_in_is_admin() == false ) // make sure that the signed in user is an admin
			return;


		$params = explode( '(@)', $data );

		if ( $params[ 0 ] == "empty" ) 
			$params[ 0 ] = 0;

		$params[ 3 ] = ucfirst( $params[ 3 ] );
		$params[ 4 ] = ucfirst( $params[ 4 ] );


		if ( $params[ 3 ] == "empty" ) 
			$params[ 3 ] = 0;

		if ( username_taken( $params[ 1 ] ) )
		{
			$this->alert( "Username already taken, please retry." );
			$this->create();
			return;
		}

		$db = new Database( $this->get_server(), $this->get_user(), $this->get_password(), "application_database" );
		$table = new Table ( "users", $db );

		$table->add_row( $params );

		$this->view( $params[ 0 ] );		
	}



	// For "Edit User"



	function edit ( int $id )
	{
		if ( $this->signed_in() == false ) // make sure the user is signed in and is not a client
			return;

		if ( $_SESSION[ 'type' ] != "admin" and $_SESSION[ 'id' ] != $id )
		{
			$this->alert( "Please leave this task to admins." );
			$this->replace_location( "index.php?path=user_controller/view/" . $_SESSION[ 'id' ] );
			return;
		}


		$user = get_user_through_id( $id );

		if ( empty( $user ) )
		{
			$this->unavailable();
		}


    	$data = [
            'title'=> $user->get_firstname() . ' ' . $user->get_lastname() . ' (' . $user->get_username() . ')',
            'company'=>$this->get_company(),
            'id'=>$user->get_id(),
            'username'=>$user->get_username(),
            'password'=>$user->get_password(),
            'firstname'=>$user->get_firstname(),
            'lastname'=>$user->get_lastname(),
            'email'=>$user->get_email(),
            'type'=>$user->get_type()
            ];

    	$this->load_view( "user", "edit", $data );
	}

	function edit_user ( $data )
	{
		if ( $this->signed_in() == false ) // make sure the user is signed in and is not a client
			return;


		$params = explode( '(@)', $data );

		if ( $_SESSION[ 'type' ] != "admin" and $_SESSION[ 'id' ] != $params[ 0 ] )
		{
			$this->alert( "Please leave this task to admins." );
			$this->replace_location( "index.php?path=user_controller/view/" . $_SESSION[ 'id' ] );
			return;
		}


		if ( $params[ 0 ] == "empty" ) 
			$params[ 0 ] = 0;

		$db = new Database( $this->get_server(), $this->get_user(), $this->get_password(), "application_database" );
		$table = new Table ( "users", $db );

		$table->update_row( $params );

		$this->view( $params[ 0 ] );
	}



	// For "Delete User"



	function delete ( int $id )
	{
		if ( $this->signed_in() == false ) // make sure the user is signed in and is not a client
			return;

		if ( $this->signed_in_is_admin() == false ) // make sure that the signed in user is an admin
			return;


		$user = get_user_through_id( $id );

		if ( empty( $user ) )
		{
			$this->unavailable();
		}


    	$data = [
            'title'=> $user->get_firstname() . ' ' . $user->get_lastname() . ' (' . $user->get_username() . ')',
            'company'=>$this->get_company(),
            'id'=>$user->get_id(),
            'username'=>$user->get_username(),
            'password'=>$user->get_password(),
            'firstname'=>$user->get_firstname(),
            'lastname'=>$user->get_lastname(),
            'email'=>$user->get_email(),
            'type'=>$user->get_type()
            ];

    	$this->load_view( "user", "delete", $data );
	}

	function delete_user ( $data )
	{
		if ( $this->signed_in() == false ) // make sure the user is signed in and is not a client
			return;

		if ( $this->signed_in_is_admin() == false ) // make sure that signed in user is an admin
			return;


		$params = explode( '(@)', $data );

		if ( $params[ 0 ] == "empty" ) 
			$params[ 0 ] = 0;

		$db = new Database( $this->get_server(), $this->get_user(), $this->get_password(), "application_database" );
		$table = new Table ( "users", $db );

		$admin_ids = $db->get_connection()->query( "SELECT id FROM users WHERE type = \"admin\"" );
		$admin_count = mysqli_num_rows( $admin_ids );

		if ( $admin_count == 1 )
		{
			$this->alert( "User cannot be deleted - it is the only \"admin\" user." );
			$this->view( $params[ 0 ] );
			return;
		}

		$user = get_user_through_id( $params[ 0 ] );
		$table->remove_row_through_id( $user->get_id() );




		if ( $user->get_type() == "client" )
		{
			$contracts = get_contracts();

			$contracts_table = new Table ( "contracts", $db );

			foreach ( $contarcts as $contract )
				if ( $contarct->get_client_id() == $user->get_id() )
					$contracts_table->remove_row_through_id( $contarct->get_id() );
		}
		else
		{
			$tasks_table = new Table ( "tasks", $db );
			$tasks = get_tasks_through_user_id ( $user->get_id() );

			foreach ( $tasks as $task )
				$tasks_table->remove_row_through_id( $task->get_id() );	
		}



		$this->index();
	}



	// For "Visualizing all Users"



	function index ( $type = null )
	{
		if ( $this->signed_in() == false ) // make sure the user is signed in and is not a client
			return;


		if ( $type == null )
			$type = ""; 


		if ( $type != "" )
			$users = get_users( $type );
		else
		{
			$users = [];

			$i = 0;

			$clients = get_users( "client" );
			$employees = get_users( "employee" );
			$admins = get_users( "admin" );

			foreach ( $clients as $client )
			{
				$users[ $i ] = $client;
				$i = $i + 1;
			}

			foreach ( $employees as $employee )
			{
				$users[ $i ] = $employee;
				$i = $i + 1;
			}

			foreach ( $admins as $admin )
			{
				$users[ $i ] = $admin;
				$i = $i + 1;
			}
		}



		if ( $type != "" )
		{
	    	$data = [
	            'title'=>ucfirst( $type ) . "s",
	            'company'=>$this->get_company(),
	            'users'=>$users,
	            'type'=>$type,
	            'search'=>null

	            ];
        }
        else
        {
	    	$data = [
	            'title'=>"Users",
	            'company'=>$this->get_company(),
	            'users'=>$users,
	            'type'=>$type,
	            'search'=>null
	            ];        	
        }

    	$this->load_view( "user", "index", $data );		
	}



	// For "Searching through all Users"



	function search ( $data )
	{
		if ( $this->signed_in() == false ) // make sure the user is signed in and is not a client
			return;

		$params = explode( "(@)", $data );

		$search = $params[ 0 ]; 
		$type = $params[ 1 ];

		if ( $type == "empty" )
			$type = null;

		if ( $search == "empty" )
			$search = null;

		$users = get_users();


		if ( $type != null )
		{
	    	$data = [
	            'title'=>ucfirst( $type ) . " search results for \"" . $search . "\"",
	            'company'=>$this->get_company(),
	            'users'=>$users,
	            'type'=>$type,
	            'search'=>$search
	            ];
        }
        else
        {
	    	$data = [
	            'title'=>"Search results for \"" . $search . "\"",
	            'company'=>$this->get_company(),
	            'users'=>$users,
	            'type'=>$type,
	            'search'=>$search
	            ];        	
        }

    	$this->load_view( "user", "index", $data );		
	}



	// For "Emailing User"



	function email ( int $id )
	{
		if ( $this->signed_in() == false ) // make sure the user is signed in and is not a client
			return;

		if ( $id == -1 )
	    	$data = [
	            'company'=>$this->get_company(),
	            'id'=>-1,
	            'username'=>"",
	            'password'=>"",
	            'firstname'=>"",
	            'lastname'=>"",
	            'email'=>"\"\"",
	            'type'=>""
            ];
        else
        {
			$user = get_user_through_id( $id );

	    	$data = [
	            'company'=>$this->get_company(),
	            'id'=>$user->get_id(),
	            'username'=>$user->get_username(),
	            'password'=>$user->get_password(),
	            'firstname'=>$user->get_firstname(),
	            'lastname'=>$user->get_lastname(),
	            'email'=>$user->get_email(),
	            'type'=>$user->get_type()
	            ];
        }

    	$this->load_view( "user", "email", $data );		
	}


	function email_user ( $data )
	{
		$parts = explode( '(@)', $data );

		$email_address = $parts[ 0 ];
		$email_subject = $parts[ 1 ];

		$sender_details = "";

		if ( isset( $_SESSION[ 'name' ] ) and isset( $_SESSION[ 'email' ] ) )
			$sender_details = "\n\nSent by: " . $_SESSION[ 'name' ] . " ( " . $_SESSION[ 'email' ] . ")\n";

		$email = wordwrap( $parts[ 2 ] . $sender_details, 70 );

		try
		{
			mail( $email_address, $email_subject, $email );
		}
		catch ( Exception $e )
		{
			$this->alert( "Email not sent." );
			return;
		}


		$alert = "Email sent to: " . $email_address;

		if ( ! isset( $_SESSION[ 'id' ] ) )
			$alert = $alert . " ( since password was not provided, an email with your details was sent ) ";

		$this->alert( $alert );

		if ( isset( $_SESSION[ 'id' ] ) )
			$this->view( $_SESSION[ 'id' ] );
		else
	    	$this->load_view( "sign_in", "sign_in", null );
	}

	function email_user_details ( $username )
	{
		if ( $username == null )
		{
			$this->alert( "Please complete your username" );
	    	$this->load_view( "sign_in", "sign_in", null );	
	    	return;			
		}

		$user = get_user_through_username( $username );

		if ( $user == null )
		{
			$this->alert( "Invalid username. Please retry." );
	    	$this->load_view( "sign_in", "sign_in", null );	
	    	return;			
		}

		$email = "Details:\n\n";

		$email = $email . "Username: " . $user->get_username() . ".\n";
		$email = $email . "Password: " . $user->get_password() . ".\n";
		$email = $email . "Firstname: " . $user->get_firstname() . ".\n";
		$email = $email . "Lastname: " . $user->get_lastname() . ".\n";
		$email = $email . "Email: " . $user->get_email() . ".\n";
		$email = $email . "Type: " . $user->get_type() . ".\n";
		$email = $email . "Id: " . $user->get_id() . ".\n";

		$this->email_user( $user->get_email() . "(@)Details(@)" . $email );
	}


	// For "Client Id"


	function make_client_id ()
	{
		$client_id = time() % 4 + time() / 4 % 2;



		$client_id_file = fopen( 'client_id', 'r' );

			$strings = fgets( $client_id_file );

		fclose( $client_id_file );

		if ( empty( $strings ) )
		{
			$client_id_file = fopen( 'client_id', 'w' );
			fclose( $client_id_file );

			return -1;
		}



		$client_id_file = fopen( 'client_id', 'w' );

		fwrite( $client_id_file, $strings . ' ' . $client_id );

		fclose( $client_id_file );



		return $client_id;
	}

	function client_id_corresponds ( string $client_id )
	{
		$client_id_file = fopen( 'client_id', 'r' );

		$strings = fgets( $client_id_file );

		fclose( $client_id_file );

		if ( empty( $strings ) )
		{
			$client_id_file = fopen( 'client_id', 'w' );
			fclose( $client_id_file );

			return -1;
		}



		$file_client_ids = explode( ' ', $strings );

		foreach ( $file_client_ids as $file_client_id )
			if ( $file_client_id == $client_id )
				return true;
		
		return false;
	} 

	function email_client_id()
	{
		if ( $this->signed_in() == false ) // make sure the user is signed in and is not a client
			return;

		if ( $this->signed_in_is_admin() == false ) // make sure that signed in user is an admin
			return;

		$client_id = $this->make_client_id();

		$email = "Welcome to " . $this->get_company() . "!\n\n";
		$email = $email . "Your Client Id: " . $client_id . "\n\n";
		$email = $email . "You will use \"Client Id\" for Sign Up.\n";
		$email = $email . "In order to Sign Up, please visit our website.\n\n";
		$email = $email . "Thank you!";

    	$data = [
            'company'=>$this->get_company(),
            
            'email'=>$email
            ];

    	$this->load_view( "user", "email_client_id", $data );		
	}


	// For "Email Client on Progress"


	function email_client_on_progress ( int $id )
	{
		if ( $this->signed_in() == false ) // make sure the user is signed in and is not a client
			return;


		$client = get_user_through_id( $id );

		$contracts = get_contracts_through_client_id( $id );
		$parts = [];

		$i = 0;
		foreach ( $contracts as $contract )
		{
			$contract_parts = get_parts_through_contract_id( $contract->get_id() );

			if ( count( $contract_parts ) == 0 or empty( $contract_parts ) )
				continue;

			foreach ( $contract_parts as $contract_part )
			{
				$parts[ $i ] = $contract_part;
				$i = $i + 1;
			}
		}

    	$data = [
            'company'=>$this->get_company(),
            
            'contracts'=>$contracts,
            'parts'=>$parts,
            'id'=>$id,
            'username'=>$client->get_username(),
            'password'=>$client->get_password(),
            'firstname'=>$client->get_firstname(),
            'lastname'=>$client->get_lastname(),
            'email'=>$client->get_email(),
            'type'=>$client->get_type()
            ];


    	$this->load_view( "user", "email_client_on_progress", $data );		
	}

}