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


		// Welcome Admin with Part tasks


		// if ( isset(	$_SESSION[ 'admin_welcome' ] ) )
		// {
		// 	if ( $_SESSION[ 'admin_welcome' ] == true )
		// 	{
		// 		$_SESSION[ 'admin_welcome' ] = false;
		// 		$this->part_tasks();
		// 		return;
		// 	}
		// }


		$user = get_user_through_id( $id );

		if ( empty( $user ) )
		{
			$this->unavailable();
		}

		$users = get_users();
		$parts = get_parts();
		$tasks = get_tasks_through_user_id( $id );

		// Orders tasks by Part progress

		$tasks_count = count( $tasks );
		for ( $i = 0; $i < $tasks_count - 1; $i = $i + 1 )
			for ( $l = $i + 1; $l < $tasks_count; $l = $l + 1 )
			{
				$part_i = get_part_through_id( $tasks[ $i ]->get_part_id() );
				$part_l = get_part_through_id( $tasks[ $l ]->get_part_id() );

				if ( $part_i->get_progress() > $part_l->get_progress() )
				{
				 	$temp = $tasks[ $l ];
				 	$tasks[ $l ] = $tasks[ $i ];
				 	$tasks[ $i ] = $temp;
				}
			}

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

		if ( $this->signed_in_is_admin() == false ) // make sure that signed in user is an admin
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

	function part_tasks()
	{
		if ( $this->signed_in() == false ) // make sure the user is signed in and is not a client
			return;

		if ( $this->signed_in_is_admin() == false ) // make sure that signed in user is an admin
			return;


		$alert = "<h3 style=\"font-size: 21px;\"> Part tasks ( Complete ) </h3>";

		include_once "controllers/Task_controller.php";

		$alert = $alert . "<br>";
		$alert = $alert . "<div style=\"text-align: left; line-height: 30px;           max-width: 1000px !important; max-height: 250px !important; border: 3px solid rgb( 123, 123, 123 ); margin: 3px 3px 10px 3px; padding: 0px 10px; border-width: 5px 5px 5px 3px; border-radius: 10px; background-color: rgb( 243, 243, 243 ) !important; overflow: scroll;\">";

		$tasks = get_tasks();


		$alert = $alert . "<b>Users that are not on any parts:</b> ";
		$users = get_users();

		$user_task_before = false;
		foreach ( $users as $user )
		{
			if ( $user->get_type() == "client" )
				continue;

			$user_tasks = false;

			foreach ( $tasks as $task )
				if ( $user->get_id() == $task->get_user_id() )
				{
					$user_tasks = true;
					break;
				}

			if ( $user_tasks == false )
			{	
				if ( $user_task_before != false ) 
					$alert = $alert . " / ";
				else
					$user_task_before = true;

				$alert = $alert . " <a href=\"index.php?path=user_controller/view/" . $user->get_id() . "\">" . $user->get_firstname() . ' ' . $user->get_lastname() . "</a> ";
			}
		}



		$alert = $alert . " <br> ";




		$empty_tasks = [];
		$i = 0;
		foreach ( $tasks as $task )
		{
			$given_part_tasks = get_part_tasks_through_task_id_and_status( $task->get_id(), "given" ); 

			if ( empty( $given_part_tasks ) )
			{
				$empty_tasks[ $i ] = $task;
				$i = $i + 1;
			}
		}



		$empty_tasks = [];
		$i = 0;
		foreach ( $tasks as $task )
		{
			$given_part_tasks = get_part_tasks_through_task_id_and_status( $task->get_id(), "given" ); 

			if ( empty( $given_part_tasks ) )
			{
				$empty_tasks[ $i ] = $task;
				$i = $i + 1;
			}
		}


		$have_empty_tasks = false;
		if ( count( $empty_tasks ) !=  0 )
		{
			foreach ( $empty_tasks as $empty_task )
			{
				$empty_part_task = get_part_through_id( $empty_task->get_part_id() );

				if ( $empty_part_task->get_progress() == 100 )
					continue;

				$contract = get_contract_through_id( $empty_part_task->get_contract_id() );

				// if ( contract_obsolete( $contract->get_id() ) or contract_finalised( $contract->get_id() ) ) // also obsolete contracts
				// 	continue;

				$have_empty_tasks = true;
				break;					
			}
		}



		if ( $have_empty_tasks = true )
		{
			$alert = $alert . "<b>Users that are on parts, but do not have tasks:</b> ( obsolete or finalised contracts not viewed ) <br> ";


			for ( $i = 0; $i < count( $empty_tasks ); $i = $i + 1 )
			{
				$empty_part_task = get_part_through_id( $empty_tasks[ $i ]->get_part_id() );
				if ( $empty_part_task->get_progress() == 100 )
					continue;

				$contract = get_contract_through_id( $empty_part_task->get_contract_id() );

				if ( contract_obsolete( $contract->get_id() ) )
					continue; // obsolete contracts


				$contract_status = "";
				if ( contract_obsolete( $contract->get_id() ) )
					$contract_status = " ( obsolete contract: <a href=\"index.php?path=contract_controller/view/" . $contract->get_id() . "\" style=\"\" >" . $contract->get_contract_name() . "</a> ) ";

				$user = get_user_through_id( $empty_task->get_user_id() );

				$alert = $alert . "<a class=\"href\" href=\"index.php?path=task_controller/add_part_task/" . $empty_tasks[ $i ]->get_id() . "\">" . "Add part task to <b>" . $user->get_firstname() . ' ' . $user->get_lastname() . "</b>, part: <b>" . $empty_part_task->get_part_name() . "</b> of contract: <b>\"" . $contract->get_contract_name() . "\"</b></a>";



				if ( $contract_status != "" )
					$alert = $alert .  " <span style=\"font-size: 10.9px;\">" . $contract_status . "</span> ";


				$alert = $alert . " <br> ";

			}

		}







		$alert = $alert . "<div style=\"height: 10px;\"></div> <b>Given part tasks:</b> ";

		foreach ( $tasks as $task )
		{
			$part = get_part_through_id( $task->get_part_id() );
			$user = get_user_through_id( $task->get_user_id() );
			$contract = get_contract_through_id( $part->get_contract_id() );

			$given_part_tasks = get_part_tasks_through_task_id_and_status( $task->get_id(), "given" );


			if ( count( $given_part_tasks ) != 0 )
			{
				$alert = $alert . "<p style=\"margin: 0px !important;\"> Part tasks given to <a href=\"index.php?path=user_controller/view/" . $user->get_id() . "\">" . $user->get_firstname() . ' ' . $user->get_lastname() . "</a> for part <a href=\"index.php?path=part_controller/view/" . $part->get_id() . "\">" . $part->get_part_name() . "</a> of contract <a href=\"index.php?path=contract_controller/view/" . $contract->get_id() . "\">\"" . $contract->get_contract_name() . "\"</a>: " ;

				foreach ( $given_part_tasks as $given_part_task )
					$alert = $alert . " <a class=\"add_part_href\" style=\"margin: 10px 0px 0px 10px; color: rgb( 90, 90, 90 ); font-size: 14px;\"> " . $given_part_task->get_part_task() . " </a> ";

				$alert = $alert . "</p>";
			}


		}	


		$alert = $alert . "<div style=\"height: 10px;\"></div>";
		$alert = $alert . "<b>Completed part tasks:</b> ";

		foreach ( $tasks as $task )
		{
			$part = get_part_through_id( $task->get_part_id() );
			$user = get_user_through_id( $task->get_user_id() );
			$contract = get_contract_through_id( $part->get_contract_id() );

			$completed_part_tasks = get_part_tasks_through_task_id_and_status( $task->get_id(), "completed" );

			if ( count( $completed_part_tasks ) != 0 )
			{
				$alert = $alert . "<p style=\"margin: 0px !important;\"> Part tasks completed by <a href=\"index.php?path=user_controller/view/" . $user->get_id() . "\">" . $user->get_firstname() . ' ' . $user->get_lastname() . "</a> for part <a href=\"index.php?path=part_controller/view/" . $part->get_id() . "\">" . $part->get_part_name() . "</a> of contract <a href=\"index.php?path=contract_controller/view/" . $contract->get_id() . "\">\"" . $contract->get_contract_name() . "\"</a>: " ;

				foreach ( $completed_part_tasks as $completed_part_task )
					$alert = $alert . " <a class=\"add_part_href\" style=\"margin: 10px 0px 0px 10px; color: rgb( 90, 90, 90 ); font-size: 14px;\"> " . $completed_part_task->get_part_task() . " </a> ";

				$alert = $alert . "</p>";
			}
		}











		$alert = $alert . "</div>";

		$this->alert( $alert );

		$this->view( $_SESSION[ 'id' ] );
	}


	// For "Create User"



	function create ( $data = null )
	{
		if ( $this->signed_in() == false ) // make sure the user is signed in and is not a client
			return;

		if ( $this->signed_in_is_admin() == false ) // make sure that the signed in user is an admin
			return;


		if ( isset( $data[ 'username' ] ) )
		{
	    	$this->load_view( "user", "create", $data );
	    	return;
		}


	   	$data = [
            'company'=>$this->get_company(),
            'user_id'=>get_user_id(),
            'type'=>$data
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


		   	$data = [
	            'company'=>$this->get_company(),
	            'user_id'=>get_user_id(),
	            'username'=>$params[ 1 ],
	            'password'=>$params[ 2 ],
	            'firstname'=>ucfirst( $params[ 3 ] ),
	            'lastname'=>ucfirst( $params[ 4 ] ),
	            'email'=>$params[ 5 ],
	            'type'=>$params[ 6 ]
	            ];


			$this->create( $data );
			return;
		}

		$db = new Database( $this->get_server(), $this->get_user(), $this->get_password(), "application_database" );
		$table = new Table ( "users", $db );

		$table->add_row( $params );

//		$this->view( $params[ 0 ] );		
		$this->replace_location( "index.php?path=user_controller/view/" . $params[ 0 ] );
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

		if ( username_taken( $params[ 1 ] ) and $params[ 1 ] != get_user_through_id( $params[0] )->get_username() )
		{
			$this->alert( "Username taken!" );
			$this->edit( $params[ 0 ] );
			return;
		}


		if ( $params[ 0 ] == "empty" ) 
			$params[ 0 ] = 0;

		$db = new Database( $this->get_server(), $this->get_user(), $this->get_password(), "application_database" );
		$table = new Table ( "users", $db );

		$table->update_row( $params );

		$this->replace_location( "index.php?path=user_controller/view/" . $params[ 0 ] );
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


		$this->replace_location( "index.php?path=user_controller" );
	}



	// For "Visualizing all Users"



	function index ( $type = null )
	{
		if ( $this->signed_in() == false ) // make sure the user is signed in and is not a client
			return;


		if ( $type == null )
			$type = ""; 


		if ( $type != "" )
		{
			if ( $type == "admin_employee" )
			{
				$users = [];

				$i = 0;

				$employees = get_users( "employee" );
				$admins = get_users( "admin" );

				foreach ( $employees as $employee )
				{
					$users[ $i ] = $employee;
					$i = $i + 1;
				}

				$users[ $i ] = new User( -1, "", "", "", "", "", "" );
				$i = $i + 1;

				foreach ( $admins as $admin )
				{
					$users[ $i ] = $admin;
					$i = $i + 1;
				}

				$type = "Admins and Employee";
			}
			else
				$users = get_users( $type );
		}
		else
		{
			$users = [];

			$i = 0;

			$clients = get_users( "client" );
			$employees = get_users( "employee" );
			$admins = get_users( "admin" );

			// Orders Admins by number of tasks

			for ( $i = 0; $i < count( $admins ) - 1; $i = $i + 1 )
				for ( $l = $i + 1; $l < count( $admins ); $l = $l + 1 )
				{
					$tasks_i = get_tasks_through_user_id( $admins[ $i ]->get_id() );
					$tasks_l = get_tasks_through_user_id( $admins[ $l ]->get_id() );

					if ( count( $tasks_i ) > count( $tasks_l ) )
					{
						$temp = $admins[ $i ];
						$admins[ $i ] = $admins[ $l ];
						$admins[ $l ] = $temp;
					}
				}

			// Orders Employees by number of tasks

			for ( $i = 0; $i < count( $employees ) - 1; $i = $i + 1 )
				for ( $l = $i + 1; $l < count( $employees ); $l = $l + 1 )
				{
					$tasks_i = get_tasks_through_user_id( $employees[ $i ]->get_id() );
					$tasks_l = get_tasks_through_user_id( $employees[ $l ]->get_id() );

					if ( count( $tasks_i ) > count( $tasks_l ) )
					{
						$temp = $employees[ $i ];
						$employees[ $i ] = $employees[ $l ];
						$employees[ $l ] = $temp;
					}
				}


			foreach ( $clients as $client )
			{
				$users[ $i ] = $client;
				$i = $i + 1;
			}

			$users[ $i ] = new User( -1, "", "", "", "", "", "" );
			$i = $i + 1;


			foreach ( $employees as $employee )
			{
				$users[ $i ] = $employee;
				$i = $i + 1;
			}

			$users[ $i ] = new User( -1, "", "", "", "", "", "" );
			$i = $i + 1;

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


		if ( $type == null )
			$users = get_users();
		else
		{
			if ( $type == "admin_employee" or $type == "Admins and Employee" )
			{
				$users = [];

				$i = 0;

				$employees = get_users( "employee" );
				$admins = get_users( "admin" );

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

				$type = "Admins and Employee";
			}
			else
				$users = get_users( $type );			
		}


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


	function make_initialization_id ( string $type )
	{
		$initialization_id = ( time() % 10000 + time() / 10 % 100 + time() % 10 + 154230 ) % 10000000;
		for ( $i = 0; $i < time() % 10; $i = $i + 4 )
			$initialization_id = ( $initialization_id + time() % 10000 + 10000000 ) % 10000000;

		if ( $type == "admin" )
			$initialization_id = $initialization_id + 100000000;

		if ( $type == "employee" )
			$initialization_id = $initialization_id + 400000000;

		if ( $type == "client" )
			$initialization_id = $initialization_id + 500000000;


		$initialization_id_file = fopen( 'initialization_id', 'r' );

			$strings = fgets( $initialization_id_file );

		fclose( $initialization_id_file );

		if ( empty( $strings ) )
		{
			$initialization_id_file = fopen( 'initialization_id', 'w' );
			fwrite( $initialization_id_file, $initialization_id );
			fclose( $initialization_id_file );

			return $initialization_id;
		}



		$initialization_id_file = fopen( 'initialization_id', 'w' );

		fwrite( $initialization_id_file, $strings . ' ' . $initialization_id );

		fclose( $initialization_id_file );



		return $initialization_id;
	}

	function email_initialization_id( string $type )
	{
		if ( $this->signed_in() == false ) // make sure the user is signed in and is not a client
			return;

		if ( $this->signed_in_is_admin() == false ) // make sure that signed in user is an admin
			return;

		$initialization_id = $this->make_initialization_id( $type );

		$email = "Welcome to " . $this->get_company() . "!\n\n";
		$email = $email . "Your Initialization Id: " . $initialization_id . "\n\n";
		$email = $email . "You will use \"Initialization Id\" for Sign Up.\n";
		$email = $email . "In order to Sign Up, please visit our website.\n\n";
		$email = $email . "Thank you!";

    	$data = [
            'company'=>$this->get_company(),
            
            'email'=>$email
            ];

    	$this->load_view( "user", "email_initialization_id", $data );		
	}


	// For "Email Client on Progress"


	function email_client_on_progress ( int $id )
	{
		if ( $this->signed_in() == false ) // make sure the user is signed in and is not a client
			return;


		$client = get_user_through_id( $id );

		$contracts = get_contracts_through_initialization_id( $id );
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