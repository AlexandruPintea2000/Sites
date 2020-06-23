<?php

include_once "Controller.php";
include_once "models/Part.php";
include_once "models/User.php";
include_once "models/Task.php";
include_once "models/Contract.php";


class Contract_controller extends Controller
{
	// For "Visualizing Contract and its Parts or Visualizing All Contracts"


	function view ( int $id )
	{
		if ( $this->signed_in() == false ) // make sure the user is signed in and is not a client
			return;
	
	
		$contract = get_contract_through_id( $id );

		if ( empty( $contract ) )
		{
			$this->unavailable();
		}

		$parts = get_parts();
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


		$contract = get_contract_through_id( $id );

		if ( empty( $contract ) )
		{
			$this->unavailable();
		}


		$parts = get_parts();

    	$data = [
            'title'=> $contract->get_contract_name() . " Parts",
            'company'=>$this->get_company(),
            'id'=> $contract->get_id(),
            'parts'=> $parts
            ];

    	$this->load_view( "contract", "parts", $data );		
	}

	function view_contracts ()
	{
		if ( $this->signed_in() == false ) // make sure the user is signed in and is not a client
			return;

		if ( $this->signed_in_is_admin() == false ) // make sure that the signed in user is an admin
			return;


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



	// For "Create Contract"



	function create ()
	{
		if ( $this->signed_in() == false ) // make sure the user is signed in and is not a client
			return;

		if ( $this->signed_in_is_admin() == false ) // make sure that the signed in user is an admin
			return;


		$clients = get_users( "client" );

	   	$data = [
            'company'=>$this->get_company(),
            'contract_id'=>get_contract_id(),
            'clients'=>$clients
            ];

    	$this->load_view( "contract", "create", $data );
	}

	function create_contract ( $data )
	{
		if ( $this->signed_in() == false ) // make sure the user is signed in and is not a client
			return;

		if ( $this->signed_in_is_admin() == false ) // make sure that the signed in user is an admin
			return;


		$params = explode( '(@)', $data );

		if ( $params[ 0 ] == "empty" ) 
			$params[ 0 ] = 0;

		$params[ 1 ] = ucfirst( $params[ 1 ] );

		if ( $params[ 3 ] == "empty" ) 
			$params[ 3 ] = 0;

		$db = new Database( $this->get_server(), $this->get_user(), $this->get_password(), "application_database" );
		$table = new Table ( "contracts", $db );

		$table->add_row( $params );

		$this->view( $params[ 0 ] );		
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

		if ( $params[ 2 ] == "empty" ) 
			$params[ 2 ] = 0;

		if ( $params[ 3 ] == "empty" ) 
			$params[ 3 ] = 0;

		$db = new Database( $this->get_server(), $this->get_user(), $this->get_password(), "application_database" );
		$table = new Table ( "contracts", $db );

		$table->update_row( $params );

		$this->view( $params[ 0 ] );
	}



	// For "Delete Contarct"



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

		$db = new Database( $this->get_server(), $this->get_user(), $this->get_password(), "application_database" );
		$table = new Table ( "contracts", $db );

		$table->remove_row_through_id( $params[ 0 ] );




		$parts_table = new Table ( "parts", $db );
		$parts = get_parts_through_contract_id( $id );

		foreach ( $parts as $part )
		{
			$tasks_table = new Table ( "tasks", $db );
			$tasks = get_tasks_through_part_id ( $id );

			foreach ( $tasks as $task )
			{
				$tasks_table->remove_row_through_id( $task->get_id() );			
			}

			$parts_table->remove_row_through_id( $part->get_id() );			
		}



		$this->index();
	}



	// For "Visualizing all Contracts"



	function index ()
	{
		if ( $this->signed_in() == false ) // make sure the user is signed in and is not a client
			return;


		$contracts = get_contracts();

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

}