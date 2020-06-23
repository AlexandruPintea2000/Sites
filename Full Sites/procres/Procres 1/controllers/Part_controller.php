<?php

include_once "Controller.php";
include_once "models/Part.php";
include_once "models/User.php";
include_once "models/Task.php";
include_once "models/Contract.php";

class Part_controller extends Controller
{

	// For "Vizualize Part"


	function view ( int $id )
	{

		if ( $this->signed_in() == false ) // make sure the user is signed in and is not a client
			return;


		$part = get_part_through_id( $id );

		if ( empty( $part ) )
		{
			$this->unavailable();
		}

		if ( $part->get_progress() > 100 )
		{
			$this->increase_progress( $id . "(@)" . ( - ( $part->get_progress() - 100 ) ) );
		}
		if ( $part->get_progress() < 0 )
		{
			$this->increase_progress( $id . "(@)" . ( - $part->get_progress() )  );
		}


		$contract = get_contract_through_id( $part->get_contract_id() );

		$users = get_users();
		$tasks = get_tasks();
		echo "echo";
    	$data = [
            'part_name'=> $part->get_part_name(),
            'company'=>$this->get_company(),
            'id'=>$part->get_id(),
            'tasks'=>$tasks,
            'users'=>$users,
            'contract_id'=>$part->get_contract_id(),
            'contract'=>$contract,
            'progress'=>$part->get_progress(),
            ];

    	$this->load_view( "part", "view", $data );
	}



	// For "Create Part"



	function create ( $id = null )
	{
		if ( $this->signed_in() == false ) // make sure the user is signed in and is not a client
			return;

		if ( $this->signed_in_is_admin() == false ) // make sure that the signed in user is an admin
			return;

		$contracts = get_contracts();

	   	$data = [
            'company'=>$this->get_company(),
            'part_id'=>get_part_id(),
            'contracts'=>$contracts,
            'contract_id'=>$id
            ];

    	$this->load_view( "part", "create", $data );
	}

	function create_part ( $data )
	{
		if ( $this->signed_in() == false ) // make sure the user is signed in and is not a client
			return;

		if ( $this->signed_in_is_admin() == false ) // make sure that the signed in user is an admin
			return;


		$params = explode( '(@)', $data );

		if ( $params[ 0 ] == "empty" ) 
			$params[ 0 ] = 0;

		$params[ 1 ] = ucfirst( $params[ 1 ] );

		if ( $params[ 2 ] == "empty" ) 
			$params[ 2 ] = 0;

		if ( $params[ 3 ] == "empty" ) 
			$params[ 3 ] = 0;

		$db = new Database( $this->get_server(), $this->get_user(), $this->get_password(), "application_database" );
		$table = new Table ( "parts", $db );

		$table->add_row( $params );

		$this->view( $params[ 0 ] );		
	}



	// For "Increase Progress of Part"




	function increase_progress( $data )
	{
		if ( $this->signed_in() == false ) // make sure the user is signed in and is not a client
			return;


		$parts = explode( '(@)', $data );

		$id = $parts[ 0 ];

		if ( $_SESSION[ 'type' ] != "admin" )
		{
			$tasks = get_tasks();

			$have_task = false;
			foreach ( $tasks as $task )
				if ( $task->get_part_id() == $id and $task->get_user_id() == $_SESSION[ 'id' ] )
				{
					$have_task = true;
					break;
				}

			if ( $have_task == false )
			{
				$this->alert( "Please leave this task to an admin. You are only able to edit your own parts." );

				$this->replace_location( "index.php?path=user_controller/view/" . $_SESSION[ 'id' ] );
				return;
			}
		}

		$progress_increase = $parts[ 1 ];

		$part = get_part_through_id( $id );

		if ( empty( $part ) )
		{
			$this->unavailable();
		}

		$increased_progress = $part->get_progress() + $progress_increase;

		if ( $increased_progress < 0 )
			$increased_progress = 0;

		if ( $increased_progress > 100 )
			$increased_progress = 100;

		$row = [ $part->get_id(), $part->get_part_name(), $part->get_contract_id(), $increased_progress ];

		$db = new Database( $this->get_server(), $this->get_user(), $this->get_password(), "application_database" );
		$table = new Table ( "parts", $db );

		$table->update_row( $row );

		$this->replace_location( "index.php?path=part_controller/view/" . $id );
	}



