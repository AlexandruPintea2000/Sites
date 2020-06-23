<?php

require "model.php";

function remove_quotes ( $a )
{
	$result = "";

	$i = 0;

	$once = true;
	while ( true )
	{
		if ( $a[ $i ] == '\'' and $once == false )
			break;

		if ( $a[ $i ] == '\'' and $once == true )
		{
			$once = false;
			$i = $i + 1;
			continue;
		}


		$result = $result . $a[ $i ];

		$i = $i + 1;
	}

	return $result;
}

function credentials ()
{
	$credentials = fopen("credentials.txt", "r");

	$credentials_strings = array();

	for ( $i = 0; $i < 4; $i = $i + 1 )
		$credentials_strings[ $i ] = remove_quotes( fgets( $credentials ) );

	fclose( $credentials );

	return $credentials_strings;
}

function check_credentials ()
{
	$credentials = fopen("credentials.txt", "r");

	$check = fgets( $credentials );

	fclose( $credentials );

	if ( $check == "" )
		return false;

	return true;
}

function table_name ()
{
	$table = fopen("table_name.txt", "r");

	$table_string = fgets( $table );

	fclose( $table );

	return $table_string;
}

function check_table_name ()
{
	$table = fopen("table_name.txt", "r");

	$check = fgets( $table );

	fclose( $table );

	if ( $check == "" )
		return false;

	return true;
}

function set_table_name ()
{

	$table_name = $_POST[ "table_name" ];

	$table = fopen("table_name.txt", "w");
	fclose( $table );

	$table = fopen("table_name.txt", "w");
	fwrite( $table, $table_name );
	fclose( $table );
}










function add_value ()
{
	$credentials = credentials();
	$table_name = table_name();

	$db = new Database( $credentials[ 0 ], $credentials[ 1 ], $credentials[ 2 ], $credentials[ 3 ] );
	$my_table = new Table ( $table_name, $db );


	$values_arr = array();

	echo "<p id=\"added\">You added: ";

	$counter = 0;
	foreach ( $_POST as $i )
	{
		if ( $i != "Submit" )
		{
			echo '"' . $i . "\", ";
			$values_arr[ $counter ] = $i;
			$counter = $counter + 1;
		}
		else
			echo " to the model.</p> ";
	}

	$my_table->add_row( $values_arr );
}

function update_value ()
{
	$credentials = credentials();
	$table_name = table_name();

	$db = new Database( $credentials[ 0 ], $credentials[ 1 ], $credentials[ 2 ], $credentials[ 3 ] );
	$my_table = new Table ( $table_name, $db );


	$values_arr = array();

	echo "<p id=\"added\">You updated: ";

	$counter = 0;
	foreach ( $_POST as $i )
	{
		if ( $i != "Submit" )
		{
			echo '"' . $i . "\", ";
			$values_arr[ $counter ] = $i;
			$counter = $counter + 1;
		}
		else
			echo " to the model.</p> ";
	}

	$my_table->update_row( $values_arr );

}

function remove_value ()
{
	$credentials = credentials();
	$table_name = table_name();

	$db = new Database( $credentials[ 0 ], $credentials[ 1 ], $credentials[ 2 ], $credentials[ 3 ] );
	$my_table = new Table ( $table_name, $db );


	$id = (int) $_POST[ "id" ];

	echo "<p id=\"removed\">You removed id = " . $id . " </p>";

	$my_table->remove_row_through_id( $id );
}



?>