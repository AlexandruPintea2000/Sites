<?php

include_once "Model.php";

class Part extends Table 
{
	private $id;
	private $part_name;
	private $contract_id;
	private $progress;

	public function get_id()
	{
		return $this->id;
	}

	public function get_part_name()
	{
		return $this->part_name;
	}

	public function get_contract_id()
	{
		return $this->contract_id;
	}

	public function get_progress()
	{
		return $this->progress;
	}

	public function __construct ( $id, $part_name, $contract_id, $progress )
	{
		$this->id = $id;
		$this->part_name = $part_name;
		$this->contract_id = $contract_id;
		$this->progress = $progress;
	}

}

function get_parts()
{
	$configure_file = fopen( 'configure', 'r' );
	$strings = fgets( $configure_file );
	fclose( $configure_file );

	$config_file = explode ( ' ', $strings );

	for  ( $i = 0; $i < count( $config_file ); $i = $i + 1 )
		$config_file[ $i ] = remove_separators( $config_file[ $i ] );



	$db = new Database( $config_file[ 0 ], $config_file[ 1 ], $config_file[ 2 ], "application_database" );
	$table = new Table( "parts", $db );

	$table_rows = $table->get_table();
	$count = $table->get_row_count();


	$parts = [];

	$k = 0;
	for ( $i = 0; $i < $count * 4; $i = $i + 4 )
	{
		$temp = new Part( $table_rows[ $i ], $table_rows[ $i + 1 ], $table_rows[ $i + 2 ], $table_rows[ $i + 3 ] );

		$parts[ $k ] = $temp;

		$k = $k + 1;
	}

	return $parts;
}

function get_part_through_id ( int $id )
{
	$parts = get_parts();

	foreach ( $parts as $part )
	{
		if ( $part->get_id() == $id )
		{
			return $part;
		}
	}
}


function get_parts_through_contract_id ( $contract_id )
{
	$configure_file = fopen( 'configure', 'r' );
	$strings = fgets( $configure_file );
	fclose( $configure_file );

	$config_file = explode ( ' ', $strings );

	for  ( $i = 0; $i < count( $config_file ); $i = $i + 1 )
		$config_file[ $i ] = remove_separators( $config_file[ $i ] );



	$db = new Database( $config_file[ 0 ], $config_file[ 1 ], $config_file[ 2 ], "application_database" );
	$table = new Table( "parts", $db );

	$table_rows = $table->get_table();
	$count = $table->get_row_count();


	$parts = [];

	$k = 0;
	for ( $i = 0; $i < $count * 4; $i = $i + 4 )
	{
		if ( $table_rows[ $i + 2 ] != $contract_id )
			continue;

		$temp = new Part( $table_rows[ $i ], $table_rows[ $i + 1 ], $table_rows[ $i + 2 ], $table_rows[ $i + 3 ] );

		$parts[ $k ] = $temp;

		$k = $k + 1;
	}

	return $parts;
}

function get_part_id ()
{
	$parts = get_parts();

	$id = 0;
	foreach ( $parts as $part )
	{
		if ( $part->get_id() == $id )
			$id = $id + 1;
		else
			return $id;
	}

	return $id;
}

function get_part_of_progress ()
{
	$parts = get_parts();

	$progress = -1;
	$part_progress;

	foreach ( $parts as $part )
	{
		if ( $part->get_progress() > $progress )
		{
			$progress = $part->get_progress();
			$part_progress = $part;
		}
	}

	return $part_pregress;
}

