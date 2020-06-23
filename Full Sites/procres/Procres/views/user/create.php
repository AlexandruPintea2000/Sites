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

		

		<div class="company"> <?php echo $this->data[ 'company' ]; ?> </div>
		<?php if ( $_SESSION[ 'type' ] != "admin" ) echo "<style> #left_nav { display: none !important; } html { padding-left: 10px !important; } </style>"; ?>
		<a class="signed_in_user" href=<?php echo "\"index.php?path=user_controller/view/" . $_SESSION[ 'id' ] . "\""; ?> > <?php echo $_SESSION[ 'username' ]; ?> </a>


		<div class="center" >

			<h1> Make User </h1>


			<div class="div_center" >

				<div style="background-color: rgb( 243, 243, 243 ); padding: 10px 5% 10px 5%; border: 3px solid rgb( 204, 204, 204 ); border-radius: 10px;">

					<h2> Let them make it </h2>

					<p style="text-align: left;"> <b> In order to let users set their own password, please: </b> 

					<br>

					<?php

					echo "<a href=\"index.php?path=user_controller/email_initialization_id/admin\"> Send an \"Admin\" Initialization Id </a> <br> ";
					echo "<a href=\"index.php?path=user_controller/email_initialization_id/employee\"> Send an \"Employee\" Initialization Id </a> <br> ";
					echo "<a href=\"index.php?path=user_controller/email_initialization_id/client\"> Send a \"Client\" Initialization Id </a>";

			    	?> 

			    	<br>

			    	<b> When they get an "Initialization Id", they will make their own users. </b>

					</p>

				</div>

			</div>



			<h4 style="background-color: rgb( 234, 234, 234 ); border-radius: 10px;"> or </h4>

			<h2> Make it </h2>

	    </div>


	   

	    <div class="div_center">

	        <form action="views/user/create.php" style="background-color: rgb( 243, 243, 243 ); padding: 10px 5% 10px 5%; border: 3px solid rgb( 204, 204, 204 ); border-radius: 10px;" >

	            <input type="number" name="id" placeholder="id" value=<?php echo $this->data[ 'user_id' ]; ?> hidden> </input>

	            <?php

	            $taken = false;
	            if ( isset( $this->data[ 'username' ] ) )
	            	$taken = true;

	            ?>

	            <b>Username:</b> <input <?php if ( $taken ) echo " value=\"" . $this->data[ 'username' ] . "\" "; ?> type="text" name="username" placeholder="username" > </input> <br>
	            <b>Password:</b> <input <?php if ( $taken ) echo " value=\"" . $this->data[ 'password' ] . "\" "; ?> type="text" name="password" placeholder="password" > </input> <br>
				
				<b>Firstname:</b> <input <?php if ( $taken ) echo " value=\"" . $this->data[ 'firstname' ] . "\" "; ?> type="text" name="firstname" placeholder="firstname" > </input> <br>
				<b>Lastname:</b> <input <?php if ( $taken ) echo " value=\"" . $this->data[ 'lastname' ] . "\" "; ?> type="text" name="lastname" placeholder="lastname" > </input> <br> 

	            <b>Email:</b> <input <?php if ( $taken ) echo " value=\"" . $this->data[ 'email' ] . "\" "; ?> type="email" name="email" placeholder="email" > </input> <br>

	            <b>Type:</b> 

	            <select name="type">

	            	<option value="admin" <?php if ( $this->data[ 'type' ] == "admin" ) echo "selected"?>> admin </option>
	            	<option value="client" <?php if ( $this->data[ 'type' ] == "client" ) echo "selected"?>> client </option>
	            	<option value="employee" <?php if ( $this->data[ 'type' ] == "employee" ) echo "selected"?>> employee </option>

	            </select>
	            <br>


	            <input class="submit" type="submit" name="Submit" value="Submit"> </input>

	        </form>


	    </div>

	</body>

</html>