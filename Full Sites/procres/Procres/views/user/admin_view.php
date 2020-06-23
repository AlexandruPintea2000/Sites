<!DOCTYPE html>

<html>

	<head>
		<title> User Details </title>
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

			<h1 class="title"><span> User Details </span></h1>

			<br>

		    <p style=" background-color: rgb( 243, 243, 243 ); border: 3px solid rgb( 234, 234, 234 ); padding: 10px; border-radius: 10px">
			    <a href="#admins"> Admins</a> /
			    <a href="#clients"> Clients</a> /
			    <a href="#employees"> Employees</a>
			</p>



			<?php

		    $clients = $this->data[ 'clients' ];
		    $employees = $this->data[ 'employees' ];
		    $admins = $this->data[ 'admins' ];
		    $parts = $this->data[ 'parts' ];
		    $tasks = $this->data[ 'tasks' ];
		    $contracts = $this->data[ 'contracts' ];


			?>


		    <p style="text-align: left; background-color: rgb( 243, 243, 243 ); padding: 10px; border-radius: 10px"> <b>Clients:</b>

		    <?php

		    for ( $i = 0; $i < count( $clients ); $i = $i + 1 )
		    {
		    	$client = $clients[ $i ];
				echo "<a href=\"#" . $client->get_id() . "\" > " . $client->get_firstname() . ' ' . $client->get_lastname() . "</a>";

				if ( $i != count( $clients ) - 1 )
					echo ", ";
			}


			?>

			</p>



		    <p style="text-align: left; background-color: rgb( 243, 243, 243 ); padding: 10px; border-radius: 10px"> <b>Employees:</b>

		    <?php

		    for ( $i = 0; $i < count( $employees ); $i = $i + 1 )
		    {
		    	$employee = $employees[ $i ];
				echo "<a href=\"#" . $employee->get_id() . "\" > " . $employee->get_firstname() . ' ' . $employee->get_lastname() . "</a>";

				if ( $i != count( $employees ) - 1 )
					echo ", ";
			}


			?>

			</p>



		    <p style="text-align: left; background-color: rgb( 243, 243, 243 ); padding: 10px; border-radius: 10px"> <b>Admins:</b>

		    <?php

		    for ( $i = 0; $i < count( $admins ); $i = $i + 1 )
		    {
		    	$admin = $admins[ $i ];
				echo "<a href=\"#" . $admin->get_id() . "\" > " . $admin->get_firstname() . ' ' . $admin->get_lastname() . "</a>";

				if ( $i != count( $admins ) - 1 )
					echo ", ";
			}


			?>

			</p>




	    </div>

	    <style>

	    	.user_center 
	    	{
	    		font-weight: bold;
	    		font-size: 25px;
	    		margin: 10px 0px;
	    		background-color: rgb( 243, 243, 243 );
	    		padding: 15px;
	    		border-radius: 10px;
	    		border: 2px solid rgb( 123, 123, 123 );
	    	}

	    	.user_center a
	    	{	
	    		text-decoration: none;
	    	}


	    	.contract_div
	    	{
	    		text-align: left !important;
	    		margin-top: 20px;
	    	}

	    	.contract 
	    	{
	    		font-size: 20px;
	    		text-decoration: none;
	    		background-color: rgb( 234, 234, 234 );
	    		border-radius: 10px;
	    		padding: 10px;
	    		border: 3px solid rgb( 204, 204, 204 );
	    		padding-left: 45%;
	    	}

	    	.contract:hover
	    	{
	    		font-size: 20px;
	    		padding: 10px;
	    		background-color: rgb( 239, 239, 239 );
	    		padding-left: 45%;
	    	}

	    	.add_part_href
	    	{
	    		padding: 3px 10px !important;
	    	}

	    	.add_part_href:hover
	    	{
	    		padding: 3px 10px !important;
	    	}


	    	.type_center
	    	{
	    		font-size: 40px;
	    	}

	    	.type_div
	    	{
	    		border: 3px solid rgb( 234, 234, 234 );
	    		border-radius: 10px;
	    		padding: 5px;
	    		background-color: rgb( 239, 239, 239 );
	    	}


	    </style>


	    <div class="center">

		    <?php
	
			echo "<p style=\"margin-top: -54px; left: -500px; font-size: 40px; position: absolute;\" id=\"clients\" > span </p>";

		    echo "<p class=\"type_center\">Clients</p> 
		          <div class=\"type_div\">";

		    foreach ( $clients as $client )
		    {
				echo "<p style=\"margin-top: -54px; left: -500px; font-size: 40px; position: absolute;\" id=\"" . $client->get_id() . "\" > span </p>";


		    	echo "<p class=\"user_center\">
		    	      	<a href=\"index.php?path=user_controller/view/" . $client->get_id() . "\">" . $client->get_firstname() . " " . $client->get_lastname() . "
		    	      	</a> 
		    	      </p>";


		    	$have_contracts = false;
		    	$have_parts = false;
	    		foreach ( $contracts as $contract )
	    		{
	    			if ( $contract->get_client_id() != $client->get_id() )
	    				continue;

	    			$have_contracts = true;

	    			echo "<div class=\"contract_div\"> 
	    				  <a class=\"contract\" href=\"index.php?path=contract_controller/view/" . $contract->get_id() . "\">" . $contract->get_contract_name() . "</a>";

    				echo "<br> <br>";

	    			foreach ( $parts as $part )
	    			{
	    				if ( $part->get_contract_id() != $contract->get_id() )
	    					continue;

				    	$have_parts = true;

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

						";


						$have_part_user = false;
	    				foreach ( $tasks as $task )
	    				{
	    					if ( $task->get_part_id() != $part->get_id() )
	    						continue;

	    					if ( $have_part_user == false )
	    					{
		    					$have_part_user = true;
		    					echo "Responsables: ";
		    				}

	    					$part_user = get_user_through_id( $task->get_user_id() );

	    					echo "<a href=\"index.php?path=user_controller/view/" . $part_user->get_id() . "\">" . $part_user->get_firstname() . " " . $part_user->get_lastname() . "</a> / ";
	    				}


    					if ( $have_part_user == false )
    						echo "Responsables not added.";


						echo "<a class=\"add_part_href\" href=\"index.php?path=task_controller/create/part(@)" . $part->get_id() . "\"> Add Users </a>";


		    			echo "<br> <br>";
	    			}


	    			if ( $have_parts )
	    				echo "If you consider:";
	    			else
	    				echo "<b>Please make sure to:</b>";

					echo " <a class=\"add_part_href\" style=\"margin-left: 10px;\" href=\"index.php?path=part_controller/create/" . $contract->get_id() . "\"> Add Part to Contract </a> <br>";

	    			if ( $contract->get_client_id() == $client->get_id() )
		    			echo "</div> <br>";
	    		}

	    		if ( $have_contracts == false )
	    			echo "<p style=\"font-size: 25px;\">Contracts unavailable.</p>";

				echo "<a class=\"add_part_href\" href=\"index.php?path=contract_controller/create/". $client->get_id() ."\" > Add Contract</a> <br> <br>";
		    }

			echo "</div>";










			echo "<p style=\"margin-top: -54px; left: -500px; font-size: 40px; position: absolute;\" id=\"admins\" > span </p>";

		    echo "<p class=\"type_center\">Admins</p> <div class=\"type_div\">";


		    foreach ( $admins as $admin )
		    {
				echo "<p style=\"margin-top: -54px; left: -500px; font-size: 40px; position: absolute;\" id=\"" . $admin->get_id() . "\" > span </p>";


		    	echo "<p class=\"user_center\"><a href=\"index.php?path=user_controller/view/" . $admin->get_id() . "\">" . $admin->get_firstname() . " " . $admin->get_lastname() . "</a> </p> <div class=\"contract_div\">";

		    	$have_contracts = false;
	    		foreach ( $contracts as $contract )
	    		{
	    			foreach ( $parts as $part )
	    			{
	    				if ( $part->get_contract_id() != $contract->get_id() )
	    					continue;


						$have_part_user = false;
	    				foreach ( $tasks as $task )
	    				{			
	    					if ( $task->get_user_id() != $admin->get_id() )
	    						continue;

	    					if ( $task->get_part_id() != $part->get_id() )
	    						continue;


	    					if ( $have_part_user == false )
	    					{
		    					$have_part_user = true;

						    	$have_contracts = true;

		    					echo "
									<div class=\"progress_div\"> 
										<b title=\"Progress\">( " . $contract->get_contract_name() . " ) - " . $part->get_part_name() . ": 
										</b>
										
										<div class=\"behind_progress\">
											<div class=\"progress\" style=\"width: " . $part->get_progress() . "%;\" > 
												<span> " . $part->get_progress() . "% </span> 
											</div> 
										</div> 
										<span> / 100% </span> 
										<a style=\"background-color: rgb( 250, 250, 250 );\" href=\"index.php?path=task_controller/add_part_task/" . $task->get_id() . "\"> Add Part Task </a>
										<a style=\"background-color: rgb( 250, 250, 250 );\" href=\"index.php?path=part_controller/view/" . $part->get_id() . "\"> View Part </a>
									</div>

									";


								include_once "controllers/Task_controller.php";

								$given_part_tasks = get_part_tasks_through_task_id_and_status( $task->get_id(), "given" );


								if ( count( $given_part_tasks ) != 0 )
								{
									echo "<div style=\"height: 5px;\"></div>";
									echo "<b>Given Part tasks:</b>";
								}
								else 
									echo " <b>Make sure to <a style=\"color: rgb( 90, 90, 90 );\" href=\"index.php?path=task_controller/add_part_task/" . $task->get_id() . "\">add part tasks</a>, </b>";

								foreach ( $given_part_tasks as $given_part_task )
								{
									echo "<a href=\"index.php?path=task_controller/view_part_task/" . $task->get_id() . '(@)' . $given_part_task->get_part_task() . "\" class=\"add_part_href\" style=\"margin-left: 10px; background-color: rgb( 250, 250, 250 ) !important;\" >" . $given_part_task->get_part_task() . "</a>";
								}

								if ( count( $given_part_tasks ) != 0 )
									echo "<div style=\"height: 10px;\"></div>";




								$completed_part_tasks = get_part_tasks_through_task_id_and_status( $task->get_id(), "completed" );


								if ( count( $completed_part_tasks ) != 0 )
								{
									echo "<div style=\"height: 5px;\"></div>";
									echo "<b>Completed Part tasks:</b>";
								}
								else
									echo "User has not completed any part tasks yet.";

								foreach ( $completed_part_tasks as $completed_part_task )
								{
									echo "<a href=\"index.php?path=task_controller/view_part_task/" . $task->get_id() . '(@)' . $completed_part_task->get_part_task() . "\" class=\"add_part_href\" style=\"margin-left: 10px; background-color: rgb( 204, 204, 204 ) !important;\" >" . $completed_part_task->get_part_task() . "</a>";
								}

								echo "<div style=\"height: 10px;\"></div>";

		    				}

	    				}

	    			}
	    		}

    			if ( $have_contracts == false )
    			{
    				echo "<div class=\"center\">";
	    			echo "<p style=\"font-size: 25px; margin-top: 0px;\">Parts unavailable.</p>";

    				echo "<a class=\"add_part_href\" href=\"index.php?index.php?path=task_controller/create/user(@)" . $admin->get_id() . "\">Make responsable for a Part </a> </div>";
	   			}
	   			else
    			{
    				echo "If you consider: <a style=\"background-color: rgb( 214, 214, 214 );\" class=\"add_part_href\" href=\"index.php?path=task_controller/create/user(@)" . $admin->get_id() . "\">Make user responsable for another Part </a>";
	   			}

   			echo "</div> <br>";

		    }

   			echo "</div> ";
















			echo "<p style=\"margin-top: -54px; left: -500px; font-size: 40px; position: absolute;\" id=\"employees\" > span </p>";

		    echo "<p class=\"type_center\">Employees</p> <div class=\"type_div\">";

		    foreach ( $employees as $employee )
		    {
				echo "<p style=\"margin-top: -54px; left: -500px; font-size: 40px; position: absolute;\" id=\"" . $employee->get_id() . "\" > span </p>";


		    	echo "<p class=\"user_center\"><a href=\"index.php?path=user_controller/view/" . $employee->get_id() . "\">" . $employee->get_firstname() . " " . $employee->get_lastname() . "</a> </p> <div class=\"contract_div\">";

		    	$have_contracts = false;
	    		foreach ( $contracts as $contract )
	    		{
	    			foreach ( $parts as $part )
	    			{
	    				if ( $part->get_contract_id() != $contract->get_id() )
	    					continue;


						$have_part_user = false;
	    				foreach ( $tasks as $task )
	    				{			
	    					if ( $task->get_user_id() != $employee->get_id() )
	    						continue;

	    					if ( $task->get_part_id() != $part->get_id() )
	    						continue;


	    					if ( $have_part_user == false )
	    					{
		    					$have_part_user = true;

						    	$have_contracts = true;

		    					echo "
									<div class=\"progress_div\"> 
										<b title=\"Progress\">( " . $contract->get_contract_name() . " ) - " . $part->get_part_name() . ": 
										</b>
										
										<div class=\"behind_progress\">
											<div class=\"progress\" style=\"width: " . $part->get_progress() . "%;\" > 
												<span> " . $part->get_progress() . "% </span> 
											</div> 
										</div> 
										<span> / 100% </span> 
										<a style=\"background-color: rgb( 250, 250, 250 );\" href=\"index.php?path=task_controller/add_part_task/" . $task->get_id() . "\"> Add Part Task </a>
										<a style=\"background-color: rgb( 250, 250, 250 );\" href=\"index.php?path=part_controller/view/" . $part->get_id() . "\"> View Part </a>
									</div>

									";


								include_once "controllers/Task_controller.php";

								$given_part_tasks = get_part_tasks_through_task_id_and_status( $task->get_id(), "given" );


								if ( count( $given_part_tasks ) != 0 )
								{
									echo "<div style=\"height: 5px;\"></div>";
									echo "<b>Given Part tasks:</b>";
								}
								else 
									echo " <b>Make sure to <a style=\"color: rgb( 90, 90, 90 );\" href=\"index.php?path=task_controller/add_part_task/" . $task->get_id() . "\">add part tasks</a>, </b>";

								foreach ( $given_part_tasks as $given_part_task )
								{
									echo "<a href=\"index.php?path=task_controller/view_part_task/" . $task->get_id() . '(@)' . $given_part_task->get_part_task() . "\" class=\"add_part_href\" style=\"margin-left: 10px; background-color: rgb( 250, 250, 250 ) !important;\" >" . $given_part_task->get_part_task() . "</a>";
								}

								if ( count( $given_part_tasks ) != 0 )
									echo "<div style=\"height: 10px;\"></div>";




								$completed_part_tasks = get_part_tasks_through_task_id_and_status( $task->get_id(), "completed" );


								if ( count( $completed_part_tasks ) != 0 )
								{
									echo "<div style=\"height: 5px;\"></div>";
									echo "<b>Completed Part tasks:</b>";
								}
								else
									echo "User has not completed any part tasks yet.";

								foreach ( $completed_part_tasks as $completed_part_task )
								{
									echo "<a href=\"index.php?path=task_controller/view_part_task/" . $task->get_id() . '(@)' . $completed_part_task->get_part_task() . "\" class=\"add_part_href\" style=\"margin-left: 10px; background-color: rgb( 204, 204, 204 ) !important;\" >" . $completed_part_task->get_part_task() . "</a>";
								}

								echo "<div style=\"height: 10px;\"></div>";

		    				}

	    				}

	    			}
	    		}

    			if ( $have_contracts == false )
    			{
    				echo "<div class=\"center\">";
	    			echo "<p style=\"font-size: 25px; margin-top: 0px;\">Parts unavailable.</p>";

    				echo "<a class=\"add_part_href\" href=\"index.php?path=task_controller/create/user(@)" . $employee->get_id() . "\">Make responsable for a Part </a> </div>";
	   			}
	   			else
    			{
    				echo "If you consider: <a style=\"background-color: rgb( 214, 214, 214 );\" class=\"add_part_href\" href=\"index.php?path=task_controller/create/user(@)" . $employee->get_id() . "\">Make user responsable for another Part </a>";
	   			}

   			echo "</div> <br>";

		    }

   			echo "</div> ";

		    ?>

		</div>
	
	</body>

</html>