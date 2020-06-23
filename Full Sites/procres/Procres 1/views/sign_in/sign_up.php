<!DOCTYPE html>

<html>

	<head>
		<title> Sign Up </title>
		<link rel="stylesheet" href="/Procres/files/style.css">
		<meta name="viewport" content="width=device-width, initial-scale=1">
	</head>

	<style>
		
		html 
		{
			padding: 0px;
		}

		select
		{
			padding: 5px 10px;
			border: 3px solid rgb( 201, 200, 200 );
			border-radius: 10px;
			outline: none;	
			color: rgb( 90, 90, 90 );
			margin: 3px 0px;
			background-color: rgb( 240, 240, 240 );
			transition: 0.5s;
		}

		select:hover
		{
			border: 3px solid rgb( 150, 150, 150 );
			color: rgb( 100, 100, 100 );
			border-radius: 10px;
			outline: none;
		}

		option
		{
			background-color: rgb( 204, 204, 204 );
			color: rgb( 90, 90, 90 );
		}


	</style>


	<?php

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

        echo "<script> location.replace( \"/Procres/index.php?path=sign_in_controller/user_sign_up/" . $data . "\" ); </script>";
    }


	?>


	<body>

		<div class="center" >

			<br>

			<h1> Sign Up </h1>

	    </div>

		        <form action="views/sign_in/sign_up.php" style="margin-left: 40%">

		            <input type="number" name="id" placeholder="id" value=<?php echo get_user_id(); ?> hidden> </input>


		            <b>Username:</b> <input type="text" name="username" placeholder="username" value=<?php echo '"'. $this->data[ 'username' ] . '"'; ?> required> </input> <br>
		            <b>Password:</b> <input type="text" name="password" placeholder="password" value=<?php echo '"' . $this->data[ 'password' ] . '"'; ?> required> </input> <br>			
					<b>Firstname:</b> <input type="text" name="firstname" placeholder="firstname" value=<?php echo '"'. $this->data[ 'firstname' ] . '"'; ?> required> </input> <br>
					<b>Lastname:</b> <input type="text" name="lastname" placeholder="lastname" value=<?php echo '"'. $this->data[ 'lastname' ] . '"'; ?> required> </input> <br> 

		            <b>Email:</b> <input type="email" name="email" placeholder="email" value=<?php echo '"'. $this->data[ 'email' ] . '"'; ?> required> </input> <br>

		            <b>Type:</b> 

		            <select name="type" >

		            	<option value="admin" <?php if ( $this->data[ 'type' ] == "admin" ) echo "selected"; ?>> admin </option>
		            	<option value="client" <?php if ( $this->data[ 'type' ] == "client" ) echo "selected"; ?>> client </option> 
		            	<option value="employee" <?php if ( $this->data[ 'type' ] == "employee" ) echo "selected"; ?>> employee </option>

		            </select>

		            <br> <br>


		            <input class="submit" style="margin-left: 10.9%;" type="submit" value="Submit"> </input>

	        </form>

	        <br><br>


	        <div class="center">

				Or, perhaps you want to: <a href=<?php echo "\"index.php?path=sign_in_controller/sign_in/" . $this->data[ 'username' ] .'(@)' . $this->data[ 'password' ] . "\""; ?> > Sign In </a>
			
			</div>
	</body>

</html>