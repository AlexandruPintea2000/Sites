<?php

include_once "Controller.php";
include_once "models/Task.php";
include_once "models/Part.php";
include_once "models/Contract.php";
include_once "models/User.php";

class Part_task
{
	private $status;
	private $part_task;

	function get_status ()
	{
		return $this->status;
	}

	function get_part_task ()
	{
		return $this->part_task;
	}

	function __construct( $status, $part_task )
	{
		$this->status = $status;
		$this->part_task = $part_task;
	}
}

function get_part_tasks_through_task_id ( int $task_id )
{
	$task = get_task_through_id( $task_id );

	$details = $task->get_details();


	$task_parts = explode( '	', $details );

	$i = 0;
	$part_tasks = [];
	for ( $l = 1; $l < count( $task_parts ); $l = $l + 1  )
	{
		$parts_of_task_part = explode( '{)}', $task_parts[ $l ] );

		$part_task = new Part_task( $parts_of_task_part[ 0 ], $parts_of_task_part[ 1 ] );

		$part_tasks[ $i ] = $part_task;
		$i = $i + 1;
	}

	return $part_tasks;
}

function get_part_tasks_through_task_id_and_status ( int $task_id, string $status )
{
	$part_tasks = get_part_tasks_through_task_id( $task_id );

	$remaining_part_tasks = [];
	$i = 0;
	foreach ( $part_tasks as $part_task )
	{
		if ( $part_task->get_status() != $status )
			continue;

		$remaining_part_tasks[ $i ] = $part_task;
		$i = $i + 1;
	}

	return 	$remaining_part_tasks;
}

