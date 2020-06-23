<!DOCTYPE html>
<html>
	<body>



	<?php

	require "view.php";

	// Actual website


	style();
	script();




	function sign_in ()
	{
		$servername = $_POST[ "server" ];
		$username = $_POST[ "user" ];
		$password = $_POST[ "password" ];
		$dbname = $_POST[ "database" ];
	


		$credentials = fopen("credentials.txt", "w");

		fwrite( $credentials, "'" . $servername . "'\n" );
		fwrite( $credentials, "'" . $username . "'\n" );
		fwrite( $credentials, "'" . $password . "'\n" );
		fwrite( $credentials, "'" . $dbname . "'\n" );

		fclose( $credentials );


		echo "Done";

		echo "<script> location.replace( \"/database_row_final/index.php?f=tables&action=false\" ); </script>";
	}



	if(function_exists($_GET['f'])) { 
	   $_GET['f']();
	}


	?>

	</body>
</html>
