<!DOCTYPE html>

<html>

	<head>
		<title> <?php echo $this->data[ 'title' ]; ?> </title>
		<link rel="stylesheet" href="/Procres/files/style.css">
		<meta name="viewport" content="width=device-width, initial-scale=1">
	</head>


	<body>

		

		<div class="company"> <?php echo $this->data[ 'company' ]; ?> </div>
		<?php if ( $_SESSION[ 'type' ] != "admin" ) echo "<style> #left_nav { display: none !important; } html { padding-left: 10px !important; } </style>"; ?>
		<a class="signed_in_user" href=<?php echo "\"index.php?path=user_controller/view/" . $_SESSION[ 'id' ] . "\""; ?> > <?php echo $_SESSION[ 'username' ]; ?> </a>


		<style>

			.title span
			{
				font-size: 49px; 
				font-weight: 500; 
				border-radius: 10px;
				border: 3px solid rgb( 239, 239, 239 );
				padding: 10px 21px !important;
				background-color: rgb( 243, 243, 243 );
			}


			.title
			{
				background-color: rgb( 234, 234, 234 );
			}

		</style>

		<div class="center" style="border: 3px solid rgb( 204, 204, 204 ); margin-top: 25px; padding: 10px 5px; border-radius: 10px;" >

			<h1 class="title"><span> All Contracts </span></h1>

			<br>


		    <p style="text-align: left; background-color: rgb( 243, 243, 243 ); padding: 10px; border-radius: 10px"> <b>Contracts:</b>

			<?php


			$contracts = $this->data[ 'contracts' ];

			foreach ( $contracts as $contract )
			{
				echo "<a href=\"#" . $contract->get_contract_name() . "	\">"  . $contract->get_contract_name() . "</a> / ";
			}

			?>

			</p>

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

			.add_part_href
			{
				background-color: rgb( 234, 234, 234 );
			}

			.add_part_href:hover
			{
				background-color: rgb( 243, 243, 243 );
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



		<?
 
		$users = $this->data[ 'users' ];
//		$parts = $this->data[ 'parts' ];
		$tasks = $this->data[ 'tasks' ];


		foreach ( $contracts as $contract )
		{

			$parts = get_parts_through_contract_id( $contract->get_id() );

			echo "<p style=\"margin-top: -54px; left: -500px; font-size: 40px; position: absolute;\" id=\"" . $contract->get_id() . "\" > span </p>";

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
				echo "<h4 style=\"font-size: 25px;\"> Contract done </h4>";

			if ( contract_obsolete( $contract->get_id() ) and contract_finalised( $contract->get_id() ) )
				echo "<h4 style=\"font-size: 25px;\"> Contract done and obsolete </h4>";


			if ( contract_final_month ( $contract->get_id() ) )
				echo "<h4> Final month of contract! </h4>";


			// echo "<br>";
			echo "<div style=\"height: 5px;\"></div>";



			// Contract Progress

			$completed_parts = 0;
			$contract_progress = 0;
			$parts_count = 0;
			foreach ( $parts as $part )
			{
				if ( $part->get_contract_id() != $contract->get_id() )
					continue;			

				if ( $part->get_progress() == 100 )
					$completed_parts = $completed_parts + 1;

				$contract_progress = $contract_progress + $part->get_progress();
				$parts_count = $parts_count + 1;
			}

			if ( $parts_count != 0 )
				$contract_progress = (int) ( $contract_progress / $parts_count );

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


			echo "<div class=\"center\"> <span> Parts completed: <b>" . $completed_parts . " / " . $parts_count . "</b></span> </div> <div style=\"height: 5px;\"></div>";


 		    $client = get_user_through_id( $contract->get_client_id() );

			echo "<h2 style=\"font-size: 20px; margin-top: 10px;\"> Client: <b style=\"margin-right: 10px;\"> <a href=\"index.php?path=user_controller/view/" . $client->get_id()	 . "\">" .  $client->get_firstname() . ' ' . $client->get_lastname() . "</a> <a class=\"add_part_href\" style=\"font-size: 17px; padding: 2px 10px;\" href=\"index.php?path=user_controller/view/" . $client->get_id()	 . "\"> Client View</a> </b> </h2>  <br>";


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


//			echo "<br>";

			foreach ( $parts as $part )
			{
				$have_users = false;

				if ( $part->get_contract_id() != $contract->get_id() )
					continue;

				echo "<p style=\"line-height: 30px; margin: 0px;\"> Responsable for <b>" . $part->get_part_name() . ": </b> ";

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
					echo "<b> ( Unavailable )</b>, make sure to: <a class=\"add_part_href\" href=\"index.php?path=task_controller/create/part(@)" . $part->get_id() . "\"> Add Users for part \"" . $part->get_part_name() .  "\" </a> <br>";
				else
					echo "<a class=\"add_part_href\" href=\"index.php?path=task_controller/create/part(@)" . $part->get_id() . "\"> Add Users for part \"" . $part->get_part_name() .  "\" </a> <br>";

				echo "</p>";
			}


			if ( count( $parts ) == 0 )
			{
				echo "<b style=\"";

//				echo " background-color: rgb( 234, 234, 234 ); border: 2px solid rgb( 150, 150, 150 ); border-radius: 10px; ";

				echo "padding: 10px 0px;;\">Please make sure to: ";
			}

			echo " <a class=\"add_part_href\" style=\"background-color: rgb( 243, 243, 243 ); border: 1px solid rgb( 204, 204, 204 ); margin-top: 10px; padding: 3px 10px;\" title=\"Add Part for Contract\" href=\"index.php?path=part_controller/create/" . $contract->get_id() . "\" > Add Part for Contract </a> ";

			if ( count( $parts ) == 0 )
				echo "! ( then, add users and part tasks to it )</b> ";
			else
				echo "<br>";


			if ( count( $parts ) != 0 )
			{

				foreach ( $parts as $part )
				{
					$have_users = false;

					if ( $part->get_contract_id() != $contract->get_id() )
						continue;

					foreach ( $users as $user )
					{
						foreach ( $tasks as $task )
							if ( $task->get_user_id() == $user->get_id() and $task->get_part_id() == $part->get_id() )
							{

								include_once "controllers/Task_controller.php";

								$given_part_tasks = get_part_tasks_through_task_id_and_status( $task->get_id(), "given" );
								$completed_part_tasks = get_part_tasks_through_task_id_and_status( $task->get_id(), "completed" );

								if ( $have_users == false )
								{
									if ( count( $given_part_tasks ) != 0 or count( $completed_part_tasks ) != 0 )
									{
										$have_users = true;
										echo "<br> <b> User tasks for part '" . $part->get_part_name() . "':  </b> <br> ";
									}
									else
										continue;
								}

								echo " <a href=\"index.php?path=user_controller/view/" . $user->get_id() . "\">" . $user->get_firstname() . ' ' . $user->get_lastname() . " </a> has tasks:  ";

								if ( count( $given_part_tasks ) != 0 )
								{
									$have_users = true;

									foreach ( $given_part_tasks as $given_part_task )
									{
										echo "<a class=\"add_part_href\" style=\"margin-left: 10px; background-color: rgb( 250, 250, 250 );\" href=\"index.php?path=task_controller/view_part_task/" . $task->get_id() . '(@)' . $given_part_task->get_part_task() . "\">" . $given_part_task->get_part_task() . "</a>";
									}
								}
								else
									echo " <b>( Empty )</b>, make sure to:";

								echo "<a class=\"add_part_href\" style=\"margin-left: 10px; padding: 2px 10px; background-color: rgb( 250, 250, 250 );\" href=\"index.php?path=task_controller/add_part_task/" . $task->get_id() . "\"> Add Task </a>";

								echo "<br> and has completed tasks: ";


								if ( count( $completed_part_tasks ) != 0 )
								{
									$have_users = true;

									foreach ( $completed_part_tasks as $completed_part_task )
									{
										echo "<a class=\"add_part_href\" style=\"margin-left: 10px; background-color: rgb( 204, 204, 204 );\" href=\"index.php?path=task_controller/view_part_task/" . $task->get_id() . '(@)' . $completed_part_task->get_part_task() . "\">" . $completed_part_task->get_part_task() . "</a>";
									}

									echo "<br> <br> ";
								}
								else
									echo " <b>( Empty )</b>, will be shown when completed.  ";

							}
			
					}

				}	
			}

			echo "<br>";

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