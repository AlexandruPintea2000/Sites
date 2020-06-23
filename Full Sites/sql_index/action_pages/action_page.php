<!DOCTYPE html>
<html>
	<body>



	<?php

	require '../files/actions.php';



	// Actual webpage

	style();
	script();
	show_nav();

	echo " <br> <br> <br> <br> "; // place for the <nav>
	echo date("d/m/Y");


	show_page( "action_page.php" );
	show_title( "Action Page");


	foreach ( $_GET as $i )
	{
		echo '"' . $i . "\" <br>";
	}

	echo "<br> <a href=\"/sql_index/index.php?f=home\" style=\"text-decoration: none;\"> Return </a>";

	?>

	</body>
</html>
