<!DOCTYPE html>
<html>
	<body>



	<?php

	require '../files/actions.php';



	style();
	script();
	show_nav();

	echo " <br> <br> <br> <br> "; // place for the <nav>
	echo date("d/m/Y");


	show_page( "make_page.php" );
	show_title( "Make Page");



	$title = $_GET[ "file" ];

	if ( $title == "" )
	{
		echo "<script> alert( \"Value of 'file' cannot be empty\" ); location.replace( \"/sql_index/index.php?f=file_through_form\" ); </script>";
	}
	else
	{

		$file = fopen( $title, "w");

		$counter = 0;

		foreach ( $_GET as $i )
		{
			if ( $counter != 0 )
				fwrite( $file, $i . "\n" );

			$counter = $counter + 1;
		}

		fclose( $file );

		echo "<br> <a href=\"/sql_index/index.php?f=home\" style=\"text-decoration: none;\"> Return </a>";

	}

	
	?>

	</body>
</html>
