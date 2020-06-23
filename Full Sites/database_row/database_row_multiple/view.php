<?php

require "controller.php";


// Style and Nav on every page

function style ()
{
	echo "
		<link rel=\"stylesheet\" href=\"/database_row_multiple/files/style.css\">
		<meta name=\"viewport\" content=\"width=device-width, initial-scale=1\">
		";	
}

function script ()
{
	echo "
		<script src=\"/database_row_multiple/files/script.js\"></script>
		";	
}

function show_nav() // Add
{
	echo "
		<div style=\"text-align: center; position: fixed;\">
			<nav>
			  	<span> <a href=\"/database_row_multiple/index.php?f=add&action=false\"> Add</a> </span>
			  	<span> <a href=\"/database_row_multiple/index.php?f=show\"> Show</a> </span>
			  	<span> <a href=\"/database_row_multiple/index.php?f=update&action=false\"> Update</a> </span>
			  	<span> <a href=\"/database_row_multiple/index.php?f=remove&action=false\"> Remove</a> </span>
			  	<span> <a href=\"/database_row_multiple/index.php?f=sign_out\"> Sign Out</a> </span>
			</nav>
		</div> ";
}









function show_page ( string $a )
{
	echo "<p id=\"show_page\" > You are on page: " . $a . ". </p> ";
}

function show_title ( string $a )
{
	echo "<hr>";
	echo "<h1 id=\"title\" > " . $a . " </h1> ";
	echo "<hr>";
}

function show_table ()
{
	$credentials = credentials();

	$db = new Database( $credentials[ 0 ], $credentials[ 1 ], $credentials[ 2 ], $credentials[ 3 ] );
	$my_table = new Table ( $credentials[ 4 ], $db );

	$my_table_rows = $my_table->get_table();

	$i = 0;
	foreach ( $my_table_rows as $row )
	{
		$i = $i + 1;

		echo $row.' ';

		if ( $i % $my_table->get_num_columns() == 0 )
			echo "<br>";
	}
}

// Website pages

function home ()
{
	show_page( "home" );
	show_title( "Home Sign in");

	echo "<div id=\"form_div\">";
	echo "<form style=\"text-align: left;\" action=\"/database_row_multiple/index.php?f=home&action=sign_in\" method=\"post\">";
		echo "Server: <input type=\"text\" name=\"server\" placeholder=\"server name\" > </input> <br>";
		echo "User: <input type=\"text\" name=\"user\" placeholder=\"user\" >  </input> <br>";
		echo "Password: <input type=\"text\" name=\"password\" placeholder=\"password\" >  </input> <br>";
		echo "Database: <input type=\"text\" name=\"database\" placeholder=\"database\" >  </input> <br>";
		echo "Table: <input type=\"text\" name=\"table\" placeholder=\"table name\" >  </input> <br>";
		echo "<input type=\"submit\" value=\"Submit\" name=\"Submit\"></input>";
	echo "</form>";
	echo "</div>";

	if(function_exists($_GET['action'])) {
	   $_GET['action']();
	}

}

function add ()
{
	if ( !check_credentials() )
	{
		show_page( "add" );
		show_title( "You are not Signed In.");

		echo "<a href=\"/database_row_multiple/index.php?f=home&action=false\"> Sign in </a>";

		return;
	}

	show_nav();

	echo " <br> <br> <br> <br> "; // place for the <nav>
	echo date("d/m/Y");

	show_page( "add" );
	show_title( "Add ");


	$credentials = credentials();


	$db = new Database( $credentials[ 0 ], $credentials[ 1 ], $credentials[ 2 ], $credentials[ 3 ] );
	$my_table = new Table ( $credentials[ 4 ], $db );

	$form_values = $my_table->get_columns();


	if(function_exists($_GET['action'])) {
	   $_GET['action']();
	}


	show_table();

	echo "<div id=\"form_div\">";
	echo "<form style=\"text-align: left;\" action=\"/database_row_multiple/index.php?f=add&action=add_value\" method=\"post\">";

	foreach ( $form_values as $i )
		echo "<span class=\"form_text\"> " . $i . ":</span> <input name=\"" . $i . "\" type=\"text\" placeholder=\"" . $i . " text\">  </input>  <br> ";
	echo "<input type=\"submit\" value=\"Submit\" name=\"Submit\"></input>";
	echo "</form>";
	echo "</div>";
}


function show ()
{
	if ( !check_credentials() )
	{
		show_page( "show" );
		show_title( "You are not Signed In.");

		echo "<a href=\"/database_row_multiple/index.php?f=home&action=false\"> Sign in </a>";

		return;
	}

	show_nav();

	echo " <br> <br> <br> <br> "; // place for the <nav>
	echo date("d/m/Y");


	show_page( "show" );
	show_title( "Show");

	show_table();
}

function update ()
{
	if ( !check_credentials() )
	{
		show_page( "update" );
		show_title( "You are not Signed In.");

		echo "<a href=\"/database_row_multiple/index.php?f=home&action=false\"> Sign in </a>";


		return;
	}


	show_nav();

	echo " <br> <br> <br> <br> "; // place for the <nav>
	echo date("d/m/Y");


	show_page( "update" );
	show_title( "Update ");



	if(function_exists($_GET['action'])) {
	   $_GET['action']();
	}


	show_table();

	$credentials = credentials();

	$db = new Database( $credentials[ 0 ], $credentials[ 1 ], $credentials[ 2 ], $credentials[ 3 ] );
	$my_table = new Table ( $credentials[ 4 ], $db );

	$form_values = $my_table->get_columns();



	echo "<div id=\"form_div\">";
	echo "<form style=\"text-align: left;\" action=\"/database_row_multiple/index.php?f=update&action=update_value\" method=\"post\">";

	foreach ( $form_values as $i )
		echo "<span class=\"form_text\"> " . $i . ":</span> <input name=\"" . $i . "\" type=\"text\" placeholder=\"" . $i . " text\">  </input>  <br> ";
	echo "<input type=\"submit\" value=\"Submit\" name=\"Submit\"></input>";
	echo "</form>";
	echo "</div>";

}


function remove ()
{
	if ( !check_credentials() )
	{
		show_page( "remove" );
		show_title( "You are not Signed In.");

		echo "<a href=\"/database_row_multiple/index.php?f=home&action=false\"> Sign in </a>";

		return;
	}

	show_nav();

	echo " <br> <br> <br> <br> "; // place for the <nav>
	echo date("d/m/Y");


	show_page( "remove" );
	show_title( "Remove ");

	if(function_exists($_GET['action'])) {
	   $_GET['action']();
	}

	show_table();

	echo "<div id=\"form_div\">";
	echo "<form style=\"text-align: left;\" action=\"/database_row_multiple/index.php?f=remove&action=remove_value\" method=\"post\">";
	echo "<span class=\"form_text\"> id:</span> <input name=\"id\" type=\"text\" placeholder=\"id\">  </input>  <br> ";
	echo "<input type=\"submit\" value=\"Submit\" name=\"Submit\"></input>";
	echo "</form>";
	echo "</div>";

}





function sign_out ()
{
	$credentials = fopen("credentials.txt", "w");
	fclose( $credentials );

	echo "<script> alert( \"Signed out.\" );  location.replace( \"/database_row_multiple/index.php?f=home&action=false\" ); </script>";
}


?>