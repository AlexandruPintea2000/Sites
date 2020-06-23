<?php

include_once "Model.php";

class Task extends Table 
{
	private $id;
	private $user_id;
	private $part_id;

	public function get_id()
	{
		return $this->id;
	}

	public function get_user_id()
	{
		return $this->user_id;
	}

	public function get_part_id()
	{
		return $this->part_id;
	}


	public function __construct ( $id, $user_id, $part_id )
	{
		$this->id = $id;
		$this->user_id = $user_id;
		$this->part_id = $part_id;
	}

}

function get_tasks()
{
	$configure_file = fopen( 'configure', 'r' );
	$strings = fgets( $configure_file );
	fclose( $configure_file );

	$config_file = explode ( ' ', $strings );

	for  ( $i = 0; $i < count( $config_file ); $i = $i + 1 )
		$config_file[ $i ] = remove_separators( $config_file[ $i ] );



	$db = new Database( $config_file[ 0 ], $config_file[ 1 ], $config_file[ 2 ], "application_database" );
	$table = new Table( "tasks", $db );

	$table_rows = $table->get_table();
	$count = $table->get_row_count();


	$tasks = [];

	$k = 0;
	for ( $i = 0; $i < $count * 3; $i = $i + 3 )
	{
		$temp = new Task( $table_rows[ $i ], $table_rows[ $i + 1 ], $table_rows[ $i + 2 ] );

		$tasks[ $k ] = $temp;

		$k = $k + 1;
	}

	return $tasks;
}


function get_tasks_through_user_id( int $user_id )
{
	$configure_file = fopen( 'configure', 'r' );
	$strings = fgets( $configure_file );
	fclose( $configure_file );

	$config_file = explode ( ' ', $strings );

	for  ( $i = 0; $i < count( $config_file ); $i = $i + 1 )
		$config_file[ $i ] = remove_separators( $config_file[ $i ] );



	$db = new Database( $config_file[ 0 ], $config_file[ 1 ], $config_file[ 2 ], "application_database" );
	$table = new Table( "tasks", $db );

	$table_rows = $table->get_table();
	$count = $table->get_row_count();


	$tasks = [];

	$k = 0;
	for ( $i = 0; $i < $count * 3; $i = $i + 3 )
	{
		if ( $table_rows[ $i + 1 ] != $user_id )
			continue;

		$temp = new Task( $table_rows[ $i ], $table_rows[ $i + 1 ], $table_rows[ $i + 2 ] );

		$tasks[ $k ] = $temp;

		$k = $k + 1;
	}

	return $tasks;
}

function get_tasks_through_part_id( int $part_id )
{
	$configure_file = fopen( 'configure', 'r' );
	$strings = fgets( $configure_file );
	fclose( $configure_file );

	$config_file = explode ( ' ', $strings );

	for  ( $i = 0; $i < count( $config_file ); $i = $i + 1 )
		$config_file[ $i ] = remove_separators( $config_file[ $i ] );



	$db = new Database( $config_file[ 0 ], $config_file[ 1 ], $config_file[ 2 ], "application_database" );
	$table = new Table( "tasks", $db );

	$table_rows = $table->get_table();
	$count = $table->get_row_count();


	$tasks = [];

	$k = 0;
	for ( $i = 0; $i < $count * 3; $i = $i + 3 )
	{
		if ( $table_rows[ $i + 2 ] != $part_id )
			continue;

		$temp = new Task( $table_rows[ $i ], $table_rows[ $i + 1 ], $table_rows[ $i + 2 ] );

		$tasks[ $k ] = $temp;

		$k = $k + 1;
	}

	return $tasks;
}











function get_task_through_id ( int $id )
{
	$tasks = get_tasks();

	foreach ( $tasks as $task )
	{
		if ( $task->get_id() == $id )
		{
			return $task;
		}
	}
}

function task_taken ( int $user_id, int $part_id )
{
	$tasks = get_tasks();

	foreach ( $tasks as $task )
	{
		if ( $task->get_user_id() != $user_id )
			continue;

		if ( $task->get_part_id() == $part_id )
			return true;
	}

	return false;
}


function get_task_id ()
{
	$tasks = get_tasks();

	$id = 0;
	foreach ( $tasks as $task )
	{
		if ( $task->get_id() == $id )
			$id = $id + 1;
		else
			return $id;
	}

	return $id;
}