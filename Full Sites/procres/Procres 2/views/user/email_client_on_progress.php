<!DOCTYPE html>

<html>

	<?php

	if ( isset( $_GET[ 'email' ] ) )
	{
		foreach ( $_GET as $email_detail )
			if ( empty( $email_detail ) )
			{
 		        echo "<script> alert( \"Please complete email before sending.\" ); </script>";

		        echo "<script> location.replace( \"/Procres/index.php?path=user_controller/email_client_on_progress/" . $_GET[ 'id' ] . "\" ); </script>";
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


		<style>

			.contracts_of_client
			{
				border: 3px solid rgb( 123, 123, 123 );
				padding: 10px;
				background-color: rgb( 243, 243, 243 );
				border-radius: 20px;
			}

		</style>

		<?php

		$contracts = $this->data[ 'contracts' ];
		$parts = $this->data[ 'parts' ];

		$actual_email = "Progress of our contracts\n\n\n";

		foreach ( $contracts as $contract )
		{
			if ( $contract->get_client_id() != $this->data[ 'id' ] )
				continue;
 
 			echo "<div class=\"contracts_of_client\">";

			echo "<b style=\"font-size: 20px;\">"  . $contract->get_contract_name() . " </b> <br> <br>";

			echo "Contract Date: <b style=\"margin-right: 10px;\">" . $contract->get_contract_date() . " </b>";
			echo "Deadline Date: <b>" . $contract->get_deadline_date() . " </b> <br> ";

			$actual_email = $actual_email .  $contract->get_contract_name() . ":";

			$have_parts = false;
			foreach ( $parts as $part )
			{
				if ( $part->get_contract_id() != $contract->get_id() )
					continue;

				if ( $have_parts == false )
				{
					$have_parts = true;
					$actual_email = $actual_email . "\n";
				}

				echo "
				<div class=\"progress_div\"> 
					<b title=\"Progress\">" . $part->get_part_name() . ": </b>
					<div class=\"behind_progress\">
						<div class=\"progress\" style=\"width: " . $part->get_progress() . "%;\" > 
							<span> " . $part->get_progress() . "% </span> 
						</div> 
					</div> 
					<span> / 100% </span>
				</div>";

				$actual_email = $actual_email . "\n " . $part->get_part_name() . ": " . $part->get_progress() . "% / 100%";
			}

			if ( $have_parts )
				$actual_email = $actual_email . "\nContract \"" . $contract->get_contract_name() . "\" Deadline: " . $contract->get_deadline_date() . "\n\n\n";
			else
				$actual_email = $actual_email . " deadline is " . $contract->get_deadline_date() . "\n";

			echo  "</div> <br> ";

		}

		$actual_email = $actual_email . "\n\nFor details, please <a href=\"index.php?path=sign_in_controller/sign_in\"> Sign In </a>.";

		$actual_email = $actual_email . "\n\n\nSent by " . $this->data[ 'company' ] . ".";

		?>

	        <form action="views/user/email.php" >

	        	<b style="margin-right: 40px">To:</b>  <input style="width: 250px;" type="text" name="email_address" value=<?php echo $this->data[ 'email' ]; ?> required> </input> <br>

	            <b>Subject:</b> <input style="width: 250px;" type="text" name="email_subject" placeholder="Subject" value=<?php echo "\"" . $this->data[ 'company' ] . " contracts Progress\""; ?> required> </input> <br>
	            <textarea style="max-width: 97.5% !important; width: 97.5% !important; height: 240px" type="textarea" name="email" cols="40" rows="10" required><?php echo $actual_email; ?></textarea> <br>

				<br> <br>

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