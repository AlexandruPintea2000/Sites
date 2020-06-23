<?php

include_once "Controller.php";
include_once "models/Part.php";
include_once "models/User.php";
include_once "models/Task.php";
include_once "models/Contract.php";


class Contract_controller extends Controller
{
	// For "Visualizing Contract and its Parts or Visualizing All Contracts or Visualizing Deleted Contracts"


	function view ( int $id )
	{
		if ( $this->signed_in() == false ) // make sure the user is signed in and is not a client
			return;
	
	
		$contract = get_contract_through_id( $id );

		if ( empty( $contract ) )
		{
			$this->unavailable();
		}

		$parts = get_parts_through_contract_id( $id );
		$client = get_user_through_id( $contract->get_client_id() );

		$users = get_users();
		$tasks = get_tasks();

    	$data = [
            'title'=> $contract->get_contract_name(),
            'users'=> $users,
            'tasks'=> $tasks,
            'company'=>$this->get_company(),
            'contract_name'=> $contract->get_contract_name(),
            'contract_date'=> $contract->get_contract_date(),
            'deadline_date'=> $contract->get_deadline_date(),
            'id'=>$contract->get_id(),
            'details'=>$contract->get_details(),
            'parts'=>$parts,
            'client'=>$client
            ];

    	$this->load_view( "contract", "view", $data );
	}

	function parts ( int $id )
	{
		if ( $this->signed_in() == false ) // make sure the user is signed in and is not a client
			return;

		echo "echo";

		$contract = get_contract_through_id( $id );

		if ( empty( $contract ) )
		{
			$this->unavailable();
		}


		$parts = get_parts();

    	$data = [
            'title'=> "\"" . $contract->get_contract_name() . "\" Parts",
            'company'=>$this->get_company(),
            'id'=> $contract->get_id(),
            'contract_name'=> $contract->get_contract_name(),
            'parts'=> $parts
            ];
        // echo "echo";
        $this->load_view( "contract", "parts", $data );		
	}

	function view_contracts ()
	{
		if ( $this->signed_in() == false ) // make sure the user is signed in and is not a client
			return;

		// if ( $this->signed_in_is_admin() == false ) // make sure that signed in user is an admin
		// 	return;


		$parts = get_parts();
		$users = get_users();
		$tasks = get_tasks();
		$contracts = get_contracts();

    	$data = [
            'title'=> "All Contracts",
            'tasks'=>$tasks,
            'users'=>$users,
            'parts'=> $parts,
            'contracts'=>$contracts,
            'company'=>$this->get_company()
            ];

    	$this->load_view( "contract", "view_contracts", $data );		
	}

	function view_deleted_contracts ()
	{
		if ( $this->signed_in() == false ) // make sure the user is signed in and is not a client
			return;

		if ( $this->signed_in_is_admin() == false ) // make sure that signed in user is an admin
			return;


		$deleted_contracts = get_deleted_contracts();

    	$data = [
            'title'=> "Deleted Contracts",
            'deleted_contracts'=>$deleted_contracts,
            'company'=>$this->get_company()
            ];


		$this->load_view( "contract", "view_deleted_contracts", $data );
	}



	// For "Create Contract"



	function create ( $data = null )
	{
		if ( $this->signed_in() == false ) // make sure the user is signed in and is not a client
			return;

		if ( $this->signed_in_is_admin() == false ) // make sure that the signed in user is an admin
			return;


		$clients = get_users( "client" );



		if ( ! empty( $data[ 'contract_name' ] ) )
		{
	    	$this->load_view( "contract", "create", $data );
	    	return;
		}

		$client_id = (int) $data;

	   	$data = [
        'company'=>$this->get_company(),
        'contract_id'=>get_contract_id(),
        'clients'=>$clients,
        'client_id'=>$client_id
        ];

    	$this->load_view( "contract", "create", $data );
	}

