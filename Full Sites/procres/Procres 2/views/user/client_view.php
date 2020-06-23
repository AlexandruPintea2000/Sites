<!DOCTYPE html>

<html>


	<?php

	if ( empty( $this->data[ 'contracts' ] ) )
	{
		$username_password = $this->data[ 'username' ] . '(@)' . $this->data[ 'password' ];

        echo "<script> alert( \"Your contract was not added yet. Please return later. Thank you!\" ); </script>";

        echo "<script> location.replace( \"/Procres/index.php?path=sign_in_controller/sign_in/" . $username_password . "\" ); </script>";		
	}

	?>

	<head>
		<title> <?php echo $this->data[ 'title' ]; ?> </title>
		<link rel="stylesheet" href="/Procres/files/style.css">
		<meta name="viewport" content="width=device-width, initial-scale=1">
	</head>

	<style>
		
		html
		{
			padding-left: 10.9%;
		}

		.client_details_color
		{
			padding: 10px;
			background-color: rgb( 150, 150, 150 );
			border-radius: 20px;
			margin: 20px 0px;
		}


		.client_details
		{
			padding: 10px 40px;
			background-color: rgb( 234, 234, 234 );
			border-radius: 40px 20px 70px 40px;
			border: 1px solid rgb( 123, 123, 123 );
			border-right: 15px solid rgb( 159, 159, 159 );
			margin: 0px 20%;
		}

		.client_details_title 
		{
			text-align: center;
			font-size: 25px;
			position: absolute;
			right: 25%;
			font-weight: 100 !important;
			margin: 0px;
		}

		p
		{
			font-weight: 400;
			font-family: Sans;
		}

		b
		{
			font-family: Arial;			
		}

		h1, h2
		{
			font-weight: 500;
			font-family: Sans;
			margin: 0px;		
		}

		h3
		{
			font-weight: 500;
			font-family: Sans;
			font-size: 40px;
			margin: 0px;					
		}

		a
		{
			font-weight: 500;
			background-color: rgb( 234, 234, 234 );
			padding: 5px 10px;
			border-radius: 15px;
			text-decoration: none;
			transition: 0.3s;
			margin-right: 10px;
		}

		a:hover
		{
			font-weight: 500;
			background-color: rgb( 230, 230, 230 );
			padding: 5px 10px;
		}



		.contracts_div
		{
			padding: 20px 80px;
			background-color: rgb( 150, 150, 150 );
			border-radius: 20px;
			margin: 30px 0px;
		}

		.contract_div
		{
			border: 3px solid black;
			margin: 20px 0px;
			background-color: rgb( 234, 234, 234 );
			padding: 40px;
			border: 1px solid rgb( 159, 159, 159 );
			border-right: 15px solid rgb( 159, 159, 159 );
			border-radius: 40px 20px 70px 40px;
		}



		.behind_progress
		{
			width: 80% !important;
		}


		nav a
		{
			font-size: 15px;
		}

		nav
		{
			height: 34px;
		}

		.client_nav
		{
			position: absolute;
			right: 40px !important;
			top: 12px;
			font-size: 17px;
		}

		.client_nav_sign_out
		{
			font-size: 15px;
			margin-left: 20px;
		}

		.low_nav
		{
			height: 90px;
			background-color: rgb( 204, 204, 204 );
			padding: 5px 20px;
			border-radius: 10px;
		}

		#left_nav
		{
			background-color: rgb( 243, 243, 243 ) !important; 
			border-width: 1px;
			width: 10%;
		}

		.left_nav_border_href 
		{
			border-radius: 2px !important;
			padding: 0px;
		}

		.left_nav_border_href:hover
		{
			border-radius: 2px !important;
			padding: 0px;
		}

		.left_nav_border_href p
		{
			border-width: 1px 5px 3px 10px;
			border-color: rgb( 204, 204, 204 );
			background-color: rgb( 243, 243, 243 ) !important; 
			margin: 10px 0px 0px 0px;
		}

		.details_href
		{
			border: 3px solid rgb( 234, 234, 234 );
			background-color: rgb( 250, 250, 250 );
		}

		.details_href:hover
		{
			background-color: rgb( 243, 243, 243 );
		}



		.company_name
		{
			position: absolute;
			right: 40px !important;
			font-size: 27px;
			font-weight: 100;
			margin-top: 10px;
		}


		@media only screen and ( max-width: 1000px )
		{
			#left_nav, .client_nav span
			{
				display: none !important;
			}

			html
			{
				padding: 0px;
			}

			.contracts_div
			{
				padding: 10px;
			}
		}

		@media only screen and ( max-width: 750px )
		{
			.client_nav_sign_out
			{
				display: initial !important;
				font-size: 15px !important;
			}

			nav a
			{
				display: none;
			}

			.company_name, .client_details_title
			{
				display: none;
			}
		}


	</style>

	<body>

		<nav>

			<p style="margin: 7px;"> 

			<?php

			if ( $_SESSION[ 'type' ] != "client" )
				echo "<a style=\"display: initial !important; font-size: 15px !important;\" href=\"index.php?path=user_controller/view/" . $this->data[ 'id' ] . "\"> <b>Return to Client</b></a>";

			echo "<b style=\"margin: 0px 20px;\">" . $this->data[ 'company' ] . " </b>";

			$contracts = $this->data[ 'contracts' ];
			$parts = $this->data[ 'parts' ];

			?>

			<div class="client_nav">

				<?php

				echo "<span>" . $this->data[ 'firstname' ] . ' ' . $this->data[ 'lastname' ] . "</span>";

				?>

				<a class="client_nav_sign_out" href="index.php?path=sign_in_controller/user_sign_out"> Sign Out </a>

			</div>

			</p>

		</nav>




		<div id="left_nav" >

			<br> <br> <br>
 
			<?php

			foreach ( $contracts as $contract )
			{
				echo "<a class=\"left_nav_border_href\" href=\"#" . $contract->get_contract_name() . "	\"><p>"  . $contract->get_contract_name() . "</p></a> ";
			}

			?>

			<br> <br>

			<div class="center">
				<a class="details_href" href="#details"> Details</a>
			</div>

		</div>





		<br> <br> <br>

		<div class="center" >

			<h1> Contracts with <b> <?php echo $this->data[ 'company' ]; ?> </b> </h1>

	    </div>


		<p class="center" title="Welcome!"> Welcome <b> <?php echo $this->data[ 'firstname' ]; ?>  <?php echo $this->data[ 'lastname' ]; ?></b>! </p>




		<div class="contracts_div">

		<?php


		foreach ( $contracts as $contract )
		{

			echo "<p style=\"margin-top: -54px; left: -500px; font-size: 40px; position: absolute;\" id=\"" . $contract->get_contract_name() . "\" > span </p>";

			echo "<div class=\"contract_div\">";

			echo "<h3 class=\"center\">"  . $contract->get_contract_name() . " </h3> <br>";

			echo "Contract Date: <b style=\"margin-right: 10px;\">" . $contract->get_contract_date() . " </b>";
			echo "Deadline Date: <b>" . $contract->get_deadline_date() . " </b> <br> <br>";

			foreach ( $parts as $part )
			{
				if ( $part->get_contract_id() != $contract->get_id() )
					continue;

				echo "
				<div class=\"progress_div\"> 
					<b title=\"Progress\">" . $part->get_part_name() . ": </b>
					<div class=\"behind_progress\">
						<div class=\"progress\" style=\"width: " . $part->get_progress() . "%;\" > 
							<span> " . $part->get_progress() . "% </span> 
						</div> 
					</div> 
					<span> / 100% </span>
				</div>

				<p>";



			}


			echo "<br>";

			$users = $this->data[ 'users' ];
			$tasks = $this->data[ 'tasks' ];

			foreach ( $parts as $part )
			{
				if ( $part->get_contract_id() != $contract->get_id() )
					continue;

				echo "Responsable for <b>" . $part->get_part_name() . ": </b> ";

				$have_users = false;
				foreach ( $users as $user )
				{
					foreach ( $tasks as $task )
						if ( $task->get_user_id() == $user->get_id() and $task->get_part_id() == $part->get_id() )
						{
							$have_users = true;

							echo "<p> <b>" . $user->get_firstname() . ' ' . $user->get_lastname() . " </b> / " . $user->get_email(). " </p> ";
						}
				}

				if ( $have_users == false )
					echo "<p> ( Unavailable ) </p>";
			}

			if ( $contract->get_details() != "" )
			{
				echo "<br> <b> Contract Details: </b>";

				echo "<pre style=\"font-family: Arial;\">'" . $contract->get_details() . "'</pre>";
			}

			echo "<p title=\"Auxiliary contract data\" style=\"margin-top: 30px; margin-bottom: 0px;\"><span class=\"auxiliary_data\" > <b>Contract Id:</b> " . $contract->get_id() . "</p> </div>";
		}

		?>

		</div>

		<h3 class="center"> Details </h3>

		<div id="details" class="client_details_color">

			<div class="client_details">

				<p class="client_details_title"> Your Details </p>

				<br>

				<p title="Your Username" style="margin-top: 0px;"> <b>Your Username:</b>  <?php echo $this->data[ 'username' ]; ?> </p>


				<p title="Your Email"> <b>Your Email:</b> <?php echo $this->data[ 'email' ]; ?> </p> 
				<p title="Auxiliary data" style="margin: 10px 0px;"><span class="auxiliary_data" > ( <b>Your User Id:</b> <?php echo $this->data[ 'id' ]; ?> 
				<b>Type:</b> <?php echo $this->data[ 'type' ]; ?> ) </span></p>

				<br>

			</div>

		</div>



		<div class="low_nav">

			<p>

			<?php

			foreach ( $contracts as $contract )
			{
				echo "<a href=\"#" . $contract->get_contract_name() . "	\">"  . $contract->get_contract_name() . "</a>";
			}


			echo "<span class=\"company_name\">" . $this->data[ 'company' ] . "</span>";


			?>

			</p>



		</div>

	</body>

</html>