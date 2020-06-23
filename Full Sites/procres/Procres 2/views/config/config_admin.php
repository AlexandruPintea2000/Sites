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


	// For "Form on this page"


	if ( isset( $_GET[ 'username' ] ) )
	{
        $data = "";
        foreach ( $_GET as $i )
        {
            if ( ! empty( $i ) and $i != "Submit" )
                $data = $data . $i . "(@)";

            if ( empty( $i ) )
    	        $data = $data . "empty(@)";
         }

        echo "<script> location.replace( \"/Procres/index.php?path=config_controller/complete_config_admin/" . $data . "\" ); </script>";
    }		


    // For "Form on the page of an admin that wants to set the name of the company"


	if ( isset( $_GET[ 'company_name' ] ) )
	{
        echo "<script> location.replace( \"/Procres/index.php?path=config_controller/set_company_name/" . $_GET[ 'company_name' ] . "\" ); </script>";
    }	


   // For "Load Database"


	if ( isset( $_GET[ 'database_file' ] ) )
	{
        echo "<script> alert( \"database_file\" ); </script>";	

        $file = $_GET[ 'database_file' ];

        echo "<script> location.replace( \"/Procres/index.php?path=config_controller/load_database/" . $file . "\" ); </script>";
    }	



	?>

	<body>

		<div class="center" >

			<h1> Configure Admin </h1>

	        <form action="views/config/config_admin.php" >

	            <input type="text" name="username" placeholder="username" required> </input> <br>
	            <input type="text" name="password" placeholder="password" required> </input> <br> <br>
				
				<input type="text" name="firstname" placeholder="firstname" required> </input> <br>
				<input type="text" name="lastname" placeholder="lastname" required> </input> <br> 

	            <input type="email" name="email" placeholder="email" required> </input> <br> <br>

	            <input type="text" name="company" placeholder="company" required> </input> <br> <span style="font-size: 14px;"> < 14 in length, or use acronim </span><br>  <br>

	            <input class="submit" type="submit" name="Submit" value="Submit"> </input>

	        </form>

	    </div>

	</body>

</html>