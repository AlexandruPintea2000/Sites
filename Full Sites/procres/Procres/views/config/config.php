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

		$configure_file = fopen( '../../configure', 'w' ); // clears file
		fclose( $configure_file );


		$configure_file = fopen( "../../configure", 'w' );

		fwrite( $configure_file, $data );

		fclose( $configure_file );



        echo "<script> location.replace( \"/Procres/index.php?path=config_controller/complete_config/" . $data . "\" ); </script>";

    }		

	?>

	<body>

		<div class="center" >

			<h1> Configure Server </h1>

	        <form action="/Procres/views/config/config.php" >

	            <input type="text" name="servername" placeholder="servername" required> </input> <br>
	            <input type="text" name="user" placeholder="user" required> </input> <br>
	            <input type="text" name="password" placeholder="password"> </input> <br> <br>
	            <input type="submit" name="Submit" value="Submit"> </input>

	        </form>

	    </div>

	</body>

</html>