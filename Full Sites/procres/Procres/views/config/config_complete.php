<!DOCTYPE html>

<html>

	<head>
		<title> Configuration </title>
		<link rel="stylesheet" href="/Procres/files/style.css">
		<meta name="viewport" content="width=device-width, initial-scale=1">
	</head>


	<style>

		html 
		{
			padding-left: 0px;
		}

	</style>



	<?php 

	if ( isset( $_GET[ 'servername' ] ) )
	{
        $data = "";
        foreach ( $_GET as $i )
        {
            if ( ! empty( $i ) and $i != "Submit" )
                $data = $data . $i . "(@)";

            if ( empty( $i ) )
    	        $data = $data . "empty(@)";

         }

        echo "<script> location.replace( \"/Procres/index.php?path=config_controller/complete_config/" . $data . "\" ); </script>";
    }		

	?>

	<body>

		<div class="center" >

			<h1> Configuration Complete! </h1>

			<a href="index.php?path=sign_in_controller/sign_in"> Sign In </a>
			
	    </div>

	</body>

</html>