function get_part_task_through_part_task_name_and_task_id ( string $part_task_name, int $task_id )
{
	$part_tasks = get_part_tasks_through_task_id( $task_id );

	foreach ( $part_tasks as $part_task )
		if ( $part_task->get_part_task() == $part_task_name )
			return $part_task;

	return -1;
}





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

		$part_tasks = get_part_tasks_through_task_id( $id );

    	$data = [
            'title'=> "Task " . $task->get_id(),
            'company'=>$this->get_company(),
            'id'=>$task->get_id(),
            'part_tasks'=>$part_tasks,
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

		if ( $this->signed_in_is_admin() == false ) // make sure that signed in user is an admin
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

//		$this->view( $params[ 0 ] );		
		$this->replace_location( "index.php?path=task_controller/view/" . $params[ 0 ] );
	}

	function view_part_task ( $data )
	{
		if ( $this->signed_in() == false ) // make sure the user is signed in and is not a client
			return;


		$parts = explode( '(@)', $data );

		$task_id = $parts[ 0 ];
		$part_task = $parts[ 1 ];

		$task = get_task_through_id( $task_id );

		$status = "";
		$part_tasks = get_part_tasks_through_task_id( $task_id );

		foreach ( $part_tasks as $part_task )
		{
			if ( $part_task->get_part_task() == $parts[ 1 ] )
			{
				$status = $part_task->get_status();
				break;
			}
		}

		$part_task = $parts[ 1 ];



		$user = get_user_through_id( $task->get_user_id() );
		$part = get_part_through_id( $task->get_part_id() );
		$contract = get_contract_through_id( $part->get_contract_id() );

		$updated_status = "";
		if ( $status == "given" )
			$updated_status = "completed";
		else
			$updated_status = "given";

		$alert_part_task = "";

		$alert_part_task = $alert_part_task . $part_task . "<br> <span style=\"font-size: 14px; font-weight: 100;\" > task ";

		if ( $status == "given" )
			$alert_part_task = $alert_part_task . " given to ";
		else
			$alert_part_task = $alert_part_task . " completed by ";

		$alert_part_task = $alert_part_task . " <a href=\"index.php?path=user_controller/view/" . $user->get_id() . "\" >";
		$alert_part_task = $alert_part_task . $user->get_firstname() . ' ' . $user->get_lastname() . "</a><br> in part: <a href=\"index.php?path=part_controller/view/" . $part->get_id() . "\">" . $part->get_part_name() . "</a> of contract: <a href=\"index.php?path=contract_controller/view/" . $contract->get_id() . "\">\"" . $contract->get_contract_name() . "\"</a> <br>";

		$alert_part_task = $alert_part_task . "<br> <span style=\"font-size: 17px;\"><b>Status:</b> " . $status . " <br> <br>";


		if ( $_SESSION[ 'type' ] == "admin" or $_SESSION[ 'id' ] == $user->get_id() )
			$alert_part_task = $alert_part_task . "<a class=\"add_part_href\" href=\"index.php?path=task_controller/update_part_task/" . $task->get_id() . '(@)' . $part_task . '(@)' . $updated_status . "\" style=\"padding: 2px 10px;\"> Make \"" . ucfirst( $updated_status ) . "\" </a> ";

		if ( $_SESSION[ 'type' ] == "admin" )
			$alert_part_task = $alert_part_task . " / <a class=\"add_part_href\" href=\"index.php?path=task_controller/delete_part_task/" . $task->get_id() . '(@)' . $part_task . "\" style=\"padding: 2px 10px;\"> Delete </a> <br><br> <span style=\"font-size: 14px;\">( if you want to edit, please delete and remake )</span> ";

		$alert_part_task = $alert_part_task . "</span> </span>";

		$this->alert( "<div style=\"max-width: 700px;\"> " . $alert_part_task . " </div>" );
		$this->view( $task_id );
	}

	function add_part_task ( int $task_id )
	{

		if ( $this->signed_in() == false ) // make sure the user is signed in and is not a client
			return;

		if ( $this->signed_in_is_admin() == false ) // make sure that signed in user is an admin
			return;


		$task = get_task_through_id( $task_id );

	   	$data = [
            'company'=>$this->get_company(),
            'task'=>$task
            ];

      	$this->load_view( "task", "add_part_task", $data );     
	}

	function add_user_part_task ( $data = null )
	{
		if ( $this->signed_in() == false ) // make sure the user is signed in and is not a client
			return;

		if ( $this->signed_in_is_admin() == false ) // make sure that signed in user is an admin
			return;


		$parts = explode( '(@)', $data );

		if ( $parts[ 0 ] == "empty" )
			$parts[ 0 ] = 0;

		$task = get_task_through_id( $parts[ 0 ] );

		$part_tasks = get_part_tasks_through_task_id( $parts[ 0 ] );

		foreach ( $part_tasks as $part_task )
		{
			if ( $part_task->get_part_task() == $parts[ 1 ] )
			{
				$this->alert( "Part task name taken, please retry." );
				$this->add_part_task( $parts[ 0 ] );
				return;
			}
		}

		$details = $task->get_details() . '{(}given{)}' . $parts[ 1 ];

		$db = new Database( $this->get_server(), $this->get_user(), $this->get_password(), "application_database" );
		$table = new Table ( "tasks", $db );

		$params = [ $task->get_id(), $task->get_user_id(), $task->get_part_id(), $details ];

		$table->update_row( $params );


		$this->view( $task->get_id() );

//		$this->location_replace( "index.php?path=task_controller/view/" . $task->get_id() );
	}

	function update_part_task( $data )
	{
		if ( $this->signed_in() == false ) // make sure the user is signed in and is not a client
			return;

		$parts = explode( '(@)', $data );

		$task_id = $parts[ 0 ];

		if ( $_SESSION[ 'type' ] != "admin" and $_SESSION[ 'id' ] != $user->get_id() )
		{
			$this->alert( "Please let an admin or the task user alter the status of part tasks." );
			$this->view( $task->get_id( $task_id ) );
			return;
		}


		$task = get_task_through_id( $task_id );
		$part_tasks = get_part_tasks_through_task_id( $task_id );
//		$part_task = get_part_task_through_part_task_name_and_task_id( $parts[ 1 ], $task_id );


		$db = new Database( $this->get_server(), $this->get_user(), $this->get_password(), "application_database" );
		$table = new Table ( "tasks", $db );

		$params = [ $task->get_id(), $task->get_user_id(), $task->get_part_id(), "" ];
		$table->update_row( $params );


		$details = "";

		foreach ( $part_tasks as $part_task )
		{
			if ( $part_task->get_part_task() == $parts[ 1 ] )
				$details = $details . '{(}' . $parts[ 2 ] . '{)}' . $parts[ 1 ];
			else
				$details = $details . '{(}' . $part_task->get_status() . '{)}' . $part_task->get_part_task();
		}


		$params = [ $task->get_id(), $task->get_user_id(), $task->get_part_id(), $details ];
		$table->update_row( $params );


		$this->view( $task_id );
	}


	function delete_part_task( $data )
	{
		if ( $this->signed_in() == false ) // make sure the user is signed in and is not a client
			return;

		$parts = explode( '(@)', $data );

		$task_id = $parts[ 0 ];

		if ( $_SESSION[ 'type' ] != "admin" and $_SESSION[ 'id' ] != $user->get_id() )
		{
			$this->alert( "Please let an admin or the task user alter the status of part tasks." );
			$this->view( $task->get_id( $task_id ) );
			return;
		}


		$task = get_task_through_id( $task_id );
		$part_tasks = get_part_tasks_through_task_id( $task_id );
//		$part_task = get_part_task_through_part_task_name_and_task_id( $parts[ 1 ], $task_id );


		$db = new Database( $this->get_server(), $this->get_user(), $this->get_password(), "application_database" );
		$table = new Table ( "tasks", $db );

		$params = [ $task->get_id(), $task->get_user_id(), $task->get_part_id(), "" ];
		$table->update_row( $params );


		$details = "";

		foreach ( $part_tasks as $part_task )
			if ( $part_task->get_part_task() != $parts[ 1 ] )
				$details = $details . '{(}' . $part_task->get_status() . '{)}' . $part_task->get_part_task();

		$params = [ $task->get_id(), $task->get_user_id(), $task->get_part_id(), $details ];
		$table->update_row( $params );


		$this->view( $task_id );
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

//		$this->view( $params[ 0 ] );
		$this->replace_location( "index.php?path=task_controller/view/" . $params[ 0 ] );
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

		$this->replace_location( "index.php?path=task_controller" );
	}



	// For "Visualizing all Tasks"



	function index ( $order = null )
	{
		if ( $this->signed_in() == false ) // make sure the user is signed in and is not a client
			return;

		$tasks = get_tasks();



		if ( $order == "obsolete" )
		{
			$remaining_tasks = [];
			$i = 0;
			foreach ( $tasks as $task )
			{
				$part = get_part_through_id( $task->get_part_id() );
				if ( contract_obsolete( $part->get_contract_id() ) )
				{
					$remaining_tasks[ $i ] = $task;
					$i = $i + 1;
				}
			}

		   	$data = [
	            'title'=>"Obsolete Tasks",
	            'company'=>$this->get_company(),
	            'tasks'=>$remaining_tasks,
	            'delete'=>"obsolete",
	            'search'=>null
	            ];        	

	    	$this->load_view( "task", "index", $data );	
	    	return;				
		}

		if ( $order == "finalised" )
		{
		$remaining_tasks = [];
			$i = 0;
			foreach ( $tasks as $task )
			{
				$part = get_part_through_id( $task->get_part_id() );
				if ( contract_finalised( $part->get_contract_id() ) )
				{
					$remaining_tasks[ $i ] = $task;
					$i = $i + 1;
				}
			}

		   	$data = [
	            'title'=>"Finalised Tasks",
	            'company'=>$this->get_company(),
	            'tasks'=>$remaining_tasks,
	            'delete'=>"obsolete",
	            'search'=>null
	            ];        	

	    	$this->load_view( "task", "index", $data );	
	    	return;					
		}


		if ( $order == "final_month" )
		{
		$remaining_tasks = [];
			$i = 0;
			foreach ( $tasks as $task )
			{
				$part = get_part_through_id( $task->get_part_id() );
				if ( contract_final_month( $part->get_contract_id() ) )
				{
					$remaining_tasks[ $i ] = $task;
					$i = $i + 1;
				}
			}

		   	$data = [
	            'title'=>"Final Month Tasks",
	            'company'=>$this->get_company(),
	            'tasks'=>$remaining_tasks,
	            'delete'=>"obsolete",
	            'search'=>null
	            ];        	

	    	$this->load_view( "task", "index", $data );	
	    	return;							
		}


		if ( $order == "interesting" )
		{
		$remaining_tasks = [];
			$i = 0;
			foreach ( $tasks as $task )
			{
				$part = get_part_through_id( $task->get_part_id() );
				if ( contract_obsolete( $part->get_contract_id() ) )
					continue;

				if ( contract_finalised( $part->get_contract_id() ) )
					continue;

				$remaining_tasks[ $i ] = $task;
				$i = $i + 1;
			}

		   	$data = [
	            'title'=>"Interesting Tasks",
	            'company'=>$this->get_company(),
	            'tasks'=>$remaining_tasks,
	            'delete'=>"obsolete",
	            'search'=>null
	            ];        	

	    	$this->load_view( "task", "index", $data );	
	    	return;				
		}



		$orders = [];

		if ( $order == null )
		{
			$users = get_users();

			$i = 0;
			$tasks = [];
			foreach ( $users as $user )
			{
				$user_tasks = get_tasks_through_user_id( $user->get_id() );

				if ( count( $user_tasks ) == 0 )
					continue;

				$tasks[ $i ] = new Task( -1, -1, -1, $user->get_firstname() . ' ' . $user->get_lastname() );
				$i = $i + 1;


				foreach ( $user_tasks as $user_task )
				{
					$tasks[ $i ] = $user_task;
					$i = $i + 1;
				}
			}

	    	$data = [
	            'title'=>"Tasks",
	            'company'=>$this->get_company(),
	            'tasks'=>$tasks,
	            'search'=>null
	            ];        	

	    	$this->load_view( "task", "index", $data );		
	    	return;
		}
		else
			$orders = explode( '(@)', $order );


		$tasks = get_tasks();
		$tasks_count = count( $tasks );


		if ( $orders[0] == "completion" )
		{
			for ( $i = 0; $i < $tasks_count - 1; $i = $i + 1 )
				for ( $l = $i + 1; $l < $tasks_count; $l = $l + 1 )
				{
					$part_i = get_part_through_id( $tasks[ $i ]->get_part_id() );
					$part_l = get_part_through_id( $tasks[ $l ]->get_part_id() );

					if ( $orders[1] == "asc" )
					{
						if ( $part_i->get_progress() < $part_l->get_progress() )
						{
						 	$temp = $tasks[ $l ];
						 	$tasks[ $l ] = $tasks[ $i ];
						 	$tasks[ $i ] = $temp;
						}
					}
					else
						if ( $part_i->get_progress() > $part_l->get_progress() )
						{
						 	$temp = $tasks[ $l ];
						 	$tasks[ $l ] = $tasks[ $i ];
						 	$tasks[ $i ] = $temp;
						}
				}

			$remaining_tasks = [];
			$i = 0;
			$part_id = -1;
			foreach ( $tasks as $task )
			{
				if ( $task->get_part_id() != $part_id )
				{
					$part_id = $task->get_part_id();

					$remaining_tasks[ $i ] = new Task( -1, -1, -1, "" );
					$i = $i + 1;
				}

				$remaining_tasks[ $i ] = $task;
				$i = $i + 1;
			}
		}

		if ( $orders[0] == "contract_deadline" )
		{
			for ( $i = 0; $i < $tasks_count - 1; $i = $i + 1 )
				for ( $l = $i + 1; $l < $tasks_count; $l = $l + 1 )
				{
					$part_i = get_part_through_id( $tasks[ $i ]->get_part_id() );
					$part_l = get_part_through_id( $tasks[ $l ]->get_part_id() );

					$contract_i = get_contract_through_id( $part_i->get_contract_id() );
					$contract_l = get_contract_through_id( $part_l->get_contract_id() );

					if ( $orders[1] == "asc" )
					{
						if ( $contract_l->get_deadline_date() > $contract_i->get_deadline_date() )
						{
						 	$temp = $tasks[ $l ];
						 	$tasks[ $l ] = $tasks[ $i ];
						 	$tasks[ $i ] = $temp;
						}
					}
					else
						if ( $contract_l->get_deadline_date() < $contract_i->get_deadline_date() )
						{
						 	$temp = $tasks[ $l ];
						 	$tasks[ $l ] = $tasks[ $i ];
						 	$tasks[ $i ] = $temp;
						}
				}

			$remaining_tasks = [];
			$i = 0;
			$contract_id = -1;
			foreach ( $tasks as $task )
			{
				$part = get_part_through_id( $task->get_part_id() );
				$contract = get_contract_through_id( $part->get_contract_id() );

				if ( $contract->get_id() != $contract_id )
				{
					$contract_id = $contract->get_id();

					$remaining_tasks[ $i ] = new Task( -1, -1, -1, "" );
					$i = $i + 1;
				}

				$remaining_tasks[ $i ] = $task;
				$i = $i + 1;

			}

		}



		if ( $orders[0] == "contract_completion" )
		{
			for ( $i = 0; $i < $tasks_count - 1; $i = $i + 1 )
				for ( $l = $i + 1; $l < $tasks_count; $l = $l + 1 )
				{
					$part_i = get_part_through_id( $tasks[ $i ]->get_part_id() );
					$part_l = get_part_through_id( $tasks[ $l ]->get_part_id() );

					$contract_i = get_contract_through_id( $part_i->get_contract_id() );
					$contract_l = get_contract_through_id( $part_l->get_contract_id() );


					$parts_i = get_parts_through_contract_id( $contract_i->get_id() );
					$parts_l = get_parts_through_contract_id( $contract_l->get_id() );

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
						 	$temp = $tasks[ $l ];
						 	$tasks[ $l ] = $tasks[ $i ];
						 	$tasks[ $i ] = $temp;
						}
					}
					else
						if ( $completed_l < $completed_i )
						{
						 	$temp = $tasks[ $l ];
						 	$tasks[ $l ] = $tasks[ $i ];
						 	$tasks[ $i ] = $temp;
						}
				}

			$remaining_tasks = [];
			$i = 0;
			$contract_id = -1;
			foreach ( $tasks as $task )
			{
				$part = get_part_through_id( $task->get_part_id() );
				$contract = get_contract_through_id( $part->get_contract_id() );

				if ( $contract->get_id() != $contract_id )
				{
					$contract_id = $contract->get_id();

					$remaining_tasks[ $i ] = new Task( -1, -1, -1, "" );
					$i = $i + 1;
				}

				$remaining_tasks[ $i ] = $task;
				$i = $i + 1;
			}

		}



    	$data = [
            'title'=>"Tasks",
            'company'=>$this->get_company(),
            'tasks'=>$remaining_tasks,
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