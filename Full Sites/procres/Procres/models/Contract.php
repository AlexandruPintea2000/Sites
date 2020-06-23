<?php

include_once "Model.php";

class Contract extends Table 
{
	private $id;
	private $contract_name;
	private $details;
	private $client_id;

	private $contract_date;
	private $deadline_date;

	public function get_id()
	{
		return $this->id;
	}

	public function get_contract_name()
	{
		return $this->contract_name;
	}

	public function get_details()
	{
		return $this->details;
	}

	public function get_client_id()
	{
		return $this->client_id;
	}

	public function get_contract_date()
	{
		return $this->contract_date;
	}

	public function get_deadline_date()
	{
		return $this->deadline_date;
	}

	public function __construct ( $id, $contract_name, $details, $client_id, $contract_date, $deadline_date )
	{
		$this->id = $id;
		$this->contract_name = $contract_name;
		$this->details = $details;
		$this->client_id = $client_id;
		$this->contract_date = $contract_date;
		$this->deadline_date = $deadline_date;
	}

}

function get_contracts()
{
	$configure_file = fopen( 'configure', 'r' );
	$strings = fgets( $configure_file );
	fclose( $configure_file );

	$config_file = explode ( ' ', $strings );

	for  ( $i = 0; $i < count( $config_file ); $i = $i + 1 )
		$config_file[ $i ] = remove_separators( $config_file[ $i ] );



	$db = new Database( $config_file[ 0 ], $config_file[ 1 ], $config_file[ 2 ], "application_database" );
	$table = new Table( "contracts", $db );

	$table_rows = $table->get_table();
	$count = $table->get_row_count();


	$contracts = [];

	$k = 0;
	for ( $i = 0; $i < $count * 6; $i = $i + 6 )
	{
		$temp = new Contract( $table_rows[ $i ], $table_rows[ $i + 1 ], $table_rows[ $i + 2 ], $table_rows[ $i + 3 ], $table_rows[ $i + 4 ], $table_rows[ $i + 5 ] );

		$contracts[ $k ] = $temp;

		$k = $k + 1;
	}

	return $contracts;
}

function get_contract_through_id ( $id )
{
	if ( $id == null )
		$id = 0;

	$contracts = get_contracts();

	foreach ( $contracts as $contract )
	{
		if ( $contract->get_id() == $id )
		{
			return $contract;
		}
	}
}


function get_contracts_through_client_id ( int $client_id )
{
	$configure_file = fopen( 'configure', 'r' );
	$strings = fgets( $configure_file );
	fclose( $configure_file );

	$config_file = explode ( ' ', $strings );

	for  ( $i = 0; $i < count( $config_file ); $i = $i + 1 )
		$config_file[ $i ] = remove_separators( $config_file[ $i ] );



	$db = new Database( $config_file[ 0 ], $config_file[ 1 ], $config_file[ 2 ], "application_database" );
	$table = new Table( "contracts", $db );

	$table_rows = $table->get_table();
	$count = $table->get_row_count();


	$contracts = [];

	$k = 0;
	for ( $i = 0; $i < $count * 6; $i = $i + 6 )
	{
		if ( $table_rows[ $i + 3 ] != $client_id )
			continue;

		$temp = new Contract( $table_rows[ $i ], $table_rows[ $i + 1 ], $table_rows[ $i + 2 ], $table_rows[ $i + 3 ], $table_rows[ $i + 4 ], $table_rows[ $i + 5 ] );

		$contracts[ $k ] = $temp;

		$k = $k + 1;
	}

	return $contracts;
}

function get_contract_id ()
{
	$contracts = get_contracts();

	$id = 0;
	foreach ( $contracts as $contract )
	{
		if ( $contract->get_id() == $id )
			$id = $id + 1;
		else
			return $id;
	}

	return $id;
}

function contract_name_taken( string $contract_name )
{
	$contracts = get_contracts();

	foreach ( $contracts as $contract )
	{
		if ( $contract->get_contract_name() == $contract_name )
			return true;
	}

	return false;
}

function get_day ( $date )
{
	$parts = explode( '-', $date );

	return $parts[ 2 ];
}

function get_month ( $date )
{
	$parts = explode( '-', $date );

	return $parts[ 1 ];
}

function get_year ( $date )
{
	$parts = explode( '-', $date );

	return $parts[ 0 ];
}


function contract_final_month ( int $id )
{
	$contract = get_contract_through_id( $id );

	if ( $contract->get_deadline_date() < date( "20y-m-d" ) )
		return false;

	if ( get_month( $contract->get_deadline_date() ) == date( "m" ) and
		 get_year( $contract->get_deadline_date() ) == date( "20y" ) )
		return true;

	return false;
}

function contract_obsolete ( int $id )
{
	$contract = get_contract_through_id( $id );

	if ( $contract->get_deadline_date() < date( "20y-m-d" ) )
		return true;

	return false;
}

function contract_finalised ( int $id )
{
	include_once "Part.php";

	$parts = get_parts_through_contract_id( $id );

	if ( count( $parts ) == 0 )
		return false;

	foreach ( $parts as $part )
		if ( $part->get_progress() != 100 )
			return false;

	return true;
}


// Deleted Contracts


class Deleted_contract extends Table 
{
	private $id;
	private $deleted_contract_name;
	private $contract_details;


	public function get_id()
	{
		return $this->id;
	}

	public function get_deleted_contract_name()
	{
		return $this->deleted_contract_name;
	}

	public function get_contract_details()
	{
		return $this->contract_details;
	}

	public function __construct ( $id, $deleted_contract_name, $contract_details )
	{
		$this->id = $id;
		$this->deleted_contract_name = $deleted_contract_name;
		$this->contract_details = $contract_details;
	}
}

function get_deleted_contracts()
{
	$configure_file = fopen( 'configure', 'r' );
	$strings = fgets( $configure_file );
	fclose( $configure_file );

	$config_file = explode ( ' ', $strings );

	for  ( $i = 0; $i < count( $config_file ); $i = $i + 1 )
		$config_file[ $i ] = remove_separators( $config_file[ $i ] );



	$db = new Database( $config_file[ 0 ], $config_file[ 1 ], $config_file[ 2 ], "application_database" );
	$table = new Table( "deleted_contracts", $db );

	$table_rows = $table->get_table();
	$count = $table->get_row_count();


	$contracts = [];

	$k = 0;
	for ( $i = 0; $i < $count * 3; $i = $i + 3 )
	{
		$temp = new Deleted_contract( $table_rows[ $i ], $table_rows[ $i + 1 ], $table_rows[ $i + 2 ] );

		$contracts[ $k ] = $temp;

		$k = $k + 1;
	}

	return $contracts;
}

function get_deleted_contract_id ()
{
	$deleted_contracts = get_deleted_contracts();

	$id = 0;
	foreach ( $deleted_contracts as $deleted_contract )
	{
		if ( $deleted_contract->get_id() == $id )
			$id = $id + 1;
		else
			return $id;
	}

	return $id;
}