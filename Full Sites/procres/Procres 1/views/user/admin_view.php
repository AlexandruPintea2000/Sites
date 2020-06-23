<!DOCTYPE html>

<html>

	<head>
		<title> Details </title>
		<link rel="stylesheet" href="/Procres/files/style.css">
		<meta name="viewport" content="width=device-width, initial-scale=1">
	</head>


	<body>


		<?php $this->show_nav(); ?>

		<div class="company"> <?php echo $this->data[ 'company' ]; ?> </div>
		<?php if ( $_SESSION[ 'type' ] != "admin" ) echo "<style> #left_nav { display: none !important; } html { padding-left: 10px !important; } </style>"; ?>
		<a class="signed_in_user" href=<?php echo "\"index.php?path=user_controller/view/" . $_SESSION[ 'id' ] . "\""; ?> > <?php echo $_SESSION[ 'username' ]; ?> </a>



		<div class="center" >

			<h1 style="font-size: 45px;"> Details </h1>

	    </div>

	
	    <p style="background-color: rgb( 243, 243, 243 ); padding: 10px; border-radius: 10px">
		    <a style="margin-left: 2.5%;" href="#admins"> Admins</a> /
		    <a href="#clients"> Clients</a> /
		    <a href="#employees"> Employees</a>
		</p>

	    <style>

	    	.user_center 
	    	{
	    		font-weight: bold;
	    		font-size: 25px;
	    		margin: 10px 0px;
	    		background-color: rgb( 204, 204, 204 );
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

		    $clients = $this->data[ 'clients' ];
		    $employees = $this->data[ 'employees' ];
		    $admins = $this->data[ 'admins' ];
		    $parts = $this->data[ 'parts' ];
		    $tasks = $this->data[ 'tasks' ];
		    $contracts = $this->data[ 'contracts' ];

	
			echo "<p style=\"margin-top: -54px; left: -500px; font-size: 40px; position: absolute;\" id=\"clients\" > span </p>";

		    echo "<p class=\"type_center\">Clients</p> 
		          <div class=\"type_div\">";

		    foreach ( $clients as $client )
		    {
		    	echo "<p class=\"user_center\">
		    	      	<a href=\"index.php?path=user_controller/view/" . $client->get_id() . "\">" . $client->get_firstname() . " " . $client->get_lastname() . "
		    	      	</a> 
		    	      </p>";


		    	$have_contracts = false;
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

	    			if ( $contract->get_client_id() == $client->get_id() )
		    			echo "</div> <br>";
	    		}

	    		if ( $have_contracts == false )
	    			echo "<p style=\"font-size: 25px;\">Contracts unavailable.</p>";

				echo "<a class=\"add_part_href\" href=\"index.php?path=contract_controller/create/\" > Add Contract</a> <br> <br>";
		    }

			echo "</div>";


			echo "<p style=\"margin-top: -54px; left: -500px; font-size: 40px; position: absolute;\" id=\"admins\" > span </p>";

		    echo "<p class=\"type_center\">Admins</p> <div class=\"type_div\">";


		    foreach ( $admins as $admin )
		    {
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
										<a href=\"index.php?path=part_controller/view/" . $part->get_id() . "\"> View Part </a>
									</div>

									";

		    				}

	    				}

	    			}
	    		}

    			if ( $have_contracts == false )
    			{
    				echo "<div class=\"center\">";
	    			echo "<p style=\"font-size: 25px; margin-top: 0px;\">Parts unavailable.</p>";

    				echo "<a class=\"add_part_href\" href=\"index.php?path_task_controller/create/user(@)" . $admin->get_id() . "\">Make responsable for a Part </a> </div>";
	   			}

   			echo "</div> <br>";

		    }

   			echo "</div> ";


			echo "<p style=\"margin-top: -54px; left: -500px; font-size: 40px; position: absolute;\" id=\"employees\" > span </p>";

		    echo "<p class=\"type_center\">Employees</p> <div class=\"type_div\">";


		    foreach ( $employees as $employee )
		    {
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
										<a href=\"index.php?path=part_controller/view/" . $part->get_id() . "\"> View Part </a>
									</div>

									";

		    				}

	    				}

	    			}
	    		}

    			if ( $have_contracts == false )
    			{
    				echo "<div class=\"center\">";
	    			echo "<p style=\"font-size: 25px; margin-top: 0px;\">Parts unavailable.</p>";

    				echo "<a class=\"add_part_href\" href=\"index.php?path_task_controller/create/user(@)" . $employee->get_id() . "\">Make responsable for a Part </a></div>";
	   			}

		    }

   			echo "</div> <br>";


		    ?>

		</div>
	
	</body>

</html>