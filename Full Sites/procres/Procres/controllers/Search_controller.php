<?php

include_once "Controller.php";
include_once "models/Part.php";
include_once "models/User.php";
include_once "models/Task.php";
include_once "models/Contract.php";


function is_user_result ( User $user, $search_params )
{

	foreach ( $search_params as $search_param )
	{
    	if ( $user->get_id() == $search_param )
    		return true;
    	if ( $user->get_username() == $search_param )
    		return true;
    	if ( $user->get_firstname() == $search_param )
    		return true;
    	if ( $user->get_lastname() == $search_param )
    		return true;
    	if ( $user->get_username() == $search_param )
    		return true;
    	if ( $user->get_email() == $search_param )
    		return true;
    }

    return false;
}

function is_contract_result ( contract $contract, $search_params )
{

	foreach ( $search_params as $search_param )
	{
    	if ( $contract->get_id() == $search_param )
    		return true;
    	if ( $contract->get_contract_name() == $search_param )
    		return true;
    	if ( $contract->get_details() == $search_param )
    		return true;
    	if ( $contract->get_client_id() == $search_param )
    		return true;
    	if ( $contract->get_contract_date() == $search_param )
    		return true;
    	if ( $contract->get_deadline_date() == $search_param )
    		return true;
    }


	$client = get_user_through_id( $contract->get_client_id() );

	if ( is_user_result( $client, $search_params ) )
		return true;   

    return false;
}

function is_part_result ( part $part, $search_params )
{

	foreach ( $search_params as $search_param )
	{
    	if ( $part->get_id() == $search_param )
    		return true;
    	if ( $part->get_part_name() == $search_param )
    		return true;
    	if ( $part->get_contract_id() == $search_param )
    		return true;
    	if ( $part->get_progress() == $search_param )
    		return true;
    }

	$contract = get_contract_through_id( $part->get_contract_id() );

	if ( is_contract_result( $contract, $search_params ) )
		return true;

    return false;
}

function is_task_result ( task $task, $search_params )
{

	foreach ( $search_params as $search_param )
	{
    	if ( $task->get_id() == $search_param )
    		return true;
    	if ( $task->get_part_id() == $search_param )
    		return true;
    	if ( $task->get_user_id() == $search_param )
    		return true;
    	// if ( $task->get_details() == $search_param )
    	// 	return true;
    }


	$user = get_user_through_id( $task->get_user_id() );
	$part = get_part_through_id( $task->get_part_id() );

	if ( is_user_result( $user, $search_params ) )
		return true;
	if ( is_part_result( $part, $search_params ) )
		return true;

    return false;
}

function is_deleted_contract_result ( Deleted_contract $deleted_contract, $search_params )
{
	foreach ( $search_params as $search_param )
	{
    	if ( $deleted_contract->get_id() == $search_param )
    		return true;
    	if ( $deleted_contract->get_deleted_contract_name() == $search_param )
    		return true;
    	if ( $deleted_contract->get_contract_details() == $search_param )
    		return true;
    }

    return false;
}

class Search_controller extends Controller
{

	function search ( $data )
	{
		if ( $this->signed_in() == false ) // make sure the user is signed in and is not a client
			return;




//		$data = $this->lowercase( $data );





		$search_data = explode( '(@)', $data ); 
		$search = "";

		for ( $i = 0; $i < count( $search_data ); $i = $i + 1 )
		{
			$search = $search . $search_data[ $i ];

			if ( ! empty( $search_data[ $i + 1 ] ) )
				$search = $search . ' ';
		}

		if ( $search == "empty" )
			$search = null;





		$users = get_users();
		$tasks = get_tasks();
		$parts = get_parts();
		$contracts = get_contracts();
		$deleted_contracts = get_deleted_contracts();


		$remaining_users = [];
		$i = 0;
		foreach ( $users as $user )
			if ( is_user_result( $user , $search_data ) )
			{
				$remaining_users[ $i ] = $user;
				$i = $i + 1;
			}


		$remaining_tasks = [];
		$i = 0;
		foreach ( $tasks as $task )
			if ( is_task_result( $task , $search_data ) )
			{
				$remaining_tasks[ $i ] = $task;
				$i = $i + 1;
			}


		$remaining_parts = [];
		$i = 0;
		foreach ( $parts as $part )
			if ( is_part_result( $part , $search_data ) )
			{
				$remaining_parts[ $i ] = $part;
				$i = $i + 1;
			}


		$remaining_contracts = [];
		$i = 0;
		foreach ( $contracts as $contract )
			if ( is_contract_result( $contract , $search_data ) )
			{
				$remaining_contracts[ $i ] = $contract;
				$i = $i + 1;
			}


		$remaining_deleted_contracts = [];
		$i = 0;
		foreach ( $deleted_contracts as $deleted_contract )
			if ( is_deleted_contract_result( $deleted_contract , $search_data ) )
			{
				$remaining_deleted_contracts[ $i ] = $deleted_contract;
				$i = $i + 1;
			}


    	$data = [
            'title'=>" results for \"" . $search . "\"",
            'company'=>$this->get_company(),
            'users'=>$remaining_users,
            'tasks'=>$remaining_tasks,
            'parts'=>$remaining_parts,
            'contracts'=>$remaining_contracts,
            'deleted_contracts'=>$remaining_deleted_contracts,
            'search'=>$search
            ];        	

    	$this->load_view( "search", "index", $data );		
	}

}