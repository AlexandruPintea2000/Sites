<!DOCTYPE html>
<html>
	<body>



	<?php

	require 'files/actions.php';










	// Website pages

	function home ()
	{
		show_page( "home" );
		show_title( "Home");
	}

	function alert ()
	{
		show_page( "alert" );
		show_title( "Make an alert");
		echo "Enter your text: <input id=\"alert_text\" type=\"text\" placeholder=\"Alert text\">  </input> ";
		echo " <input type=\"button\" value=\"Alert!\" onclick=\"alert_text()\"></input> ";
	}

	function form ()
	{
		show_page( "form" );
		show_title( "Make a form");

		$form_values = array( "value_1", "value_2", "value_3", "value_4" );

		echo "<div id=\"form_div\">";
		echo "<form style=\"text-align: left;\" action=\"/sql_index/action_pages/action_page.php\">";
		foreach ( $form_values as $i )
			echo "<span class=\"form_text\"> " . $i . ":</span> <input name=\"" . $i . "\" type=\"text\" placeholder=\"" . $i . " text\">  </input>  <br> ";
		echo "<input type=\"submit\" value=\"Submit\">";
		echo "</form>";
		echo "</div>";
	}

	function file_through_form ()
	{
		show_page( "file_through_form" );
		show_title( "Make a file through a form");

		$form_values = array( "title", "contact", "about" );

		$form_values = array( "value_1", "value_2", "value_3", "value_4" );

		echo "<div id=\"form_div\">";
		echo "<form style=\"text-align: left;\" action=\"/sql_index/action_pages/make_page.php\">";


		echo "<span class=\"form_text\"> file:</span> <input name=\"file\" type=\"text\" placeholder=\"file text\">  </input>  <br> ";


		foreach ( $form_values as $i )
			echo "<span class=\"form_text\"> " . $i . ":</span> <input name=\"" . $i . "\" type=\"text\" placeholder=\"" . $i . " text\">  </input>  <br> ";
		echo "<input type=\"submit\" value=\"Submit\">";
		echo "</form>";
		echo "</div>";
	}






	// Actual website

	style();
	script();
	show_nav();


	echo " <br> <br> <br> <br> "; // place for the <nav>
	echo date("d/m/Y");


	if(function_exists($_GET['f'])) {
	   $_GET['f']();
	}


	?>

	</body>
</html>
