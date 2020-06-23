<?php

require "controller.php";


// Style and Nav on every page

function style ()
{
	echo "
		<link rel=\"stylesheet\" href=\"/sql_index_multiple/files/style.css\">
		<meta name=\"viewport\" content=\"width=device-width, initial-scale=1\">
		";	
}

function script ()
{
	echo "
		<script src=\"/sql_index_multiple/files/script.js\"></script>
		";	
}

function show_nav() // Add
{
	echo "
		<div style=\"text-align: center; position: fixed;\">
			<nav>
			  	<span> <a href=\"/sql_index_multiple/index.php?f=home\"> Home</a> </span>
			  	<span> <a href=\"/sql_index_multiple/index.php?f=add&action=false\"> Add</a> </span>
			  	<span> <a href=\"/sql_index_multiple/index.php?f=show\"> Show</a> </span>
			  	<span> <a href=\"/sql_index_multiple/index.php?f=update&action=false\"> Update</a> </span>
			  	<span> <a href=\"/sql_index_multiple/index.php?f=remove&action=false\"> Remove</a> </span>
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


// Website pages

function home ()
{
	show_page( "home" );
	show_title( "Home");
}

function add ()
{
	show_page( "add" );
	show_title( "Add ");

	$form_values = array( "value_1", "value_2", "value_3", "value_4" );

	if(function_exists($_GET['action'])) {
	   $_GET['action']();
	}


	echo "<div id=\"form_div\">";
	echo "<form style=\"text-align: left;\" action=\"/sql_index_multiple/index.php?f=add&action=add_value\" method=\"post\">";

	foreach ( $form_values as $i )
		echo "<span class=\"form_text\"> " . $i . ":</span> <input name=\"" . $i . "\" type=\"text\" placeholder=\"" . $i . " text\">  </input>  <br> ";
	echo "<input type=\"submit\" value=\"Submit\" name=\"Submit\"></input>";
	echo "</form>";
	echo "</div>";
}

function show ()
{
	show_page( "show" );
	show_title( "Show");

	my_table_show();
}

function update ()
{
	show_page( "update" );
	show_title( "Update ");

	$form_values = array( "id", "value_1", "value_2", "value_3", "value_4" );

	if(function_exists($_GET['action'])) {
	   $_GET['action']();
	}


	my_table_show();

	echo "<div id=\"form_div\">";
	echo "<form style=\"text-align: left;\" action=\"/sql_index_multiple/index.php?f=update&action=update_value\" method=\"post\">";

	foreach ( $form_values as $i )
		echo "<span class=\"form_text\"> " . $i . ":</span> <input name=\"" . $i . "\" type=\"text\" placeholder=\"" . $i . " text\">  </input>  <br> ";
	echo "<input type=\"submit\" value=\"Submit\" name=\"Submit\"></input>";
	echo "</form>";
	echo "</div>";

}


function remove ()
{
	show_page( "remove" );
	show_title( "Remove ");

	my_table_show();

	if(function_exists($_GET['action'])) {
	   $_GET['action']();
	}

	echo "<div id=\"form_div\">";
	echo "<form style=\"text-align: left;\" action=\"/sql_index_multiple/index.php?f=remove&action=remove_value\" method=\"post\">";
	echo "<span class=\"form_text\"> id:</span> <input name=\"id\" type=\"text\" placeholder=\"id\">  </input>  <br> ";
	echo "<input type=\"submit\" value=\"Submit\" name=\"Submit\"></input>";
	echo "</form>";
	echo "</div>";

}


?>