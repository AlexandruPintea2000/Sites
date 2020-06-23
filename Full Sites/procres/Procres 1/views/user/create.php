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

        echo "<script> location.replace( \"/Procres/index.php?path=user_controller/create_user/" . $data . "\" ); </script>";
    }

	?>

	<head>
		<title> Make User </title>
		<link rel="stylesheet" href="/Procres/files/style.css">
		<meta name="viewport" content="width=device-width, initial-scale=1">
	</head>


	<body>

		<?php $this->show_nav(); ?>

		<div class="company"> <?php echo $this->data[ 'company' ]; ?> </div>
		<?php if ( $_SESSION[ 'type' ] != "admin" ) echo "<style> #left_nav { display: none !important; } html { padding-left: 10px !important; } </style>"; ?>
		<a class="signed_in_user" href=<?php echo "\"index.php?path=user_controller/view/" . $_SESSION[ 'id' ] . "\""; ?> > <?php echo $_SESSION[ 'username' ]; ?> </a>


		<div class="center" >

			<h1> Make User </h1>

	    </div>


	        <form action="views/user/create.php" >

	            <input type="number" name="id" placeholder="id" value=<?php echo $this->data[ 'user_id' ]; ?> hidden> </input>


	            <b>Username:</b> <input type="text" name="username" placeholder="username" > </input> <br>
	            <b>Password:</b> <input type="text" name="password" placeholder="password" > </input> <br>
				
				<b>Firstname:</b> <input type="text" name="firstname" placeholder="firstname" > </input> <br>
				<b>Lastname:</b> <input type="text" name="lastname" placeholder="lastname" > </input> <br> 

	            <b>Email:</b> <input type="email" name="email" placeholder="email" > </input> <br>

	            <b>Type:</b> 

	            <select name="type">

	            	<option value="admin" <?php if ( $this->data[ 'type' ] == "admin" ) echo "selected"?>> admin </option>
	            	<option value="client" <?php if ( $this->data[ 'type' ] == "client" ) echo "selected"?>> client </option>
	            	<option value="employee" <?php if ( $this->data[ 'type' ] == "employee" ) echo "selected"?>> employee </option>

	            </select>
	            <br>


	            <input class="submit" type="submit" name="Submit" value="Submit"> </input>

	        </form>



	</body>

</html>