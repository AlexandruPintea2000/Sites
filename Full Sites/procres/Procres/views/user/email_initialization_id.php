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

		

		<div class="company"> <?php echo $this->data[ 'company' ]; ?> </div>
		<?php if ( $_SESSION[ 'type' ] != "admin" ) echo "<style> #left_nav { display: none !important; } html { padding-left: 10px !important; } </style>"; ?>
		<a class="signed_in_user" href=<?php echo "\"index.php?path=user_controller/view/" . $_SESSION[ 'id' ] . "\""; ?> > <?php echo $_SESSION[ 'username' ]; ?> </a>


		<div class="center" >

			<h1> Send Email </h1>

			<div class="delete_div">

				<h3> Only send an "Initialization Email" to clients that have a contract with you in real life.  </h3>

				<h3> An "Initialization Email" in only for clients that have not already signed up. <br> <a href="index.php?path=user_controller/index/client">Clients that have already signed up</a>, do not have to be sent such an email anymore. </h3>

			</div>

		</div>

		<br>

	        <form action="views/user/email.php" >

	        	<b style="margin-right: 40px">To:</b>  <input style="width: 250px;" type="text" name="email_address"  placeholder="Email Address" required> </input> <br>

	            <b>Subject:</b> <input style="width: 250px;" type="text" name="email_subject" placeholder="Subject" value=<?php echo "\"" . $this->data[ 'company' ] . " - Initialization\""; ?> required> </input> <br>
	            <textarea style="max-width: 97.5% !important; width: 97.5% !important; height: 240px" type="textarea" name="email" cols="40" rows="10" required><?php echo $this->data[ 'email' ] ?></textarea> <br>

	            <span style="font-size: 10.9px"> Sent by <?php echo $_SESSION[ 'name' ] . " ( " . $_SESSION[ 'email' ] . " )" ?> </span>


				<div class="center">

		            <input class="submit" type="submit" value="Send Initialization Id"> </input>

		           </div>

	        </form>




	</body>

</html>