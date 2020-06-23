<?php

include_once "Controller.php";
include_once "models/Task.php";
include_once "models/Part.php";
include_once "models/Contract.php";
include_once "models/User.php";

class Task_controller extends Controller
{

	// For "Visualize Task"


	function view ( int $id )
	{

		if ( $this->signed_in() == false ) // make sure the user is signed in and is not a client
			return;


		$task = get_task_through_id( $id );

		if ( empty( $task ) )
		{
			$this->unavailable();
		}

		$user = get_user_through_id( $task->get_user_id() );
		$part = get_part_through_id( $task->get_part_id() );

    	$data = [
            'title'=> "Task " . $task->get_id(),
            'company'=>$this->get_company(),
            'id'=>$task->get_id(),
            'user'=>$user,
            'part'=>$part
            ];

    	$this->load_view( "task", "view", $data );
	}



	// For "Create Task"



	function create ( $data = null )
	{
		if ( $this->signed_in() == false ) // make sure the user is signed in and is not a client
			return;

		if ( $this->signed_in_is_admin() == false ) // make sure that the signed in user is an admin
			return;


		$employees = get_users( "employee" );
		$admins = get_users( "admin" );
		$parts = get_parts();
		$contracts = get_contracts();

		$user_id = null;
		$part_id = null;

		if ( $data != null )
		{
			$data_parts = explode( "(@)", $data );

			if ( $data_parts[ 0 ] == "user" )
				$user_id = $data_parts[ 1 ];

			if ( $data_parts[ 0 ] == "part" )
				$part_id = $data_parts[ 1 ];
		}


	   	$data = [
            'company'=>$this->get_company(),
            'task_id'=>get_task_id(),
            'employees'=>$employees,
            'admins'=>$admins,
            'parts'=>$parts,
            'user_id'=>$user_id,
            'part_id'=>$part_id,
            'contracts'=>$contracts
            ];

    	$this->load_view( "task", "create", $data );
	}

	function create_task ( $data )
	{
		if ( $this->signed_in() == false ) // make sure the user is signed in and is not a client
			return;

		if ( $this->signed_in_is_admin() == false ) // make sure that the signed in user is an admin
			return;


		$params = explode( '(@)', $data );

		if ( $params[ 0 ] == "empty" ) 
			$params[ 0 ] = 0;

		if ( $params[ 1 ] == "empty" ) 
			$params[ 1 ] = 0;

		if ( $params[ 2 ] == "empty" ) 
			$params[ 2 ] = 0;

		if ( task_taken( $params[ 1 ], $params[ 2 ] ) )
		{
			$this->alert( "Already taken." );
			$this->create();
			return;
		}


		$db = new Database( $this->get_server(), $this->get_user(), $this->get_password(), "application_database" );
		$table = new Table ( "tasks", $db );

		$table->add_row( $params );

		$this->view( $params[ 0 ] );		
	}



	// For "Edit Task"



	function edit ( int $id )
	{
		if ( $this->signed_in() == false ) // make sure the user is signed in and is not a client
			return;

		if ( $this->signed_in_is_admin() == false ) // make sure that the signed in user is an admin
			return;


		$employees = get_users( "employee" );
		$admins = get_users( "admin" );
		$parts = get_parts();
		$contracts = get_contracts();


		$task = get_task_through_id( $id );

		if ( empty( $task ) )
		{
			$this->unavailable();
		}



	   	$data = [
            'title'=> "Task " . $task->get_id(),
            'company'=>$this->get_company(),
            'employees'=>$employees,
            'admins'=>$admins,
            'id'=>$task->get_id(),
            'parts'=>$parts,
            'user_id'=>$task->get_user_id(),
            'part_id'=>$task->get_part_id(),
            'contracts'=>$contracts
            ];

    	$this->load_view( "task", "edit", $data );
	}