	function create_contract ( $data )
	{
		if ( $this->signed_in() == false ) // make sure the user is signed in and is not a client
			return;

		if ( $this->signed_in_is_admin() == false ) // make sure that signed in user is an admin
			return;



		$params = explode( '(@)', $data );

		if ( $params[ 0 ] == "empty" ) 
			$params[ 0 ] = 0;


		// For "  Contract  empty   " == "Contract empty" contract names


		$params[ 1 ] = ucfirst( $params[ 1 ] );

		$contract_name_data = [];
		$params_1 = explode( ' ',  $params[ 1 ] );
		$i = 0;
		foreach ( $params_1 as $param_1 )
		{
			if (  $param_1 == "" )
				continue;

			$contract_name_data[ $i ] = $param_1;
			$i = $i + 1;
		}

		$contract_name = "";
		for ( $i = 0; $i < count( $contract_name_data ); $i = $i + 1 )
		{
			$contract_name = $contract_name . $contract_name_data[ $i ];

			if ( $i != count( $contract_name_data ) - 1 )			
				$contract_name = $contract_name . ' ';
		}



		$params[ 2 ] = "";

		$details_file = fopen( 'details', 'r' ); // clears file

		// $id = $params[ 0 ];
		// $are_in_file = false;

		while ( $temp = fgets( $details_file ) )
		{
			// if ( $temp == $id . "\n" and $are_in_file == false )
			// {
			// 	$are_in_file = true;
			// 	continue;
			// }

			// if ( $temp == $id . "\n" and $are_in_file == true )
			// 	break;	

			// if ( $are_in_file == true )
				$params[ 2 ] = $params[ 2 ] . $temp;
		}

		fclose( $details_file );			

		$details_file = fopen( 'details', 'w' ); // clears file
		fclose( $details_file );			



		if ( $params[ 3 ] == "empty" ) 
			$params[ 3 ] = 0;

		if ( contract_name_taken( $contract_name ) )
		{
			$this->alert( "Contract name: already taken, please retry." );


			$clients = get_users( "client" );
		   	$data = [
            'company'=>$this->get_company(),
            'contract_id'=>get_contract_id(),
            'clients'=>$clients,
            'contract_name'=>$contract_name,
            'details'=>$params[ 2 ],
            'client_id'=>$params[ 3 ],
            'contract_date'=>$params[ 4 ],
            'deadline_date'=>$params[ 5 ]
            ];


			$this->create( $data );
			return;
		}

		$db = new Database( $this->get_server(), $this->get_user(), $this->get_password(), "application_database" );
		$table = new Table ( "contracts", $db );

		$table->add_row( $params );

		foreach ( $params as $param )
			echo "'" . $param . "' ";

//    	$this->view( $params[ 0 ] );
		$this->replace_location( "index.php?path=contract_controller/view/" . $params[ 0 ] );
	}



	// For "Edit Contract"



	function edit ( int $id )
	{
		if ( $this->signed_in() == false ) // make sure the user is signed in and is not a client
			return;

		if ( $this->signed_in_is_admin() == false ) // make sure that the signed in user is an admin
			return;


		$clients = get_users( "client" );

		$contract = get_contract_through_id( $id );

		if ( empty( $contract ) )
		{
			$this->unavailable();
		}


    	$data = [
            'title'=> $contract->get_contract_name(),
            'company'=>$this->get_company(),
            'contract_name'=> $contract->get_contract_name(),
            'contract_date'=> $contract->get_contract_date(),
            'deadline_date'=> $contract->get_deadline_date(),
            'id'=>$contract->get_id(),
            'details'=>$contract->get_details(),
            'client_id'=>$contract->get_client_id(),
            'clients'=>$clients,
            'details'=>$contract->get_details()
            ];

    	$this->load_view( "contract", "edit", $data );
	}

	function edit_contract ( $data )
	{
		if ( $this->signed_in() == false ) // make sure the user is signed in and is not a client
			return;

		if ( $this->signed_in_is_admin() == false ) // make sure that the signed in user is an admin
			return;


		$params = explode( '(@)', $data );

		if ( $params[ 0 ] == "empty" ) 
			$params[ 0 ] = 0;





		$params[ 2 ] = "";

		$details_file = fopen( 'details', 'r' ); // clears file



		// $id = $params[ 0 ];
		// $are_in_file = false;

		while ( $temp = fgets( $details_file ) )
		{
			// if ( $temp == $id . "\n" and $are_in_file == false )
			// {
			// 	$are_in_file = true;
			// 	continue;
			// }

			// if ( $temp == $id . "\n" and $are_in_file == true )
			// 	break;	

			// if ( $are_in_file == true )
				$params[ 2 ] = $params[ 2 ] . $temp;
		}

		fclose( $details_file );			

		$details_file = fopen( 'details', 'w' ); // clears file
		fclose( $details_file );			





		if ( $params[ 3 ] == "empty" ) 
			$params[ 3 ] = 0;

		$db = new Database( $this->get_server(), $this->get_user(), $this->get_password(), "application_database" );
		$table = new Table ( "contracts", $db );

		$table->update_row( $params );

//		$this->view( $params[ 0 ] );
		$this->replace_location( "index.php?path=contract_controller/view/" . $params[ 0 ] );
	}



