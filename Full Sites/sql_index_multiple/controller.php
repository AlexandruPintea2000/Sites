<?php

require "model.php";

function add_value ()
{
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
			echo " to the model.</p>";
	}

	my_table_add( $values_arr[0], $values_arr[1], $values_arr[2], $values_arr[3] );

}


function remove_value ()
{
	$id = (int) $_POST[ "id" ];

	my_table_remove( $id );

	echo "<script> location.replace( \"/sql_index_multiple/index.php?f=remove&action=false\" ); </script>";

}


function update_value ()
{
	$values_arr = array();

	echo "<p id=\"updated\">You updated: ";

	$id = $_POST[ "id" ];

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
			echo " to the model.</p>";
	}

	my_table_update( $id, $values_arr[1], $values_arr[2], $values_arr[3], $values_arr[4] );
}


?>