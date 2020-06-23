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

		if ( $this->signed_in_is_admin() == false ) // make sure that signed in user is an admin
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


		if ( part_name_taken( $params[ 2 ], $params[ 1 ] ) )
		{
			$this->alert( "Part name taken, please retry." );
			$this->create( $params[ 2 ] );
			return;
		}

		$db = new Database( $this->get_server(), $this->get_user(), $this->get_password(), "application_database" );
		$table = new Table ( "parts", $db );

		$table->add_row( $params );

//		$this->view( $params[ 0 ] );		
		$this->replace_location( "index.php?path=contract_controller/view/" . $params[ 2 ] );
	}



	// For "Increase Progress of Part"




	function increase_progress( $data )
	{
		if ( $this->signed_in() == false ) // make sure the user is signed in and is not a client
			return;


		$parts = explode( '(@)', $data );

		if ( $parts[ 0 ] == "empty" )
			$id = 0;
		else
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



		$contract_was_finalised = contract_finalised( $part->get_contract_id() );
		$part_was_completed = false;
		if ( $part->get_progress() == 100 )
			$part_was_completed = true;



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



		$tasks = get_tasks_through_part_id( $part->get_id() );
		$part_users = [];
		$i = 0;
		foreach ( $tasks as $task )
		{
			$user = get_user_through_id( $task->get_user_id() );

			$part_users[ $i ] = $user;
			$i = $i + 1;
		}



		$contract = get_contract_through_id( $part->get_contract_id() );

		if ( ! $contract_was_finalised )
		if ( $part_was_completed and $increased_progress < 100 )
		{
			$alert = "";

			$alert = $alert . "<h2>Part was complete, but now it's not!</h2> <h3 style=\"font-size: 14px;\">Users: ";

			if ( count( $part_users ) == 0 )
				$alert = $alert . " ( Empty ) ";




			include_once "Task_controller.php";

			for ( $i = 0; $i < count( $part_users ); $i = $i + 1 )
			{
				$part_user = $part_users[ $i ];



				$tasks = get_tasks_through_user_id( $part_user->get_id() );
				for ( $l = 0; $l < count( $tasks ); $l = $l + 1 )
				{
					if ( $tasks[ $l ]->get_part_id() == $id )
					{
						$task = $tasks[ $l ];
						break;
					}
				}
				$given_part_tasks = get_part_tasks_through_task_id_and_status( $task->get_id(), "given" );


				if ( count( $given_part_tasks ) != 0 )
					$alert = $alert . "<br>";
				$alert = $alert . " <a href=\"index.php?path=user_controller/view/" . $part_user->get_id() . "\" >" . $part_user->get_firstname() . ' ' . $part_user->get_lastname() . "</a>";



				if ( count( $given_part_tasks ) == 0 )
					$alert = $alert . ": ( Completed all given tasks ) ";
				else
					$alert = $alert . " given: ";

				foreach ( $given_part_tasks as $given_part_task )
				{
					$alert = $alert . "<a class=\"add_part_href\" href=\"index.php?path=task_controller/view_part_task/" . $task->get_id() . '(@)' . $given_part_task->get_part_task() . "\"> " . $given_part_task->get_part_task() . " </a>";
				}



				if ( $i != count( $part_users ) - 1 )
					$alert = $alert . ',';

				$alert = $alert . "<br>";
			}






			$alert = $alert . "<br> will be shown tasks for this part ( if they have any ). </h3>";

			$this->alert( $alert );
			$this->view( $part->get_id() );
			return;
		}


		if ( ! contract_finalised( $contract->get_id() ) )
		if ( $part_was_completed == false and $increased_progress == 100 )
		{
			$alert = "";

			$alert = $alert . "<h2>Part is now complete! </h2>  <h3 style=\"font-size: 17px; vertical-align: middle;\">  Users: ";

			if ( count( $part_users ) == 0 )
				$alert = $alert . " ( Empty ) ";



			include_once "Task_controller.php";

			for ( $i = 0; $i < count( $part_users ); $i = $i + 1 )
			{
				$part_user = $part_users[ $i ];



				$tasks = get_tasks_through_user_id( $part_user->get_id() );
				for ( $l = 0; $l < count( $tasks ); $l = $l + 1 )
				{
					if ( $tasks[ $l ]->get_part_id() == $id )
					{
						$task = $tasks[ $l ];
						break;
					}
				}
				$given_part_tasks = get_part_tasks_through_task_id_and_status( $task->get_id(), "given" );


				if ( count( $given_part_tasks ) != 0 )
					$alert = $alert . "<br>";
				$alert = $alert . " <a href=\"index.php?path=user_controller/view/" . $part_user->get_id() . "\" >" . $part_user->get_firstname() . ' ' . $part_user->get_lastname() . "</a>";



				if ( count( $given_part_tasks ) == 0 )
					$alert = $alert . ": ( Completed all given tasks ) ";
				else
					$alert = $alert . " given: ";

				foreach ( $given_part_tasks as $given_part_task )
				{
					$alert = $alert . "<a class=\"add_part_href\" href=\"index.php?path=task_controller/view_part_task/" . $task->get_id() . '(@)' . $given_part_task->get_part_task() . "\"> " . $given_part_task->get_part_task() . " </a>";
				}



				if ( $i != count( $part_users ) - 1 )
					$alert = $alert . ',';

				$alert = $alert . "<br>";
			}




			$alert = $alert . "<br> <span style=\"font-size: 21px;\"> will not be shown any tasks for this part. </span> </h3> ( do not complete a part on your own, talk to them ) <br> <br>";

			$this->alert( $alert );
			$this->view( $part->get_id() );
			return;
		}




		$client = get_user_through_id( $contract->get_client_id() );

		$contract = get_contract_through_id( $part->get_contract_id() );
		if ( contract_finalised( $part->get_contract_id() ) and ! $contract_was_finalised )
		{
			$this->alert( "<h2 style=\"font-size: 30px;\">Contract <a href=\"index.php?path=contract_controller/view/" . $contract->get_id() . "\">\"" . $contract->get_contract_name() . "\"</a> was finalised. <br> <span style=\"font-size: 17px;\"> ( contract was for <a href=\"index.php?path=user_controller/view/" . $client->get_id() . "\"> " . $client->get_firstname() . ' '.  $client->get_lastname() . "</a> ) </span> </h2> <h3> If you do not want to finalise the contract,<span style=\"font-size: 21px; color: rgb( 50, 50, 50 );\"> please edit part progress to not be 100%.</span> <br> Or, if you do, make sure to <a href=\"index.php?path=contract_controller/email_admins_finalised/" . $contract->get_id() . "\">Email Admins</a>. </h3> <h4> <span style=\"font-size: 21px; color: rgb( 50, 50, 50 );\">Contract part tasks are not shown to users </span> when a contract is finalised! <br> ( So, make sure to not finalise a contract by yourself. ) <br> <br> If you want the contract to be finalised, <br> you might as well <a href=\"index.php?path=contract_controller/delete/" . $part->get_contract_id() . "\"> Delete It</a>, but only if you are an admin. <br> ( it will also be added to <a href=\"index.php?path=contract_controller/view_deleted_contracts\"> Deleted Contracts</a>, when you delete it, where it will not be editable ) </h4> " );

			$this->view( $part->get_id() );
			return;
		}

		if ( ! contract_finalised( $part->get_contract_id() ) and $contract_was_finalised )
		{
			$this->alert( "<h2 style=\"font-size: 30px;\">Contract <a href=\"index.php?path=contract_controller/view/" . $contract->get_id() . "\">\"" . $contract->get_contract_name() . "\"</a> not finalised anymore. <br> <span style=\"font-size: 17px;\"> ( client: <a href=\"index.php?path=user_controller/view/" . $client->get_id() . "\"> " . $client->get_firstname() . ' '.  $client->get_lastname() . "</a> ) </span></h2> <h3> If you want to have it finalised,<span style=\"font-size: 21px; color: rgb( 50, 50, 50 );\"> please edit part progress to be 100%.</span> </h3> <h4> <span style=\"font-size: 21px; color: rgb( 50, 50, 50 );\">Contract part tasks are not shown to users </span> when a contract is finalised! <br> ( So, make sure to not finalise a contract by yourself. ) " );

			$this->view( $part->get_id() );
			return;
		}


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

//		$this->view( $params[ 0 ] );
		$this->replace_location( "index.php?path=part_controller/view/" . $params[ 0 ] );
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
			$tasks_table->remove_row_through_id( $task->get_id() );			


		$this->replace_location( "index.php?path=part_controller" );
	}




	// For "Visualizing all Parts"




	function index ( $order = null )
	{
		if ( $this->signed_in() == false ) // make sure the user is signed in and is not a client
			return;


		$parts = get_parts();

		$parts_count = count( $parts );


		$orders = [];

		if ( $order == "obsolete" )
		{
			$remaining_parts = [];
			$i = 0;
			foreach ( $parts as $part )
			{
				if ( contract_obsolete( $part->get_contract_id() ) )
				{
					$remaining_parts[ $i ] = $part;
					$i = $i + 1;
				}
			}

		   	$data = [
	            'title'=>"Obsolete Parts",
	            'company'=>$this->get_company(),
	            'parts'=>$remaining_parts,
	            'search'=>null
	            ];        	

	    	$this->load_view( "part", "index", $data );	
	    	return;				
		}

		if ( $order == "finalised" )
		{
			$remaining_parts = [];
			$i = 0;
			foreach ( $parts as $part )
			{
				if ( contract_finalised( $part->get_contract_id() ) )
				{
					$remaining_parts[ $i ] = $part;
					$i = $i + 1;
				}
			}

		   	$data = [
	            'title'=>"Finalised Parts",
	            'company'=>$this->get_company(),
	            'parts'=>$remaining_parts,
	            'search'=>null
	            ];        	

	    	$this->load_view( "part", "index", $data );	
	    	return;				
		}


		if ( $order == "final_month" )
		{
			$remaining_parts = [];
			$i = 0;
			foreach ( $parts as $part )
			{
				if ( contract_final_month( $part->get_contract_id() ) )
				{
					$remaining_parts[ $i ] = $part;
					$i = $i + 1;
				}
			}

		   	$data = [
	            'title'=>"Final Month Parts",
	            'company'=>$this->get_company(),
	            'parts'=>$remaining_parts,
	            'search'=>null
	            ];        	

	    	$this->load_view( "part", "index", $data );	
	    	return;				
		}


		if ( $order == "interesting" )
		{
			$remaining_parts = [];
			$i = 0;
			foreach ( $parts as $part )
			{
				if ( contract_obsolete( $part->get_contract_id() ) )
					continue;

				if ( contract_finalised( $part->get_contract_id() ) )
					continue;

				$remaining_parts[ $i ] = $part;
				$i = $i + 1;
			}

		   	$data = [
	            'title'=>"Interesting Parts",
	            'company'=>$this->get_company(),
	            'parts'=>$remaining_parts,
	            'search'=>null
	            ];        	

	    	$this->load_view( "part", "index", $data );	
	    	return;			
		}


		if ( $order == null )
		{
			for ( $i = 0; $i < $parts_count - 1; $i = $i + 1 )
				for ( $l = $i + 1; $l < $parts_count; $l = $l + 1 )
				{
					if ( get_contract_through_id( $parts[ $l ]->get_contract_id() )->get_deadline_date() > get_contract_through_id( $parts[ $i ]->get_contract_id() )->get_deadline_date() )
					{
						$temp = $parts[ $l ];
						$parts[ $l ] = $parts[ $i ];
						$parts[ $i ] = $temp;
					}
				}

				$remaining_parts = [];
				$i = 0;
				$contract_id = -1;
				foreach ( $parts as $part )
				{
					$contract = get_contract_through_id( $part->get_contract_id() );

					if ( $contract->get_id() != $contract_id )
					{
						$contract_id = $contract->get_id();

						$remaining_parts[ $i ] = new Part( -1, "", -1, -1 );
						$i = $i + 1;
					}

					$remaining_parts[ $i ] = $part;
					$i = $i + 1;
				}


		    	$data = [
		            'title'=>"Parts",
		            'company'=>$this->get_company(),
		            'parts'=>$remaining_parts,
		            'search'=>null
		            ];        	

		    	$this->load_view( "part", "index", $data );
		    	return;
		}
		else
			$orders = explode( '(@)', $order );



		if ( $orders[0] == "completion" )
		{
			for ( $i = 0; $i < $parts_count - 1; $i = $i + 1 )
				for ( $l = $i + 1; $l < $parts_count; $l = $l + 1 )
				{
					if ( $orders[1] == "asc" )
					{
						if ( $parts[ $l ]->get_progress() > $parts[ $i ]->get_progress() )
						{
							$temp = $parts[ $l ];
							$parts[ $l ] = $parts[ $i ];
							$parts[ $i ] = $temp;
						}
					}
					else
						if ( $parts[ $l ]->get_progress() < $parts[ $i ]->get_progress() )
						{
							$temp = $parts[ $l ];
							$parts[ $l ] = $parts[ $i ];
							$parts[ $i ] = $temp;
						}
				}

			$remaining_parts = [];
			$remaining_parts[ 0 ] =	new Part( -1, "", -1, -1 );
			$i = 1;
			foreach ( $parts as $part )
			{
				$remaining_parts[ $i ] = $part;
				$i = $i + 1;
			}		
		}

		if ( $orders[0] == "contract_completion" )
		{
			for ( $i = 0; $i < $parts_count - 1; $i = $i + 1 )
				for ( $l = $i + 1; $l < $parts_count; $l = $l + 1 )
				{
					$contract_i = get_contract_through_id( $parts[ $i ]->get_contract_id() );
					$contract_l = get_contract_through_id( $parts[ $l ]->get_contract_id() );

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
							$temp = $parts[ $l ];
							$parts[ $l ] = $parts[ $i ];
							$parts[ $i ] = $temp;
						}
					}
					else
						if ( $completed_l < $completed_i )
						{
							$temp = $parts[ $l ];
							$parts[ $l ] = $parts[ $i ];
							$parts[ $i ] = $temp;
						}
				}			


			$remaining_parts = [];
			$i = 0;
			$contract_id = -1;
			foreach ( $parts as $part )
			{
				$contract = get_contract_through_id( $part->get_contract_id() );

				if ( $contract->get_id() != $contract_id )
				{
					$contract_id = $contract->get_id();	

					$remaining_parts[ $i ] = new Part( -1, "", -1, -1 );
					$i = $i + 1;			
				}

				$remaining_parts[ $i ] = $part;
				$i = $i + 1;
			}		

		}

		if ( $orders[0] == "part_name" )
		{
			for ( $i = 0; $i < $parts_count - 1; $i = $i + 1 )
				for ( $l = $i + 1; $l < $parts_count; $l = $l + 1 )
				{
					if ( $orders[1] == "asc" )
					{
						if ( $parts[ $l ]->get_part_name() < $parts[ $i ]->get_part_name() )
						{
							$temp = $parts[ $l ];
							$parts[ $l ] = $parts[ $i ];
							$parts[ $i ] = $temp;
						}
					}
					else
						if ( $parts[ $l ]->get_part_name() > $parts[ $i ]->get_part_name() )
						{
							$temp = $parts[ $l ];
							$parts[ $l ] = $parts[ $i ];
							$parts[ $i ] = $temp;
						}
				}			

			$remaining_parts = [];
			$remaining_parts[ 0 ] =	new Part( -1, "", -1, -1 );
			$i = 1;
			foreach ( $parts as $part )
			{
				$remaining_parts[ $i ] = $part;
				$i = $i + 1;
			}		
		}




    	$data = [
            'title'=>"Parts",
            'company'=>$this->get_company(),
            'parts'=>$remaining_parts,
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