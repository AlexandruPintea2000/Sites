<!DOCTYPE html>

<html>

	<?

	$contract_name = $this->data[ 'contract_name' ];

	if ( ! isset( $_SESSION[ $contract_name ] ) )
		$_SESSION[ $contract_name ] = true;

	?>



	<head>
		<title> <?php echo $this->data[ 'contract_name' ]; ?> </title>
		<link rel="stylesheet" href="/Procres/files/style.css">
		<meta name="viewport" content="width=device-width, initial-scale=1">
	</head>


	<style>
		
		h3 
		{
			padding: 2px;
			margin: 0px;
		}

	</style>


	<body>

		

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
					echo "<h3> Contract is done. <br> <span style=\"font-weight: 500; color: rgb( 123, 123, 123 ); font-size: 14px;\"> Or not? <a href=\"index.php?path=contract_controller/parts/" . $this->data[ 'id' ] . "\"> Edit its parts</a> so that they are not all 100%. </span> </h3>";

				if ( ! contract_finalised ( $this->data[ 'id' ] ) and contract_obsolete( $this->data[ 'id' ] ) )
					echo "<h3> Contract is obsolete. <br> <span style=\"font-weight: 500; color: rgb( 123, 123, 123 ); font-size: 14px;\"> Or not? <a href=\"index.php?path=contract_controller/edit/" . $this->data[ 'id' ] . "\"> Edit its deadline</a> / <a href=\"index.php?path=contract_controller/add_month_to_deadline/" . $this->data[ 'id' ] . "\">Add 1 month</a> </span> </span> </h3>";

				if ( contract_finalised ( $this->data[ 'id' ] ) and contract_obsolete( $this->data[ 'id' ] ) )
					echo "<h3> Contract is done and obsolete. <br> <span style=\"font-weight: 500; color: rgb( 123, 123, 123 ); font-size: 14px;\"> Or not done? <a href=\"index.php?path=contract_controller/parts/" . $this->data[ 'id' ] . "\"> Edit its parts</a> so that they are not all 100%.  </span> <br> <span style=\"font-weight: 500; color: rgb( 123, 123, 123 ); font-size: 14px;\"> Or not obsolete? <a href=\"index.php?path=contract_controller/edit/" . $this->data[ 'id' ] . "\"> Edit its deadline</a> / <a href=\"index.php?path=contract_controller/add_month_to_deadline/" . $this->data[ 'id' ] . "\">Add 1 month</a> </span> </h3>";


				echo "<style> html{ background-color: rgb( 234, 234, 234 ); } </style>";

				echo "<div class=\"div_center\">";
				if ( $_SESSION[ 'type' ] == "admin" )
					echo "<h2 class=\"delete_border\" style=\"width: 40%; font-size: 15px; text-align: center;\"> <a href=\"index.php?path=contract_controller/delete/" . $this->data[ 'id' ] . "\">Delete the entire contract</a>, not each  part on its own. <div style=\"height: 5px;\"></div>  </h2>";
				else
					echo "<h2 class=\"delete_border\" style=\"width: 40%;\"> Please tell an admin to delete contract. </h2>";
				echo "</div>";
			}

			if ( contract_final_month( $this->data[ 'id' ] ) )
			{
				echo "<h2> Final month of Contract! <br> <span style=\"font-weight: 500; color: rgb( 123, 123, 123 ); font-size: 14px;\"> Or not? <a href=\"index.php?path=contract_controller/edit/" . $this->data[ 'id' ] . "\"> Edit its deadline</a>. </span> </h2>";
			}




			// Contract Progress

			$parts = $this->data[ 'parts' ];

			$completed_parts = 0;
			$contract_progress = 0;
			foreach ( $parts as $part )
			{
				if ( $part->get_progress() == 100 )
					$completed_parts = $completed_parts + 1;

				$contract_progress = $contract_progress + $part->get_progress();
			}

			if ( count( $parts ) > 0 )
				$contract_progress = (int) ( $contract_progress / count( $parts ) );

			echo "
			<div class=\"progress_center\">
				<div class=\"progress_div\"> 
					<div class=\"behind_progress\">
						<div class=\"progress\" style=\"width: " . $contract_progress . "%;\" > 
							<span> " . $contract_progress . "% </span> 
						</div> 
					</div> 
					<span> / 100% </span>			
				</div>
			</div>";


			echo "<span style=\"text-align-center;\"> Parts completed: <b>" . $completed_parts . " / " . count( $parts ) . "</b></span> ";


			

			?>

	    </div>

	    <br>


	    <div class="div_center" style="background-color: rgb( 234, 234, 234 ); border-radius: 10px;">
	    <div class="user_details" >

<!-- 			<p title="Contract name"> <b>Contract name:</b>  <?php echo ucfirst( $this->data[ 'contract_name' ] ); ?> </p>
 -->

			<p title="Contract Details"> <b>For Client:</b> <a href=<?php echo "index.php?path=user_controller/view/" . $this->data[ 'client' ]->get_id(); ?> > <?php echo $this->data[ 'client' ]->get_firstname() . ' ' . $this->data[ 'client' ]->get_lastname(); ?></a> / <a class="add_part_href" href=<?php echo "index.php?path=user_controller/email_client_on_progress/" . $this->data[ 'client' ]->get_id(); ?> > Email</a>  
			( <b><?php echo $this->data[ 'contract_date' ]; ?></b> - until <b><?php echo $this->data[ 'deadline_date' ]; ?></b> ) 
			<span class="auxiliary_data" > ( <b>Id:</b> <?php echo $this->data[ 'id' ]; ?> ) </span></p>

		</div>
		</div>

		<br>

		<?php


		foreach ( $parts as $part )
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
		<a class="add_part_href" title="Add Part" href=<?php echo "index.php?path=part_controller/create/" . $this->data[ 'id' ]; ?> > Add Part for Contract </a>

		<br>
		<br>
		<br>

	    <div class="div_center" style="background-color: rgb( 243, 243, 243 ); border-radius: 10px;">
	    <div class="user_details">

 			<p title="Details" style="text-align: center;"> <span style="font-size: 30px; text-align: center;"> Contract Details <?php

	 			if ( $this->data[ 'details' ] == "" )
	 				echo "<br><span style=\"font-size: 15px; color: rgb( 123, 123, 123 ); text-align: center;\"> <br><b> Contract does not have details.</b></span>";

 			 	?></span><pre><?php

 				if ( $this->data[ 'details' ] != "" )
	 			 	echo $this->data[ 'details' ]; 
	 
 			 	?></pre></p>

 		</div>
 		</div>

		<br>
		<br>

		<div class="div_center">		
			<div class="user_options">

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

			</div>
		</div>

		<br>


	</body>

</html>