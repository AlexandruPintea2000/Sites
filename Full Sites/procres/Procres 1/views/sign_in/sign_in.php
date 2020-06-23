<!DOCTYPE html>

<html>

	<head>
		<title> Sign In </title>
		<link rel="stylesheet" href="/Procres/files/style.css">
		<meta name="viewport" content="width=device-width, initial-scale=1">
	</head>

	<style>
		
		html 
		{
			padding: 0px;
		}

	</style>


	<?php

	if ( isset( $_GET[ 'username' ] ) )
	{
		echo "echo";

		if ( $_GET[ 'password' ] != "" )
		{
	        $data = "";
	        foreach ( $_GET as $i )
	        {
	            if ( ! empty( $i ) and $i != "Submit" )
	                $data = $data . $i . "(@)";

	            if ( empty( $i ) )
	    	        $data = $data . "empty(@)";
	         }

	        echo "<script> location.replace( \"/Procres/index.php?path=sign_in_controller/user_sign_in/" . $data . "\" ); </script>";
   		}
   		else
  	        echo "<script> location.replace( \"/Procres/index.php?path=user_controller/email_user_details/" . $_GET[ 'username' ] 	. "\" ); </script>"; 			
    }

	?>


	<body>

		<div class="center" >


			<br> <br> <br>

			<h1> Sign In </h1>

			<form action="views/sign_in/sign_in.php">

				<input type="text" name="username" placeholder="username" value=<?php echo '"' . $this->data[ 'username' ] .'"'; ?> required> </input> <br>
				<input type="text" name="password" placeholder="password" value=<?php echo '"' . $this->data[ 'password' ] .'"'; ?> > </input> <br>
				<input type="Submit" value="Submit"> </input>

				<br><br>

				or <input type="Submit" value="Send Details"> </input> to sign in

			</form>

			<br>

			Or, perhaps you want to: <a href=<?php echo "\"index.php?path=sign_in_controller/sign_up/" . $this->data[ 'username' ] .'(@)' . $this->data[ 'password' ] . "\""; ?> > Sign Up </a>

	    </div>

	</body>

</html>