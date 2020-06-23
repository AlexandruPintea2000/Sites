<!DOCTYPE html>
<html>
	<body>



	<?php
	
	require "view.php";


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