	// For "Edit Part"




	function edit ( int $id )
	{
		if ( $this->signed_in() == false ) // make sure the user is signed in and is not a client
			return;

		if ( $this->signed_in_is_admin() == false ) // make sure that the signed in user is an admin
			return;


		$contracts = get_contracts();

		$part = get_part_through_id( $id );

		if ( empty( $part ) )
		{
			$this->unavailable();
		}



    	$data = [
            'title'=> $part->get_part_name(),
            'company'=>$this->get_company(),
            'id'=>$part->get_id(),
            'part_name'=>$part->get_part_name(),
            'contract_id'=>$part->get_contract_id(),
            'contracts'=>$contracts,
            'progress'=>$part->get_progress(),
            ];

    	$this->load_view( "part", "edit", $data );
	}

	function edit_part ( $data )
	{
		if ( $this->signed_in() == false ) // make sure the user is signed in and is not a client
			return;

		if ( $this->signed_in_is_admin() == false ) // make sure that the signed in user is an admin
			return;


		$params = explode( '(@)', $data );

		if ( $params[ 0 ] == "empty" ) 
			$params[ 0 ] = 0;

		if ( $params[ 2 ] == "empty" ) 
			$params[ 2 ] = 0;

		if ( $params[ 3 ] == "empty" ) 
			$params[ 3 ] = 0;

		$db = new Database( $this->get_server(), $this->get_user(), $this->get_password(), "application_database" );
		$table = new Table ( "parts", $db );

		$table->update_row( $params );

		$this->view( $params[ 0 ] );
	}



	// For "Delete Part"




	function delete ( int $id )
	{
		if ( $this->signed_in() == false ) // make sure the user is signed in and is not a client
			return;

		if ( $this->signed_in_is_admin() == false ) // make sure that the signed in user is an admin
			return;


		$part = get_part_through_id( $id );

		if ( empty( $part ) )
		{
			$this->unavailable();
		}

		$contract = get_contract_through_id( $part->get_contract_id() );
		$contract_name = $contract->get_contract_name();

    	$data = [
            'title'=> $part->get_part_name(),
            'company'=>$this->get_company(),
            'id'=>$part->get_id(),
            'part_name'=>$part->get_part_name(),
            'contract_name'=>$contract_name,
            'progress'=>$part->get_progress(),
            ];

    	$this->load_view( "part", "delete", $data );
	}

	function delete_part ( $data )
	{
		if ( $this->signed_in() == false ) // make sure the user is signed in and is not a client
			return;

		if ( $this->signed_in_is_admin() == false ) // make sure that the signed in user is an admin
			return;


		$params = explode( '(@)', $data );

		if ( $params[ 0 ] == "empty" ) 
			$params[ 0 ] = 0;

		$db = new Database( $this->get_server(), $this->get_user(), $this->get_password(), "application_database" );
		$table = new Table ( "parts", $db );

		$table->remove_row_through_id( $params[ 0 ] );



		$tasks_table = new Table ( "tasks", $db );
		$tasks = get_tasks_through_part_id ( $params[ 0 ] );

		foreach ( $tasks as $task )
		{
			$tasks_table->remove_row_through_id( $task->get_id() );			
		}


		$this->index();
	}




	// For "Visualizing all Parts"




	function index ()
	{
		if ( $this->signed_in() == false ) // make sure the user is signed in and is not a client
			return;


		$parts = get_parts();

    	$data = [
            'title'=>"Parts",
            'company'=>$this->get_company(),
            'parts'=>$parts,
            'search'=>null
            ];        	

    	$this->load_view( "part", "index", $data );		
	}




	// For "Searching through all Parts"




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

		$parts = get_parts();

    	$data = [
            'title'=>"Search results for \"" . $search . "\"",
            'company'=>$this->get_company(),
            'parts'=>$parts,
            'type'=>$type,
            'search'=>$search
            ];        	

    	$this->load_view( "part", "index", $data );		
	}


}