	// For "Delete Contarct and Add Deleted Contract"



	function delete ( int $id )
	{
		if ( $this->signed_in() == false ) // make sure the user is signed in and is not a client
			return;

		if ( $this->signed_in_is_admin() == false ) // make sure that the signed in user is an admin
			return;


		$contract = get_contract_through_id( $id );

		if ( empty( $contract ) )
		{
			$this->unavailable();
		}


		$client = get_user_through_id( $contract->get_client_id() );
		$client_name = $client->get_firstname() . ' ' . $client->get_lastname();


    	$data = [
            'title'=> $contract->get_contract_name(),
            'company'=>$this->get_company(),
            'contract_name'=> $contract->get_contract_name(),
            'contract_date'=> $contract->get_contract_date(),
            'deadline_date'=> $contract->get_deadline_date(),
            'id'=>$contract->get_id(),
            'details'=>$contract->get_details(),
            'client_name'=>$client_name,
            'details'=>$contract->get_details()
            ];

    	$this->load_view( "contract", "delete", $data );
	}

	function delete_contract ( $data )
	{
		if ( $this->signed_in() == false ) // make sure the user is signed in and is not a client
			return;

		if ( $this->signed_in_is_admin() == false ) // make sure that the signed in user is an admin
			return;

		$params = explode( '(@)', $data );

		if ( $params[ 0 ] == "empty" ) 
			$params[ 0 ] = 0;



		$this->add_deleted_contract( $params[ 0 ] );



		$db = new Database( $this->get_server(), $this->get_user(), $this->get_password(), "application_database" );
		$table = new Table ( "contracts", $db );

		$table->remove_row_through_id( $params[ 0 ] );




		$parts_table = new Table ( "parts", $db );
		$parts = get_parts_through_contract_id( $params[ 0 ] );

		foreach ( $parts as $part )
		{
			$tasks_table = new Table ( "tasks", $db );
			$tasks = get_tasks_through_part_id ( $part->get_id() );

			foreach ( $tasks as $task )
			{
				$tasks_table->remove_row_through_id( $task->get_id() );			
			}

			$parts_table->remove_row_through_id( $part->get_id() );			
		}



		$this->replace_location( "index.php?path=contract_controller" );
	}


	function delete_obsolete_finalised ( $obsolete_or_finalised )
	{
		$contracts = get_contracts();

		$db = new Database( $this->get_server(), $this->get_user(), $this->get_password(), "application_database" );
		$contracts_table = new Table ( "contracts", $db );
		$parts_table = new Table ( "parts", $db );
		$tasks_table = new Table ( "tasks", $db );


		foreach( $contracts as $contract )
		{

			if ( $obsolete_or_finalised == "obsolete" )
			{
				if ( contract_obsolete( $contract->get_id() ) )
						$contracts_table->remove_row_through_id( $contract->get_id() );
				else
					continue;
			}

			if ( $obsolete_or_finalised == "finalised" )
			{
				if ( contract_finalised( $contract->get_id() ) )
						$contracts_table->remove_row_through_id( $contract->get_id() );
				else
					continue;
			}

			$parts = get_parts_through_contract_id( $contract->get_id() );

			foreach ( $parts as $part )
			{
					$parts_table->remove_row_through_id( $part->get_id() );

					$tasks = get_tasks_through_part_id( $part->get_id() );

					foreach ( $tasks as $task )
 					$tasks_table->remove_row_through_id( $task->get_id() );
			}

		}


		$this->alert( "All \"" . ucfirst( $obsolete_or_finalised ) . "\" Contracts were deleted." );

//    	$this->load_view( "contract", "index", null );
//		$this->replace_location( "index.php?path=contract_controller" );
		$this->index();
	}


