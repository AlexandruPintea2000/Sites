<!DOCTYPE html>

<html>

	<head>
		<title> <?php echo $this->data[ 'title' ]; ?> </title>
		<link rel="stylesheet" href="/Procres/files/style.css">
		<meta name="viewport" content="width=device-width, initial-scale=1">
	</head>


	<body>

		<?php $this->show_nav(); ?>

		<div class="company"> <?php echo $this->data[ 'company' ]; ?> </div>
		<?php if ( $_SESSION[ 'type' ] != "admin" ) echo "<style> #left_nav { display: none !important; } html { padding-left: 10px !important; } </style>"; ?>
		<a class="signed_in_user" href=<?php echo "\"index.php?path=user_controller/view/" . $_SESSION[ 'id' ] . "\""; ?> > <?php echo $_SESSION[ 'username' ]; ?> </a>


		<div class="center" >

			<h1> <?php echo $this->data[ 'title' ]; ?>  </h1>

	    </div>



		<style>

			h3
			{
				font-weight: 500;
				font-family: Sans;
				font-size: 30px !important;
				margin: 0px;					
			}

			h3 a
			{
				text-decoration: none;
			}

			h4
			{
				font-size: 20px;
				text-align: center;
				margin: 0px;
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


			pre
			{
				border: 3px solid rgb( 204, 204, 204 );
				padding: 15px;
				border-radius: 15px;
				background-color: rgb( 209, 209, 209 );
			}


			@media only screen and ( max-width: 1000px )
			{
				.contracts_div
				{
					padding: 10px;
				}
			}


			@media only screen and ( max-width: 900px )
			{
				h3 a
				{
					font-size: 30px !important;
				}
			}

		</style>



		Contracts:

		<?php


		$contracts = $this->data[ 'contracts' ];

		foreach ( $contracts as $contract )
		{
			echo "<a href=\"#" . $contract->get_contract_name() . "	\">"  . $contract->get_contract_name() . "</a> / ";
		}
 
		$users = $this->data[ 'users' ];
		$parts = $this->data[ 'parts' ];
		$tasks = $this->data[ 'tasks' ];


		foreach ( $contracts as $contract )
		{

			echo "<p style=\"margin-top: -54px; left: -500px; font-size: 40px; position: absolute;\" id=\"" . $contract->get_contract_name() . "\" > span </p>";

			echo "<div class=\"contract_div\"";

			if ( contract_obsolete( $contract->get_id() ) or contract_finalised( $contract->get_id() ) )
				echo "style=\"background-color: rgb( 204, 204, 204 );\"";
			
			if ( contract_final_month ( $contract->get_id() ) )
				echo "style=\"background-color: rgb( 243, 243, 243 );\"";
			
			echo ">";

			echo "<h3 class=\"center\"><a href=\"index.php?path=contract_controller/view/" . $contract->get_id() . "\">"  . $contract->get_contract_name() . "</a></h3> <br>";

			if ( contract_obsolete( $contract->get_id() ) and ! contract_finalised( $contract->get_id() ) )
				echo "<h4> Contract obsolete, but not finalised </h4>";

			if ( ! contract_obsolete( $contract->get_id() ) and contract_finalised( $contract->get_id() ) )
				echo "<h4> Contract done </h4>";

			if ( contract_obsolete( $contract->get_id() ) and contract_finalised( $contract->get_id() ) )
				echo "<h4> Contract done and obsolete </h4>";


			if ( contract_final_month ( $contract->get_id() ) )
				echo "<h4> Final month of contract! </h4>";


			echo "<br>";


 		    $client = get_user_through_id( $contract->get_client_id() );

			echo "<h2 style=\"font-size: 20px;\"> Client: <b style=\"margin-right: 10px;\"> <a href=\"index.php?path=user_controller/view/" . $client->get_id()	 . "\">" .  $client->get_firstname() . ' ' . $client->get_lastname() . "</a> </b> </h2>  <br>";


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
					<a href=\"index.php?path=part_controller/view/" . $part->get_id() . "\"> View / Edit Part </a>
				</div>

				<p>";



			}


			echo "<br>";

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

							echo " <a href=\"index.php?path=user_controller/view/" . $user->get_id() . "\">" . $user->get_firstname() . ' ' . $user->get_lastname() . " </a> / ";
						}
				}

				if ( $have_users == false )
					echo " ( Unavailable ) <p> <a class=\"add_part_href\" href=\"index.php?path=task_controller/create/part(@)" . $part->get_id() . "\"> Add Users for part \"" . $part->get_part_name() .  "\" </a> </p>";
				else
					echo "<p> <a class=\"add_part_href\" href=\"index.php?path=task_controller/create/part(@)" . $part->get_id() . "\"> Add Users for part \"" . $part->get_part_name() .  "\" </a> </p> <br>";

			}

			if ( $contract->get_details() != "" )
			{
				echo "<br> <b> Contract Details: </b>";

				echo "<pre style=\"font-family: Arial;\">" . $contract->get_details() . "</pre>";
			}

			echo "<p title=\"Auxiliary contract data\" style=\"margin-top: 30px; margin-bottom: 0px;\"><span class=\"auxiliary_data\" > <b>Contract Id:</b> " . $contract->get_id() . "</p> </div>";
		}

		?>

		<br>


	</body>

</html>