	function edit_task ( $data )
	{
		if ( $this->signed_in() == false ) // make sure the user is signed in and is not a client
			return;

		if ( $this->signed_in_is_admin() == false ) // make sure that the signed in user is an admin
			return;


		$params = explode( '(@)', $data );

		if ( $params[ 0 ] == "empty" ) 
			$params[ 0 ] = 0;

		if ( $params[ 1 ] == "empty" ) 
			$params[ 1 ] = 0;

		if ( $params[ 2 ] == "empty" ) 
			$params[ 2 ] = 0;

		$db = new Database( $this->get_server(), $this->get_user(), $this->get_password(), "application_database" );
		$table = new Table ( "tasks", $db );

		$table->update_row( $params );

		$this->view( $params[ 0 ] );
	}



	// For "Delete Task"



	function delete ( int $id )
	{
		if ( $this->signed_in() == false ) // make sure the user is signed in and is not a client
			return;

		if ( $this->signed_in_is_admin() == false ) // make sure that the signed in user is an admin
			return;


		$task = get_task_through_id( $id );

		if ( empty( $task ) )
		{
			$this->unavailable();
		}

		$user = get_user_through_id( $task->get_user_id() );
		$user_name = $user->get_firstname() . ' ' . $user->get_lastname();
		$part = get_part_through_id( $task->get_part_id() );
		$part_name = $part->get_part_name();

    	$data = [
            'title'=> "Task " . $task->get_id(),
            'company'=>$this->get_company(),
            'id'=>$task->get_id(),
            'user_name'=>$user_name,
            'part_name'=>$part_name
            ];

    	$this->load_view( "task", "delete", $data );
	}

	function delete_task ( $data )
	{
		if ( $this->signed_in() == false ) // make sure the user is signed in and is not a client
			return;

		if ( $this->signed_in_is_admin() == false ) // make sure that the signed in user is an admin
			return;


		$params = explode( '(@)', $data );

		if ( $params[ 0 ] == "empty" ) 
			$params[ 0 ] = 0;

		if ( $params[ 1 ] == "empty" ) 
			$params[ 1 ] = 0;

		if ( $params[ 2 ] == "empty" ) 
			$params[ 2 ] = 0;

		$db = new Database( $this->get_server(), $this->get_user(), $this->get_password(), "application_database" );
		$table = new Table ( "tasks", $db );

		$table->remove_row_through_id( $params[ 0 ] );

		$this->index();
	}



	// For "Visualizing all Tasks"



	function index ()
	{
		if ( $this->signed_in() == false ) // make sure the user is signed in and is not a client
			return;

		$tasks = get_tasks();



		$tasks_count = count( $tasks );


		for ( $i = 0; $i < $tasks_count - 1; $i = $i + 1 )
			for ( $l = $i + 1; $l < $tasks_count; $l = $l + 1 )
			{
				$part_i = get_part_through_id( $tasks[ $i ]->get_part_id() );
				$part_l = get_part_through_id( $tasks[ $l ]->get_part_id() );

				$contract_i = get_contract_through_id( $part_i->get_contract_id() );
				$contract_l = get_contract_through_id( $part_l->get_contract_id() );

				if ( $contract_l->get_deadline_date() > $contract_i->get_deadline_date() )
				{
					$temp = $tasks[ $l ];
					$tasks[ $l ] = $tasks[ $i ];
					$tasks[ $i ] = $temp;
				}
			}


    	$data = [
            'title'=>"Tasks",
            'company'=>$this->get_company(),
            'tasks'=>$tasks,
            'search'=>null
            ];        	

    	$this->load_view( "task", "index", $data );		
	}



	// For "Searching through all Tasks"



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

		$tasks = get_tasks();

    	$data = [
            'title'=>"Search results for \"" . $search . "\"",
            'company'=>$this->get_company(),
            'tasks'=>$tasks,
            'type'=>$type,
            'search'=>$search
            ];        	

    	$this->load_view( "task", "index", $data );		
	}

}