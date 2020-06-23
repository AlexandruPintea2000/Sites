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

	for ( $i = 0; $i < 5; $i = $i + 1 )
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







function add_value ()
{
	$credentials = credentials();

	$db = new Database( $credentials[ 0 ], $credentials[ 1 ], $credentials[ 2 ], $credentials[ 3 ] );
	$my_table = new Table ( $credentials[ 4 ], $db );


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

	$db = new Database( $credentials[ 0 ], $credentials[ 1 ], $credentials[ 2 ], $credentials[ 3 ] );
	$my_table = new Table ( $credentials[ 4 ], $db );


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

	$db = new Database( $credentials[ 0 ], $credentials[ 1 ], $credentials[ 2 ], $credentials[ 3 ] );
	$my_table = new Table ( $credentials[ 4 ], $db );

	$id = (int) $_POST[ "id" ];

	echo "<p id=\"removed\">You removed id = " . $id . " </p>";

	$my_table->remove_row_through_id( $id );
}



?>