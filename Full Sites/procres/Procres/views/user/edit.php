<!DOCTYPE html>

<html>

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

        echo "<script> location.replace( \"/Procres/index.php?path=user_controller/edit_user/" . $data . "\" ); </script>";
    }

	?>

	<head>
		<title> <?php echo $this->data[ 'title' ]; ?> </title>
		<link rel="stylesheet" href="/Procres/files/style.css">
		<meta name="viewport" content="width=device-width, initial-scale=1">
	</head>

	<body>

		

		<div class="company"> <?php echo $this->data[ 'company' ]; ?> </div>
		<?php if ( $_SESSION[ 'type' ] != "admin" ) echo "<style> #left_nav { display: none !important; } html { padding-left: 10px !important; } </style>"; ?>
		<a class="signed_in_user" href=<?php echo "\"index.php?path=user_controller/view/" . $_SESSION[ 'id' ] . "\""; ?> > <?php echo $_SESSION[ 'username' ]; ?> </a>


		<div class="center" >

			<h1> <?php echo $this->data[ 'title' ]; ?> </h1>

	    </div>


	        <form action="views/user/edit.php" style="background-color: rgb( 243, 243, 243 ); padding: 10px 70px 10px 70px; border: 3px solid rgb( 204, 204, 204 ); border-radius: 10px;" >

	            <input type="number" name="id" value=<?php echo $this->data[ 'id' ]; ?> hidden> </input> <br>


	            <b>Username:</b> <input type="text" name="username" value=<?php echo $this->data[ 'username' ]; ?>> </input> <br>
	            <b>Password:</b> <input type="text" name="password" value=<?php echo $this->data[ 'password' ]; ?>> </input> <br>
				
				<b>Firstname:</b> <input type="text" name="firstname" value=<?php echo $this->data[ 'firstname' ]; ?> > </input> <br>
				<b>Lastname:</b> <input type="text" name="lastname" value=<?php echo $this->data[ 'lastname' ]; ?>> </input> <br> 
				
	            <b>Email:</b> <input type="email" name="email" value=<?php echo $this->data[ 'email' ]; ?>> </input> <br> <br>

	            <input type="text" name="type" value=<?php echo $this->data[ 'type' ]; ?> hidden> </input> <br>


	            <input class="submit" type="submit" name="Submit" value="Submit"> </input>

	        </form>

	        <br>

			<a style="margin-left: 7%;" href=<?php echo "index.php?path=user_controller/view/" . $this->data[ 'id' ]; ?> > Return to View User </a>

	</body>

</html>