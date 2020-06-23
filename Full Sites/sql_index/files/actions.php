<?php



	// Style and Nav on every page

	function style ()
	{
		echo "
			<link rel=\"stylesheet\" href=\"/sql_index/files/style.css\">
			<meta name=\"viewport\" content=\"width=device-width, initial-scale=1\">
			";	
	}

	function script ()
	{
		echo "
			<script src=\"/sql_index/files/script.js\"></script>
			";	
	}

	function show_nav() // Add
	{
		echo "
			<div style=\"text-align: center; position: fixed;\">
				<nav>
				  	<span> <a href=\"/sql_index/index.php?f=home\"> Home</a> </span>
				  	<span> <a href=\"/sql_index/index.php?f=alert\"> Alert</a> </span>
				  	<span> <a href=\"/sql_index/index.php?f=form\"> Form</a> </span>
				  	<span> <a href=\"/sql_index/index.php?f=file_through_form\"> File</a> </span>
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

?>