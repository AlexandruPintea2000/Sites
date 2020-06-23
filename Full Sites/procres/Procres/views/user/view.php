<!DOCTYPE html>

<html>

	<head>
		<title> <?php echo $this->data[ 'title' ]; ?> </title>
		<link rel="stylesheet" href="/Procres/files/style.css">
		<meta name="viewport" content="width=device-width, initial-scale=1">
	</head>

	
	<?php



	$users = $this->data[ 'users' ];
	$contracts = $this->data[ 'contracts' ];
	$parts = $this->data[ 'parts' ];
	$tasks = $this->data[ 'tasks' ];


	include_once "controllers/Task_controller.php";

	$have_tasks_for_completed = false;
	foreach ( $tasks as $task )
	{
		$part = get_part_through_id( $task->get_part_id() );

		if ( $part == null )
			continue;

		$given_part_tasks = get_part_tasks_through_task_id_and_status( $task->get_id(), "given" );


		if ( $part->get_progress() == 100 and count( $given_part_tasks ) != 0 and ! isset( $_SESSION[ $this->data[ 'username' ] ] ) )
		{
			$_SESSION[ $this->data[ 'username' ] ] = true;

			$have_tasks_for_completed = true;

			$user_name = "User has";
			if ( $_SESSION[ 'id' ] == $this->data[ 'id' ] )
				$user_name = "You have";

			if( contract_finalised( $part->get_contract_id() ) )
			{
				$alert = "<h4>" . $user_name . " tasks for at least a finalised contract!";

				if ( $_SESSION[ 'type' ] == "admin" )
					$alert = $alert . "<br> also, <br> <br> <a id=\"view_part_tasks\" class=\"add_part_href\" href=\"index.php?path=user_controller/part_tasks\" style=\"font-weight: 500 !important; padding: 7px 15px; border: 2px solid rgb( 123, 123, 123 ); background-color: rgb( 243, 243, 243 );\"> View Company Part tasks </a> <br> <br> to make sure everything is all right. <br> <br>";

				$alert = $alert . "</h4>";

				$this->alert( $alert );
			}
			else
			{
				$alert = "<h4>" . $user_name . " tasks for at least a completed part!";

				if ( $_SESSION[ 'type' ] == "admin" )
					$alert = $alert . "<br> also, <br> <br> <a class=\"add_part_href\" href=\"index.php?path=user_controller/part_tasks\" style=\"font-weight: 500 !important; padding: 7px 15px; border: 2px solid rgb( 123, 123, 123 ); background-color: rgb( 243, 243, 243 );\"> View Company Part tasks </a> <br> <br> to make sure everything is all right. <br> <br>";

				$alert = $alert . "</h4>";

				$this->alert( $alert );				
			}

			break;
		}
	}



	$have_obsolete_finalised_contracts = false;
	foreach ( $contracts as $contract )
	{
		if ( contract_obsolete( $contract->get_id() ) or contract_finalised( $contract->get_id() ) )
		{
			$have_obsolete_finalised_contracts = true;
			break;
		}
	}

	if ( $have_tasks_for_completed == false and $have_obsolete_finalised_contracts = true and $_SESSION[ 'type' ] == "admin" and ! isset( $_SESSION[ $this->data[ 'username' ] ] ) and $_SESSION[ 'id' ] == $this->data[ 'id' ] )
	{
		$_SESSION[ $this->data[ 'username' ] ] = true;

		$this->alert( "<h4>Please delete Obsolete / Finalised Contracts. <br> also, <br> <br> <a class=\"add_part_href\" href=\"index.php?path=user_controller/part_tasks\" style=\"font-weight: 500 !important; padding: 7px 15px; border: 2px solid rgb( 123, 123, 123 ); background-color: rgb( 243, 243, 243 );\"> View Company Part tasks </a> <br> <br> to make sure everything is all right. <br> <br></h4>" );	
	}

	?>


	<style>		

		h3
		{
			font-weight: 500;
			font-family: Sans;
			font-size: 30px;
			margin: 0px;					
		}

		h4
		{
			font-size: 20px;
			text-align: center;
			margin: 0px;
		}


		.behind_progress
		{
			max-width: 170px;
		}

		.admin_contract
		{
			border: 2px solid rgb( 204, 204, 204 );
			border-width: 0px 0px 2px 1px;
			padding: 5px 10px;
			border-radius: 10px;
		}

		.contract_div
		{
			border: 3px solid black;
			margin: 20px 0px;
			background-color: rgb( 234, 234, 234 );
			padding: 20px;
			border: 1px solid rgb( 159, 159, 159 );
			border-right: 15px solid rgb( 159, 159, 159 );
			border-radius: 40px 20px 70px 40px;
		}

	</style>


	<body>

		<div class="company"> <?php echo $this->data[ 'company' ]; ?> </div>
		<?php if ( $_SESSION[ 'type' ] != "admin" ) echo "<style> #left_nav { display: none !important; } html { padding-left: 10px !important; } .admin_contracts { display: none; } </style>"; ?>
		<a class="signed_in_user" href=<?php echo "\"index.php?path=user_controller/view/" . $_SESSION[ 'id' ] . "\""; ?> > <?php echo $_SESSION[ 'username' ]; ?> </a>


		<div class="center" >

			<div style="height: 10px;" ></div>

			<h1> <?php echo $this->data[ 'title' ]; ?> </h1>
			<?php if ( $_SESSION[ 'id' ] == $this->data[ 'id' ] ) echo "<h3>Welcome!</h3>"; ?>
			<?php // if ( $_SESSION[ 'id' ] == $this->data[ 'id' ] ) echo "<h3>Welcome " . $this->data[ 'firstname' ] . ' ' . $this->data[ 'lastname' ] "!</h3>"; ?>

			<?php 

			if ( $_SESSION[ 'type' ] == "admin" and $this->data[ 'id' ] == $_SESSION[ 'id' ] )
			{
				echo "<br> <h4 id=\"view_part_tasks\" style=\"position: absolute; right: 20px;\"> <a class=\"add_part_href\" href=\"index.php?path=user_controller/part_tasks\" style=\"font-weight: 500 !important; padding: 7px 15px; background-color: rgb( 234, 234, 234 );\"> View Company Part tasks </a> </h4>  ";
			}


			?>

	    </div>





	    <div class="div_center" style="background-color: rgb( 234, 234, 234 ); border-radius: 10px;">
	    <div class="user_details">

		<p title="Username"> <b>Username:</b>  

		<?php 

		echo $this->data[ 'username' ]; 

		if ( $this->data[ 'id' ] != $_SESSION[ 'id' ] )
		{
			if ( $this->data[ 'type' ] == "client" )
				echo " / <a title=\"Email client on progress\" class=\"add_part_href\" href=\"index.php?path=user_controller/email_client_on_progress/" . $this->data[ 'id' ] . "\" > Email </a>";
			else
				echo " / <a title=\"Email user\" class=\"add_part_href\" href=\"index.php?path=user_controller/email/" . $this->data[ 'id' ] . "\" > Email </a>";
		}

		?>

		</p> 

		<p title="Firstname"> <b>Firstname:</b> <?php echo $this->data[ 'firstname' ]; ?> </p> 
		<p title="Lastname"> <b>Lastname:</b> <?php echo $this->data[ 'lastname' ]; ?> </p> 
		<span title="Email"> <b>Email:</b> <?php echo $this->data[ 'email' ]; ?> </span> /


		<b>Id:</b> <?php echo $this->data[ 'id' ]; ?> / 
		<b>Type:</b> <?php echo $this->data[ 'type' ]; ?> ) </span></p>


		</div>
		</div>


		<?php

			if ( $_SESSION[ 'type' ] == "admin" and $this->data[ 'id' ] == $_SESSION[ 'id' ] )
			{
				echo "<span id=\"th_parts\">";

				echo "<a title=\"Users\" href=\"index.php?path=user_controller/index\" > Users</a> / ";
				echo "<a title=\"All Users ( Details )\" href=\"index.php?path=user_controller/admin_view\" > All Users ( Details )</a> / ";
				echo "<a title=\"All Contracts ( Details )\" href=\"index.php?path=contract_controller/view_contracts\" > All Contracts ( Details )</a> / ";

				if ( $have_obsolete_finalised_contracts )
					echo "<a title=\"Obsolete / Finalised\" href=\"index.php?path=contract_controller/index/Obsolete\" > Obsolete or Finalised Contracts</a> / ";

				echo "</span> <br>";
			



				$deleted_contracts = get_deleted_contracts();


				$config_data_loaded = false;
				foreach ( $users as $user )
					if ( $user->get_id() < -1 )
					{
						$config_data_loaded = true;
						break;
					}
				if ( ( count( $users ) == 1 and
					 count( $contracts ) == 0 and 
					 count( $parts ) == 0 and
					 count( $deleted_contracts ) == 0 and
					 count( $tasks ) == 0 ) or $config_data_loaded == true )
				{
					echo "<br> <br> <div style=\"background-color: rgb( 243, 243, 243 ); padding: 10px; border: 3px solid rgb( 234, 234, 234 ); border-radius: 10px;\"> <h4> Welcome " . $this->data[ 'username' ] . "! \"Config Data\" was loaded! </h4> <div style=\"height: 15px;\"></div> ";

					// Load Config Data


					// echo "<a class=\"add_part_href\" style=\"border: 2px solid rgb( 123, 123, 123 ); background-color: rgb( 234, 234, 234 ) !important; padding: 2px 10px;\" title=\"Load Config Data\" href=\"index.php?path=config_controller/load_config_data\" > Load Config Data </a> / ";
					echo "<div class=\"center\"><a title=\"Delete Config Data\" class=\"add_part_href\" style=\"border: 2px solid rgb( 123, 123, 123 ); background-color: rgb( 234, 234, 234 ) !important; padding: 2px 10px;\" href=\"index.php?path=config_controller/delete_config_data\" > Delete Config Data </a></div> <div style=\"height: 5px;\"> </div> <b style=\"color: rgb( 123, 123, 123 );\"> <br> Config data is <b style=\"color: rgb( 90, 90, 90 );\">users</b>, <b style=\"color: rgb( 90, 90, 90 );\">contracts</b>, <b style=\"color: rgb( 90, 90, 90 );\">parts</b> and <b style=\"color: rgb( 90, 90, 90 );\">tasks</b> that do not have any meaning but to get you around! <div style=\"height: 10px;\"> </div> If you did load it and you have data of your own, you should Delete Config Data ( deleting it will not affect your data ). <div style=\"height: 10px;\"> </div> You should also Delete Config Data when you are done getting around. <div style=\"height: 10px;\"> </div> <b style=\"color: rgb( 90, 90, 90 );\">Do not edit config data to make it your own, if you want you are able to make your: users, contracts, parts and tasks. </b> <div style=\"height: 10px;\"> </div> Config Data is only used when you made your admin user in configuration. </b> </div> ";

					
				}

			}

		?>


		<p> Details available in this <a href="views/index.html"> index</a>. </p>