	function add_month_to_deadline ( int $id )
	{
		$contract = get_contract_through_id( $id );

		if ( $contract->get_deadline_date() < date( "20y-m-d" ) )
		{
			$dates = explode( '-', $contract->get_deadline_date() );


			if ( date( "m" ) > 11 )
				$date = ( date( 'y' ) + 1 ) . '-1-' . date( "d" );
			else
				$date = date( 'y' ) . '-' . ( date( "m" ) + 1 ) . '-' . date( "d" );

			$params = [ $contract->get_id(), $contract->get_contract_name(), $contract->get_details(), $contract->get_client_id(), $contract->get_contract_date(), date( $date )  ];


			$db = new Database( $this->get_server(), $this->get_user(), $this->get_password(), "application_database" );
			$table = new Table ( "contracts", $db );

			$table->update_row( $params );

			$this->alert( "Contract deadline updated." );

			$this->view( $params[ 0 ] );
		}
		else
		{
			$this->alert( "Contract is not Obsolete!" );
			$this->view( $params[ 0 ] );			
		}
	}



	// Deleted Contracts



	function add_deleted_contract ( $id )
	{
		if ( $id == null )
			$id = 0;

		$contract = get_contract_through_id( $id );

		$file = $contract->get_contract_name() . "\n\n\n";

		if ( ! empty( $contract->get_details() ) )
			$file = $file . "Details:\n" . $contract->get_details() . "\n\n";

		$client = get_user_through_id( $contract->get_client_id() );
		$file = $file . "Client: " .  $client->get_firstname() . " " . $client->get_lastname() . "\n\n";
		
		$file = $file . "Contract Date: " . $contract->get_contract_date() . "\n";
		$file = $file . "Deadline Date: " . $contract->get_deadline_date() . "\n\n";


		$parts = get_parts_through_contract_id( $id );
		$tasks = get_tasks();

		if ( $parts != null )
		{
			$file = $file . "Parts of contract:\n\n";

			foreach ( $parts as $part )
			{
				$file = $file . $part->get_part_name() . ": " . $part->get_progress() . "% / 100%";

				$have_users = false;
				foreach ( $tasks as $task )
					if ( $task->get_part_id() == $part->get_id() )
					{
						$user = get_user_through_id( $task->get_user_id() );

						if ( $have_users == false )
						{
							$have_users = true;
							$file = $file . " - Responsables:\n";
						}

						$file = $file . $user->get_firstname() . " " . $user->get_lastname();



						include_once "controllers/Task_controller.php";

						$given_part_tasks = get_part_tasks_through_task_id_and_status( $task->get_id(), "given" );
						$completed_part_tasks = get_part_tasks_through_task_id_and_status( $task->get_id(), "completed" );

						$have_part_tasks = false;
						if ( count( $given_part_tasks ) != 0 or count( $completed_part_tasks ) != 0 )
						{
							$file = $file .  ":\n";
							$have_part_tasks = true;
						}

						if ( count( $given_part_tasks ) != 0 )
							$file = $file .  "  Given tasks for \"" . $part->get_part_name() . "\":\n";

						foreach ( $given_part_tasks as $given_part_task )
							$file = $file .  "    " . $given_part_task->get_part_task() . '\n';


						if ( count( $completed_part_tasks ) != 0 )
							$file = $file .  "  Completed tasks for \"" . $part->get_part_name() . "\":\n";

						foreach ( $completed_part_tasks as $completed_part_task )
							$file = $file .  "    " . $completed_part_task->get_part_task() . '\n';


						$file = $file . "\n";
					}

				$file = $file . "\n";
			}

		}

		$db = new Database( $this->get_server(), $this->get_user(), $this->get_password(), "application_database" );
		$table = new Table ( "deleted_contracts", $db );
		
		$deleted_contract_name = "\"" . $contract->get_contract_name() . "\" with: " . $client->get_firstname() . " " . $client->get_lastname(); 
		$params = [ get_deleted_contract_id(), $deleted_contract_name, $file ];

		$table->add_row( $params );
	}


	// For "Visualizing all Contracts"



