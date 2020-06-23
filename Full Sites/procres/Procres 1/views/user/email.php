<!DOCTYPE html>

<html>

	<?php

	if ( isset( $_GET[ 'email' ] ) )
	{
		foreach ( $_GET as $email_detail )
			if ( empty( $email_detail ) )
			{
 		        echo "<script> alert( \"Please complete email before sending.\" ); </script>";

		        echo "<script> location.replace( \"/Procres/index.php?path=user_controller/email/" . $_GET[ 'id' ] . "\" ); </script>";
		        break;
			}
	

        $data = "";
        foreach ( $_GET as $i )
        {
            if ( ! empty( $i ) and $i != "Submit" )
                $data = $data . $i . "(@)";

            if ( empty( $i ) )
    	        $data = $data . "empty(@)";

         }

        echo "<script> location.replace( \"/Procres/index.php?path=user_controller/email_user/" . $data . "\" ); </script>";
    }

	?>

	<head>
		<title> Send Email </title>
		<link rel="stylesheet" href="/Procres/files/style.css">
		<meta name="viewport" content="width=device-width, initial-scale=1">
	</head>

	<body>

		<?php $this->show_nav(); ?>

		<div class="company"> <?php echo $this->data[ 'company' ]; ?> </div>
		<?php if ( $_SESSION[ 'type' ] != "admin" ) echo "<style> #left_nav { display: none !important; } html { padding-left: 10px !important; } </style>"; ?>
		<a class="signed_in_user" href=<?php echo "\"index.php?path=user_controller/view/" . $_SESSION[ 'id' ] . "\""; ?> > <?php echo $_SESSION[ 'username' ]; ?> </a>


		<div class="center" >

			<h1> Send Email </h1>

		</div>


	        <form action="views/user/email.php" >

	        	<b style="margin-right: 40px">To:</b>  <input style="width: 250px;" type="text" name="email_address" value=<?php echo $this->data[ 'email' ]; ?> required> </input> <br>

	            <b>Subject:</b> <input style="width: 250px;" type="text" name="email_subject" placeholder="Subject" required> </input> <br>
	            <textarea style="max-width: 97.5% !important; width: 97.5% !important; height: 240px" type="textarea" name="email" cols="40" rows="10" required></textarea> <br>

	            <span style="font-size: 10.9px"> Sent by <?php echo $_SESSION[ 'name' ] . " ( " . $_SESSION[ 'email' ] . " )" ?> </span>

	            <!-- if any "required" is deleted -->
				<input style="width: 250px;" type="text" name="id" placeholder="id" value=<?php echo $this->data[ 'id' ]; ?> hidden> </input> <br> <br>

				<div class="center">

		            <input class="submit" type="submit" value="Send"> </input>

		           </div>

	        </form>



		<div class="center" >

	        <br>

			<a href=<?php echo "index.php?path=user_controller/view/" . $this->data[ 'id' ]; ?> > Return to View User </a>

	    </div>

	</body>

</html>