<!-- 		<br>
 -->
		<?php

		if ( $this->data[ 'type' ] != "client" )
		{

			echo "<b>Parts assigned to ";

			if ( $_SESSION[ 'id' ] == $this->data[ 'id' ] )
				echo " you: </b> <br> <br>";
			else
				echo $this->data[ 'firstname' ] . ' ' . $this->data[ 'lastname' ] . ":</b>";


			$user_has_tasks = false;
			foreach ( $tasks as $task )
				foreach ( $parts as $part )
					if ( $part->get_id() == $task->get_part_id() and $this->data[ 'id' ] == $task->get_user_id() )
					{
						$contract;

						foreach ( $contracts as $i )
							if ( $i->get_id() == $part->get_contract_id() )
							{
								$contract = $i;
								break;
							}

						if ( $user_has_tasks == false )
						{
							if ( $_SESSION[ 'id' ] != $this->data[ 'id' ] )
								echo "<br> <br>";
							$user_has_tasks = true;
						}

						echo " <div class=\"parts\" ";

						if ( contract_finalised( $contract->get_id() ) )
							echo "style=\"background-color: rgb( 204, 204, 204 ); padding: 0px 0px 10px 10px !important; font-size: 14px; display: flex;\"";
						else
						{
							if ( $part->get_progress() == 100 )
								echo "style=\"background-color: rgb( 214, 214, 214 ); padding: 0px 0px 10px 10px !important; font-size: 14px; display: flex;\"";

							if ( contract_obsolete( $contract->get_id() ) )
								echo "style=\"background-color: rgb( 214, 214, 214 );\"";

						}


						echo " >
						<div title=\"Progress\" class=\"progress_div\"> 
							<b title=\"Progress\"> " . $contract->get_contract_name() . ' - '  . $part->get_part_name() . ": </b>";

						if ( $part->get_progress() != 100 )
						{
							echo "
								<div class=\"behind_progress\"";

							if ( contract_final_month( $part->get_contract_id() ) )
								echo " style=\"background-color: rgb( 204, 204, 204 );\" ";


							echo ">
									<div class=\"progress\" style=\"width: " . $part->get_progress() . "%; ";

							if ( contract_final_month( $part->get_contract_id() ) )
								echo "background-color: rgb( 243, 243, 243 );";


							if ( contract_obsolete( $part->get_contract_id() ) )
								echo "background-color: rgb( 204, 204, 204 );";

							echo " \" > 
										<span> " . $part->get_progress() . "%</span> 
									</div>  ";


							if ( contract_obsolete( $part->get_contract_id() ) )				echo "<b class=\"contract_obsolete\">  ( Contract Obsolete ) </b>";

							if ( contract_final_month( $part->get_contract_id() ) )				echo "<b class=\"contract_obsolete\">  ( Final Month! ) </b>";


							echo" 
								</div> 
								<span> / 100% </span>
								<a title=\"View Part\" href=\"index.php?path=part_controller/view/" .$part->get_id() . "\" > View Part</a>";


							if ( $_SESSION[ 'type' ] == "admin" )
								if ( contract_obsolete( $part->get_contract_id() ) or contract_finalised( $part->get_contract_id() ) )
									echo "<a href=\"index.php?path=contract_controller/delete/" . $part->get_contract_id() . "\"> Delete Contract </a> </div>";
								else
								{
									if ( ! contract_final_month( $part->get_contract_id() ) )	
										echo "
											<b title=\"As of Contract Deadline date\"> <b></b> <span style=\"margin: 0px; font-weight: 500;\">until</span> " . $contract->get_deadline_date() . "</b>";
									else
										echo "<b title=\"Final Month of Contract\"> <b></b> <b style=\"font-size: 10px;\"> until </b> <b style=\"margin: 0px 0px 0px -10px;\"> " . get_day( $contract->get_deadline_date() ) . "</b> <b style=\"font-size: 10px;\"> this month </b> </b>";

									echo "</div>";

									if ( contract_final_month( $part->get_contract_id() ) )		echo "<b style=\"font-size: 20px;\" title=\"Final Month of Contract\"> <b></b> until " . get_day( $contract->get_deadline_date() ) . " this month </b> /";

								}
						}
						else
						{


							echo "<span style=\"margin: 0px; font-weight: bold;\">( Completed! )</span>";


							if ( contract_finalised( $part->get_contract_id() ) )
								echo "<span style=\"margin: 0px; font-size: 14px !important;\"> <b></b>  <b style=\"color: rgb( 50, 50, 50 );\">Finalised Contract:</b> <a style=\"margin: 0px; padding: 2px 10px; background-color: rgb( 234, 234, 234 );\" href=\"index.php?path=contract_controller/delete/" . $part->get_contract_id() . "\">Delete Contract</a></span>";

							if ( contract_final_month( $part->get_contract_id() ) and
								 ! contract_finalised( $part->get_contract_id() ) )
								echo "<span style=\"margin: 0px;\"> <b></b> <b> Final Month!</b></span>";

							echo "</div>";

						}





						include_once "controllers/Task_controller.php";

						$given_part_tasks = get_part_tasks_through_task_id_and_status( $task->get_id(), "given" );

						if ( count( $given_part_tasks ) != 0 and
								 ! contract_finalised( $part->get_contract_id() ) )
						{
							if ( $part ->get_progress() == 100 )
							{
								echo " <b style=\"margin: 10px 0px 0px 10px; color: rgb( 50, 50, 50 );\"> Complete <a style=\"color: rgb( 50, 50, 50 );\" href=\"index.php?path=task_controller/view/" . $task->get_id() . "\">	 tasks for this part</a>! </b> ";
							}
							else
								echo " <b style=\"margin: 10px 0px 0px 10px; color: rgb( 90, 90, 90 );\"> Part tasks: </b> ";								
						}
						

						if ( contract_obsolete( $contract->get_id() ) and 
							! contract_finalised( $contract->get_id() ) and
							$part ->get_progress() != 100 )
							echo " <b>( Contract Obsolete )</b> ";

					
						if ( count( $given_part_tasks ) == 0 and
								 ! contract_finalised( $part->get_contract_id() ) )	
						{
							if ( $part ->get_progress() != 100 )
								echo " <b style=\"margin: 10px 0px 0px 0px; color: rgb( 123, 123, 123 );\"> ( Part tasks empty for now ) </b> ";		
							else
								echo " <b style=\"margin: 10px 0px 0px 10px; color: rgb( 123, 123, 123 );\"> ( Part tasks completed ) </b> ";		
						}

//
						if ( ! contract_finalised( $part->get_contract_id() )
							and $part ->get_progress() != 100 )
						{	foreach ( $given_part_tasks as $given_part_task )
							{
								echo "<a href=\"index.php?path=task_controller/view_part_task/" . $task->get_id() . '(@)' . $given_part_task->get_part_task() . "\" class=\"add_part_href\" style=\"margin-left: 10px; background-color: rgb( 243, 243, 243 );";

								if ( $part ->get_progress() == 100 )
								{
									echo " background-color: rgb( 250, 250, 250 ); !important; margin-top: 7.9px; line-height: 21px; ";
								}

								echo "\" >" . $given_part_task->get_part_task() . "</a>";
							}
						}


						echo "<div style=\"height: 10px;\"></div>";


						// Invalid with "or"

						$shown = false;
						if ( $part ->get_progress() != 100 and $_SESSION[ 'type' ] == "admin" )
						{
							echo "<a class=\"add_part_href\" href=\"index.php?path=task_controller/add_part_task/" . $task->get_id() . "\" style=\"font-size: 14px; border: 2px solid rgb( 150, 150, 150 ); margin-right: 10px; padding: 2px 10px;\"> Add Tasks </a> ";

							$shown = true;
						}
						if ( $part ->get_progress() != 100 and $_SESSION[ 'id' ] == $this->data[ 'id' ] and ! $shown
						)
							echo "<a class=\"add_part_href\" href=\"index.php?path=task_controller/add_part_task/" . $task->get_id() . "\" style=\"font-size: 14px; border: 2px solid rgb( 150, 150, 150 );  margin-right: 10px; padding: 2px 10px;\"> Add Tasks </a> ";


						echo "<a class=\"add_part_href\" href=\"index.php?path=task_controller/view/" . $task->get_id() . "\" style=\"font-size: 14px; border: 2px solid rgb( 150, 150, 150 ); margin-right: 10px; padding: 2px 10px;";

						if ( $part ->get_progress() == 100 )
							echo "margin: 7px 0px 0px 10px; border-width: 1px; vertical-align: middle; line-height: 14px; padding: 3px 10px 0px 10px;";


						echo" \" > View Tasks </a>";




						$tasks = get_tasks();
						$have_users_on_this_task = "false";
						foreach ( $users as $user )
						{
							if ( $part->get_progress() == 100 )
							{
								$have_users_on_this_task = "part_completed";
								break;
							}
						
							if ( $this->data[ 'id' ] == $user->get_id() )
								continue;

							foreach ( $tasks as $task_of_user )
							{
								if ( $part->get_id() == $task_of_user->get_part_id() and $user->get_id() == $task_of_user->get_user_id() )
								{
									if ( $have_users_on_this_task == "false" )
									{
										$have_users_on_this_task = "true";
										echo "<span style=\" ";

										if ( $part->get_progress() != 100 )
											echo " line-height: 30px; ";

										if ( $part->get_progress() == 100 )
											echo " margin: 10px 0px 0px 10px; ";

										echo "\" > Also on this part ( <b>" . $part->get_part_name() . "</b> ): ";
									}

									echo "<a href=\"index.php?path=user_controller/view/" . $user->get_id() . "\"> ".  $user->get_firstname() . ' ' . $user->get_lastname() . ' ( ' . $user->get_username() . ' )</a> ';

									if ( $have_users_on_this_task )
										echo "/";
								}

								echo "</span>";
							}
						}		


						if ( $have_users_on_this_task == "part_completed" )
							echo "<a class=\"add_part_href\" style=\"margin: 7px 0px 0px 10px; border-width: 1px; vertical-align: middle; line-height: 14px; padding: 3px 10px 0px 10px; font-size: 14px; border: 2px solid rgb( 150, 150, 150 ); margin-right: 10px; padding: 2px 10px;\" href=\"index.php?path=contract_controller/view_contracts#" . $contract->get_id() . "\" > Part Users </a>";



						if ( $have_users_on_this_task == "false"  )
						{
							if ( $_SESSION[ 'id' ] == $this->data[ 'id' ] )
							{
								echo "<span style=\"font-size: 14px; ";

								if ( $part->get_progress() == 100 )
									echo " margin: 10px 0px 0px 10px;";

								echo "\" > Others do not have this part yet. </span>";
							}
							else
								echo "For now, <b>" . $part->get_part_name() . "</b> is <b> " . $this->data[ 'firstname' ] . ' ' . $this->data[ 'lastname' ] . "'s part only</b>. </b> ";					
						}

						echo "</div> <br>";

					}


			if ( $user_has_tasks == false )
			{
				if ( $_SESSION[ 'id' ] != $this->data[ 'id' ] )
					echo " does not have parts. <br>";
				else
				{
					if ( $_SESSION[ 'type' ] == "admin" )
						echo " for now, empty <b>( you do not have any tasks )</b>. <br> 
						   ( You have to have <b>contracts</b>, <b>parts</b> for them and <b>then tasks</b> for users. ) <br>
						   <br>";
					else
						echo " for now, empty <b>";
						
				}
			}
			// else
			// 	echo "<br>";


		}
		else
		{
			$contracts = $this->data[ 'contracts' ];

			$have_contracts = false;
			foreach ( $contracts as $contract )
			{
				if ( $this->data[ 'id' ] == $contract->get_client_id() )
				{
					echo "<a class=\"add_part_href\" href=\"index.php?path=user_controller/client_view/" . $this->data[ 'id' ] . "\"> Client View</a> <br> <br>";
					$have_contracts = true;
					break;
				}
			}

			$contracts = get_contracts_through_client_id( $this->data[ 'id' ] );

			if ( $have_contracts == true )
			{
				echo "<style> .behind_progress { width: 80% !important; } </style>";

				foreach ( $contracts as $contract )
				{
					echo "<div class=\"contract_div\"";

					if ( contract_obsolete( $contract->get_id() ) or contract_finalised( $contract->get_id() ) )
						echo "style=\"background-color: rgb( 204, 204, 204 );\"";
					
					if ( contract_final_month ( $contract->get_id() ) )
						echo "style=\"background-color: rgb( 243, 243, 243 );\"";
					
					echo ">";

					echo "<h3 class=\"center\">"  . $contract->get_contract_name() . " </h3>";

					if ( contract_obsolete( $contract->get_id() ) and ! contract_finalised( $contract->get_id() ) )
						echo "<h4> Contract obsolete, but not finalised </h4>";

					if ( ! contract_obsolete( $contract->get_id() ) and contract_finalised( $contract->get_id() ) )
						echo "<h4> Contract done </h4>";

					if ( contract_obsolete( $contract->get_id() ) and contract_finalised( $contract->get_id() ) )
						echo "<h4> Contract done and obsolete </h4>";


					if ( contract_final_month ( $contract->get_id() ) )
						echo "<h4> Final month of contract! </h4>";


					echo "<br>";


					echo "Contract Date: <b style=\"margin-right: 10px;\">" . $contract->get_contract_date() . " </b>";
					echo "Deadline Date: <b>" . $contract->get_deadline_date() . " </b> <br> <br>";

					$parts = get_parts();

					foreach ( $parts as $part )
					{
						if ( $part->get_contract_id() != $contract->get_id() )
							continue;

						echo " 
						<div class=\"progress_div\" > 
							<b title=\"Progress\">" . $part->get_part_name() . ": </b>
							<div class=\"behind_progress\">
								<div class=\"progress\" style=\"width: " . $part->get_progress() . "%;\" > 
									<span>" . $part->get_progress() . "% </span> 
								</div> 
							</div> 
							<span> / 100% </span> 
						</div>

						<p>";
					}


					$users = get_users();
					$tasks = get_tasks();

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
					echo " ( Unavailable ) <p> <a class=\"add_part_href\" href=\"index.php?path=task_controller/create/part(@)" . $part->get_id() . "\"> Add Users for part \"" . $part->get_part_name() .  "\" </a> </p>";
				else
					echo "<p> <a class=\"add_part_href\" href=\"index.php?path=task_controller/create/part(@)" . $part->get_id() . "\"> Add Users for part \"" . $part->get_part_name() .  "\" </a> </p> <br>";					}

					if ( $contract->get_details() != "" )
					{
						echo "<br> <b> Contract Details: </b>";

						echo "<pre style=\"font-family: Arial;\">'" . $contract->get_details() . "'</pre>";
					}

					echo "<p title=\"Auxiliary contract data\" style=\"margin-top: 30px; margin-bottom: 0px;\"><span class=\"auxiliary_data\" > <b>Contract Id:</b> " . $contract->get_id() . "</p> </div>";
				}
			}

			if ( $_SESSION[ 'type' ] == "admin" )
			{
				echo "<a class=\"add_part_href\" href=\"index.php?path=contract_controller/create/" . $this->data[ 'id' ] ."\" > Add Contract</a>";

				echo "<br>";
			}

		}







		if ( $_SESSION[ 'type' ] == "admin" )
		{
			echo "<div style=\"height: 10px;\"></div> <a class=\"add_part_href\" style=\"padding: 2px 10px; background-color: rgb( 204, 204, 204 );\" title=\"Add Part to User\" href=\"index.php?path=task_controller/create/user(@)" . $this->data[ 'id' ] . "\" > Add Part to User </a>  <br>";

			if ( $_SESSION[ 'id' ] == $this->data[ 'id' ] )
				echo "<br><br>";
		}




		if ( $_SESSION[ 'id' ] != $this->data[ 'id' ] )
			echo "<style> .auxiliary_data { display: none; } </style>";
		else
			echo "<style> .user_details { display: none; } </style>";

		?>		

		<div class="auxiliary_data" style="margin: -21px 0px 0px 0px;" >

			<p title="Auxiliary data" style="margin: 10px 0px;"> ( 
				<span title="Username"> <b>Username:</b> <?php echo $this->data[ 'username' ]; ?>
				</span> /

				<span title="Firstname"> <b>Firstname:</b> <?php echo $this->data[ 'firstname' ]; ?> </span> /
				<span title="Lastname"> <b>Lastname:</b> <?php echo $this->data[ 'lastname' ]; ?> </span> /
				<span title="Email"> <b>Email:</b> <?php echo $this->data[ 'email' ]; ?> </span> /

				<b>Id:</b> <?php echo $this->data[ 'id' ]; ?> / 
				<b>Type:</b> <?php echo $this->data[ 'type' ]; ?> )
			</p>

		</div>


		<br>



		<?php

		echo "<div class=\"div_center\">
			  	<div class=\"user_options\">";

		if ( $_SESSION[ 'type' ] == "admin" or $_SESSION[ 'id' ] == $this->data[ 'id' ] )
			echo "<a title=\"Edit user\" href=\"index.php?path=user_controller/edit/" . $this->data[ 'id' ] . "\" > Edit User</a> ";  
		
		if ( $_SESSION[ 'type' ] == "admin" )
		{
			echo "/ <a title=\"Delete user\" href=\"index.php?path=user_controller/delete/" . $this->data[ 'id' ] . "\" > Delete User</a>";
			echo "<br> <br> <a title=\"View Employees\" href=\"index.php?path=user_controller/index/employee\" > View Employees </a> / ";
			echo "<a title=\"View Admins\" href=\"index.php?path=user_controller/index/admin\" > View Admins </a> 

				
				</div> </div>


				";

			if ( $_SESSION[ 'id' ] == $this->data[ 'id' ] )
			{
				echo "<br> <br>";



			    echo "	<div id=\"admin_contracts\" class=\"contract_div\" style=\"padding: 10px;\" >

			    	<h3 style=\"margin-bottom: 0px;\"> Contracts: </h3>
					<span style=\"font-size: 15px;\"> ( not your tasks ) </span> <br> <br>";


				echo "<a class=\"add_part_href\" style=\"padding: 5px 10px;\" href=\"index.php?path=user_controller/part_tasks\"> View Company Part tasks </a> <br> <br>";

		    	$contracts = get_contracts();

		    	foreach ( $contracts as $contract )
		    	{
		    		echo "<div class=\"admin_contract\"> <b>";

		    		echo "<span style=\"line-height\"> <a href=\"index.php?path=contract_controller/view/" . $contract->get_id() . "\" > " .  $contract->get_contract_name() . "</a> for ";

		    		$client = get_user_through_id( $contract->get_client_id() );

		    		echo "<a href=\"index.php?path=user_controller/view/" . $client->get_id() . "\"> " . $client->get_firstname() . ' ' . $client->get_lastname() . "</a>:</b>";


					// Contract Progress

					$parts = get_parts_through_contract_id( $contract->get_id() );

					$completed_parts = 0;
					$contract_progress = 0;
					foreach ( $parts as $part )
					{
						if ( $part->get_progress() == 100 )
							$completed_parts = $completed_parts + 1;

						$contract_progress = $contract_progress + $part->get_progress();
					}

					if ( count( $parts ) != 0 )
						$contract_progress = (int) ( $contract_progress / count( $parts ) );

					echo " <b>" . $contract_progress . "%</b> - ";

					echo "<span style=\"display: inline-block; text-align-center; font-size: 15px;\"> Parts completed: <b>" . $completed_parts . " / " . count( $parts ) . "</b> </span> ";


					if ( contract_obsolete( $contract->get_id() ) and ! contract_finalised( $contract->get_id() ) )
						echo " - <b style=\"display: inline-block;\">( Obsolete, but not finalised )</b>";

					if ( ! contract_obsolete( $contract->get_id() ) and contract_finalised( $contract->get_id() ) )
						echo " - <b style=\"display: inline-block;\">( Finalised )</b>";

					if ( contract_obsolete( $contract->get_id() ) and contract_finalised( $contract->get_id() ) )
						echo " - <b style=\"display: inline-block;\">( Finalised and obsolete )</b>";


					if ( contract_final_month ( $contract->get_id() ) )
						echo " - <b style=\"display: inline-block;\">( Final month! )</b>";

		    		echo "<br>";

		    		echo "<div style=\"height: 5px;\"> </div>";

					echo $contract->get_contract_name() . " Parts: ";

					$parts = get_parts_through_contract_id( $contract->get_id() );

					foreach ( $parts as $part )
					{
						echo "<b> <a href=\"index.php?path=part_controller/view/" . $part->get_id() . "\">" . $part->get_part_name() . "</a>: ";

						if ( $part->get_progress() == 100 )
							echo "Complete</b> / ";
						else
							echo $part->get_progress() . "%</b> / ";
					}

					echo "<a href=\"index.php?path=part_controller/create/" . $part->get_id() . "\" class=\"add_part_href\" style=\"padding: 2px 10px;\"> Add Part </a>";

					echo "<br>";



					$tasks = get_tasks();
					$users = get_users();

					$have_contract_users = false;
					foreach ( $parts as $part )
					{	
						echo "<b>" . $part->get_part_name();

						if ( $part->get_progress() == 100 )
							echo " ( Completed ) ";

						echo "</b>: ";


						$have_users = false;

						foreach ( $users as $user )
							foreach ( $tasks as $task )
								if ( $task->get_user_id() == $user->get_id() and 
								     $task->get_part_id() == $part->get_id() )
								{

									if ( $have_users == false )
									{
										$have_users = true;
										$have_contract_users = true;
									}

									echo "<a style=\"text-decoration: none; font-weight: 500;\" href=\"index.php?path=user_controller/view/" . $user->get_id() . "\"> " . $user->get_firstname() . ' ' . $user->get_lastname() . "</a> / ";
								}

						echo "<a class=\"add_part_href\" style=\"padding: 1px 10px;\" href=\"index.php?path=task_controller/create/part(@)" . $part->get_id() . "\"> Add Users </a> <br>";
					}


					echo "</div> <br>";
		    	}

		    	echo "</div>";




				echo "<div class=\"center\">
					 <h2> Company edit details <br> <span style=\"font-size: 14px;\"> ( save, or not common practice to edit ) </span> </h2>
				 </div>

				 <div class=\"admin_company_edit\">

					 <b>Set Company Name:</b> 

					 <form  title=\"Edit Company Name\" action=\"views/config/config_admin.php\" > 

					 	<input type=\"text\" name=\"company_name\" placeholder=\"" . $this->data[ 'company' ] .  "\" required> </input>
					 	<input type=\"submit\" value=\"Set Company Name\"> </input>

					 </form>

					 <br>
					 <a href=\"index.php?path=config_controller/save_database\"> Save Database</a> /


					 <b>Load Database:</b> 
					 <form title=\"Load Database\" action=\"views/config/config_admin.php\" > 

					 	<p style=\"font-size: 14px; margin: 5px 0px 5px 0px;\"> Please place text of database file: </p>

					 	<textarea name=\"database_file\" rows=4 cols=20 required></textarea> <br>
					 	<input type=\"submit\" value=\"Load Database file\"> </input>

					 </form>

					 <!-- <br>

					 or, if you do not have the file: <a href=\"index.php?path=config_controller/load_last_saved_database\"> Load Last Saved Database</a>.

					 <br>


					  -->



				 </div>

				 ";
			}
			else
				echo "</div> </div>";
		}

		?>





		<br>


	</body>

</html>