	function index ( $order = null )
	{
		if ( $this->signed_in() == false ) // make sure the user is signed in and is not a client
			return;


		$contracts = get_contracts();

		$contracts_count = count( $contracts );


		$orders = [];

		if ( $order == "obsolete" )
		{
			$remaining_contracts = [];
			$i = 0;
			foreach ( $contracts as $contract )
			{
				if ( contract_obsolete( $contract->get_id() ) )
				{
					$remaining_contracts[ $i ] = $contract;
					$i = $i + 1;
				}
			}

		   	$data = [
	            'title'=>"Obsolete Contracts",
	            'company'=>$this->get_company(),
	            'contracts'=>$remaining_contracts,
	            'delete'=>"obsolete",
	            'search'=>null
	            ];        	

	    	$this->load_view( "contract", "index", $data );	
	    	return;				
		}

		if ( $order == "finalised" )
		{
			$remaining_contracts = [];
			$i = 0;
			foreach ( $contracts as $contract )
			{
				if ( contract_finalised( $contract->get_id() ) )
				{
					$remaining_contracts[ $i ] = $contract;
					$i = $i + 1;
				}
			}

		   	$data = [
	            'title'=>"Finalised Contracts",
	            'company'=>$this->get_company(),
	            'contracts'=>$remaining_contracts,
	            'delete'=>"finalised",
	            'search'=>null
	            ];        	

	    	$this->load_view( "contract", "index", $data );	
	    	return;				
		}


		if ( $order == "final_month" )
		{
			$remaining_contracts = [];
			$i = 0;
			foreach ( $contracts as $contract )
			{
				if ( contract_final_month( $contract->get_id() ) )
				{
					$remaining_contracts[ $i ] = $contract;
					$i = $i + 1;
				}
			}

		   	$data = [
	            'title'=>"Final Month Contracts",
	            'company'=>$this->get_company(),
	            'contracts'=>$remaining_contracts,
	            'search'=>null
	            ];        	

	    	$this->load_view( "contract", "index", $data );	
	    	return;				
		}


		if ( $order == "interesting" )
		{
			$remaining_contracts = [];
			$i = 0;
			foreach ( $contracts as $contract )
			{
				if ( contract_obsolete( $contract->get_id() ) )
					continue;

				if ( contract_finalised( $contract->get_id() ) )
					continue;

				$remaining_contracts[ $i ] = $contract;
				$i = $i + 1;
			}

		   	$data = [
	            'title'=>"Interesting Contracts",
	            'company'=>$this->get_company(),
	            'contracts'=>$remaining_contracts,
	            'search'=>null
	            ];        	

	    	$this->load_view( "contract", "index", $data );	
	    	return;				
		}


		if ( $order == null )
		{
			for ( $i = 0; $i < $contracts_count - 1; $i = $i + 1 )
				for ( $l = $i + 1; $l < $contracts_count; $l = $l + 1 )
				{
					if ( $contracts[ $l ]->get_deadline_date() > $contracts[ $i ]->get_deadline_date() )
					{
						$temp = $contracts[ $l ];
						$contracts[ $l ] = $contracts[ $i ];
						$contracts[ $i ] = $temp;
					}
				}

			   	$data = [
		            'title'=>"Contracts",
		            'company'=>$this->get_company(),
		            'contracts'=>$contracts,
		            'search'=>null
		            ];        	

	    	$this->load_view( "contract", "index", $data );	
	    	return;	
		}
		else
			$orders = explode( '(@)', $order );





		if ( $orders[0] == "deadline" )
		{
			for ( $i = 0; $i < $contracts_count - 1; $i = $i + 1 )
				for ( $l = $i + 1; $l < $contracts_count; $l = $l + 1 )
				{
					if ( $orders[1] == "asc" )
					{
						if ( $contracts[ $l ]->get_deadline_date() > $contracts[ $i ]->get_deadline_date() )
						{
							$temp = $contracts[ $l ];
							$contracts[ $l ] = $contracts[ $i ];
							$contracts[ $i ] = $temp;
						}
					}
					else
						if ( $contracts[ $l ]->get_deadline_date() < $contracts[ $i ]->get_deadline_date() )
						{
							$temp = $contracts[ $l ];
							$contracts[ $l ] = $contracts[ $i ];
							$contracts[ $i ] = $temp;
						}
				}
		}
		

		if ( $orders[0] == "completion" )		
		{
			for ( $i = 0; $i < $contracts_count - 1; $i = $i + 1 )
				for ( $l = $i + 1; $l < $contracts_count; $l = $l + 1 )
				{
					$parts_i = get_parts_through_contract_id( $contracts[ $i ]->get_id() );
					$parts_l = get_parts_through_contract_id( $contracts[ $l ]->get_id() );

					$completed_i = 0;
					if ( count( $parts_i ) != 0 )
					{
						foreach ( $parts_i as $part )
							$completed_i = $completed_i + $part->get_progress();

						$completed_i = $completed_i / count( $parts_i );
					}

					$completed_l = 0;
					if ( count( $parts_l ) != 0 )
					{
						foreach ( $parts_l as $part )
							$completed_l = $completed_l + $part->get_progress();

						$completed_l = $completed_l / count( $parts_l );
					}

					if ( $orders[1] == "asc" )
					{
						if ( $completed_l > $completed_i )
						{
							$temp = $contracts[ $l ];
							$contracts[ $l ] = $contracts[ $i ];
							$contracts[ $i ] = $temp;
						}
					}
					else
						if ( $completed_l < $completed_i )
						{
							$temp = $contracts[ $l ];
							$contracts[ $l ] = $contracts[ $i ];
							$contracts[ $i ] = $temp;
						}
				}			
		}

		if ( $orders[0] == "contract_name" )
		{
			for ( $i = 0; $i < $contracts_count - 1; $i = $i + 1 )
				for ( $l = $i + 1; $l < $contracts_count; $l = $l + 1 )
				{
					if ( $orders[1] == "asc" )
					{
						if ( $contracts[ $l ]->get_contract_name() < $contracts[ $i ]->get_contract_name() )
						{
							$temp = $contracts[ $l ];
							$contracts[ $l ] = $contracts[ $i ];
							$contracts[ $i ] = $temp;
						}
					}
					else
						if ( $contracts[ $l ]->get_contract_name() > $contracts[ $i ]->get_contract_name() )
						{
							$temp = $contracts[ $l ];
							$contracts[ $l ] = $contracts[ $i ];
							$contracts[ $i ] = $temp;
						}
				}
		}		

		if ( $orders[0] == "contract_date" )
		{
			for ( $i = 0; $i < $contracts_count - 1; $i = $i + 1 )
				for ( $l = $i + 1; $l < $contracts_count; $l = $l + 1 )
				{
					if ( $orders[1] == "asc" )
					{
						if ( $contracts[ $l ]->get_contract_date() > $contracts[ $i ]->get_contract_date() )
						{
							$temp = $contracts[ $l ];
							$contracts[ $l ] = $contracts[ $i ];
							$contracts[ $i ] = $temp;
						}
					}
					else
						if ( $contracts[ $l ]->get_contract_date() < $contracts[ $i ]->get_contract_date() )
						{
							$temp = $contracts[ $l ];
							$contracts[ $l ] = $contracts[ $i ];
							$contracts[ $i ] = $temp;
						}
				}
		}		


	   	$data = [
            'title'=>"Contracts",
            'company'=>$this->get_company(),
            'contracts'=>$contracts,
            'search'=>null
            ];        	

    	$this->load_view( "contract", "index", $data );		
	}



	// For "Searching through all Contracts"



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

		$contracts = get_contracts();

    	$data = [
            'title'=>"Search results for \"" . $search . "\"",
            'company'=>$this->get_company(),
            'contracts'=>$contracts,
            'type'=>$type,
            'search'=>$search
            ];        	

    	$this->load_view( "contract", "index", $data );		
	}



	function email_admins_finalised ( int $id )
	{
		if ( $this->signed_in() == false ) // make sure the user is signed in and is not a client
			return;

		$contract = get_contract_through_id( $id );

		$subject = "Contract :\"" . $contract->get_contract_name() . "\" was finalised!";

		$email = "Please make sure to delete this contract, in order to add it to \"Deleted Contracts\", if you consider so.";

		$admins = get_users( "admin" );

		foreach ( $admins as $admin )
			mail( $admin->get_email(), $subject, $email );


//		$this->alert( "Admins were emailed on finalization." );
		$this->replace_location( "index.php?path=contract_controller/view/" . $id );
	}

}