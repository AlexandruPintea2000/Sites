<!DOCTYPE html>

<html>

	<head>
		<title> <?php echo $this->data[ 'contract_name' ]; ?> </title>
		<link rel="stylesheet" href="/Procres/files/style.css">
		<meta name="viewport" content="width=device-width, initial-scale=1">
	</head>


	<body>

		<?php $this->show_nav(); ?>

		<div class="company"> <?php echo $this->data[ 'company' ]; ?> </div>
		<?php if ( $_SESSION[ 'type' ] != "admin" ) echo "<style> #left_nav { display: none !important; } html { padding-left: 10px !important; } </style>"; ?>
		<a class="signed_in_user" href=<?php echo "\"index.php?path=user_controller/view/" . $_SESSION[ 'id' ] . "\""; ?> > <?php echo $_SESSION[ 'username' ]; ?> </a>


		<div class="center" >

			<h1> <?php echo ucfirst( $this->data[ 'contract_name' ] ); ?>  </h1>

			<?php

			// Contract obsolete / finalised

			if ( contract_obsolete( $this->data[ 'id' ] ) or contract_finalised ( $this->data[ 'id' ] ) )
			{

				if ( contract_finalised ( $this->data[ 'id' ] ) and ! contract_obsolete( $this->data[ 'id' ] ) )
					echo "<h2> Contract is done. </h2>";

				if ( ! contract_finalised ( $this->data[ 'id' ] ) and contract_obsolete( $this->data[ 'id' ] ) )
					echo "<h2> Contract is obsolete. </h2>";

				if ( contract_finalised ( $this->data[ 'id' ] ) and contract_obsolete( $this->data[ 'id' ] ) )
					echo "<h2> Contract is done and obsolete. </h2>";


				echo "<style> html{ background-color: rgb( 234, 234, 234 ); } </style>";

				echo "<div class=\"div_center\">";
				if ( $_SESSION[ 'type' ] == "admin" )
					echo "<h2 class=\"delete_border\" style=\"width: 70%; text-align: center;\"> <a href=\"index.php?path=contract_controller/delete/" . $this->data[ 'id' ] . "\">Delete the entire contract</a>, not each  part on its own. </h2>";
				else
					echo "<h2 class=\"delete_border\" style=\"width: 70%;\"> Please tell an admin to delete contract. </h2>";
				echo "</div>";
			}

			if ( contract_final_month( $this->data[ 'id' ] ) )
				echo "<h2> Final month of Contract! </h2>";

			?>

	    </div>


		<p title="Contract name"> <b>Contract name:</b>  <?php echo ucfirst( $this->data[ 'contract_name' ] ); ?> </p>


		<?php

		$parts = $this->data[ 'parts' ];

		foreach ( $parts as $part )
			if ( $part->get_contract_id() == $this->data[ 'id' ] )
			{
				echo "
				<div class=\"progress_div\"> 
					<b title=\"Progress\">" . $part->get_part_name() . ": </b>
					<div class=\"behind_progress\">
						<div class=\"progress\" style=\"width: " . $part->get_progress() . "%;\" > 
							<span> " . $part->get_progress() . "% </span> 
						</div> 
					</div> 
					<span> / 100% </span>
					<a title=\"Edit Progress\" href=\"index.php?path=part_controller/view/" .$part->get_id() . "\" > Edit Progress</a> 
					<a title=\"Delete this Part\" href=\"index.php?path=part_controller/delete/" .$part->get_id() . "\" > Delete Part</a>				
				</div>

				<p>
					<b> Users: </b> ";

				
				$users = $this->data[ 'users' ];
				$tasks = $this->data[ 'tasks' ];

				foreach ( $users as $user )
					foreach ( $tasks as $task )
						if ( $task->get_user_id() == $user->get_id() and $task->get_part_id() == $part->get_id() )
						{
							echo "<a href=\"index.php?path=user_controller/view/" . $user->get_id() . "\">" . $user->get_firstname() . ' ' . $user->get_lastname() . "</a> / ";
						}

				echo "	<a class=\"add_part_href\" href=\"index.php?path=task_controller/create/part(@)" . $part->get_id() . "\"> Add Users </a>
				</p>";
			}

		?>		

		<br>

		<a class="add_part_href" title="Add Part" href=<?php echo "index.php?path=part_controller/create/" . $this->data[ 'id' ]; ?> > Add Part </a>

		<br>
		<br>

		<p title="Details"> <b>Details:</b> <pre><?php echo $this->data[ 'details' ]; ?></pre> </p>
		<p title="Client"> <b>Client:</b> <a href=<?php echo "index.php?path=user_controller/view/" . $this->data[ 'client' ]->get_id(); ?> > <?php echo $this->data[ 'client' ]->get_firstname() . ' ' . $this->data[ 'client' ]->get_lastname(); ?></a> / <a class="add_part_href" href=<?php echo "index.php?path=user_controller/email_client_on_progress/" . $this->data[ 'client' ]->get_id(); ?> > Email </a> </p>
		<p title="Contract Date"> <b>Contract Date:</b> <?php echo $this->data[ 'contract_date' ]; ?> </p>
		<p title="Deadline Date"> <b>Deadline Date:</b> <?php echo $this->data[ 'deadline_date' ]; ?> </p>
		<p title="Auxiliary data" style="margin: 10px 0px;"><span class="auxiliary_data" > ( <b>Id:</b> <?php echo $this->data[ 'id' ]; ?> ) </span></p>

		<br>

		

		<a title="Edit this contract" href=<?php echo "index.php?path=contract_controller/edit/" . $this->data[ 'id' ]; ?> > Edit contract</a> /  
		<a title="Delete this contract" href=<?php echo "index.php?path=contract_controller/delete/" . $this->data[ 'id' ]; ?> > Delete contract</a>


		<?php

		// Invalid

		// if ( $_SESSION[ 'type' ] == "admin" )
		// {
		// 	echo "
		// 		<a title=\"Edit this contract\" href=\"index.php?path=contract_controller/edit/" . $this->data[ 'id' ] . " > Edit contract</a> /  
		// 		<a title=\"Delete this contract\" href=\"index.php?path=contract_controller/delete/" . $this->data[ 'id' ] . "> Delete contract</a>";
		// }

		?>


		<br>


	</body>

</html>