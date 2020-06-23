<!DOCTYPE html>

<html>

	<head>
		<title> <?php echo $this->data[ 'title' ]; ?> </title>
		<link rel="stylesheet" href="/Procres/files/style.css">
		<meta name="viewport" content="width=device-width, initial-scale=1">
	</head>

	
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

		<?php $this->show_nav(); ?>

		<div class="company"> <?php echo $this->data[ 'company' ]; ?> </div>
		<?php if ( $_SESSION[ 'type' ] != "admin" ) echo "<style> #left_nav { display: none !important; } html { padding-left: 10px !important; } .admin_contracts { display: none; } </style>"; ?>
		<a class="signed_in_user" href=<?php echo "\"index.php?path=user_controller/view/" . $_SESSION[ 'id' ] . "\""; ?> > <?php echo $_SESSION[ 'username' ]; ?> </a>


		<div class="center" >

			<h1> <?php echo $this->data[ 'title' ]; ?> </h1>
			<?php if ( $_SESSION[ 'id' ] == $this->data[ 'id' ] ) echo "<h3>Welcome!</h3>"; ?>
			<?php // if ( $_SESSION[ 'id' ] == $this->data[ 'id' ] ) echo "<h3>Welcome " . $this->data[ 'firstname' ] . ' ' . $this->data[ 'lastname' ] "!</h3>"; ?>

	    </div>

	    <?php

			if ( $this->data[ 'type' ] == "admin" )
				echo "<a href=\"index.php?path=user_controller/email_client_id\"> Email client Id </a>";

	    ?>





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


		<br>

		<?php

		if ( $this->data[ 'type' ] != "client" )
		{
			echo "<b>Parts assigned to ";

			if ( $_SESSION[ 'id' ] == $this->data[ 'id' ] )
				echo " you";
			else
				echo $this->data[ 'firstname' ] . ' ' . $this->data[ 'lastname' ];

			echo ":</b>";

			if ( $_SESSION[ 'id' ] == $this->data[ 'id' ] )
				echo "<br> <br>";			

			$users = $this->data[ 'users' ];
			$contracts = $this->data[ 'contracts' ];
			$parts = $this->data[ 'parts' ];
			$tasks = $this->data[ 'tasks' ];

			$user_has_tasks = false;
			foreach ( $parts as $part )
				foreach ( $tasks as $task )
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

						echo " <div class=\"parts\">
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
											<b title=\"As of Contract Deadline date\"> <b></b> until " . $contract->get_deadline_date() . "</b>";
									else
										echo "<b title=\"Final Month of Contract\"> <b></b> <b style=\"font-size: 10px;\"> until </b> <b style=\"margin: 0px 0px 0px -10px;\"> " . get_day( $contract->get_deadline_date() ) . "</b> <b style=\"font-size: 10px;\"> this month </b> </b>";

									echo "</div>";

									if ( contract_final_month( $part->get_contract_id() ) )		echo "<b style=\"font-size: 20px;\" title=\"Final Month of Contract\"> <b></b> until " . get_day( $contract->get_deadline_date() ) . " this month </b> /";

								}
						}
						else
						{


							echo "<span style=\"margin: 0px;\">Completed!</span>";


							if ( contract_finalised( $part->get_contract_id() ) )
								echo "<span style=\"margin: 0px;\"> <b></b>  <b>Contract finalised:</b> <a style=\"margin: 0px;\" href=\"index.php?path=contract_controller/delete/" . $part->get_contract_id() . "\">Delete Contract</a></span>";

							if ( contract_final_month( $part->get_contract_id() ) )
								echo "<span style=\"margin: 0px;\"> <b></b> <b> Final Month!</b></span>";

							echo "</div>";

						}


						$have_users_on_this_task = false;
						foreach ( $users as $user )
						{
							if ( $this->data[ 'id' ] == $user->get_id() )
								continue;

							foreach ( $tasks as $task_of_user )
								if ( $part->get_id() == $task_of_user->get_part_id() and $user->get_id() == $task_of_user->get_user_id() )
								{
									if ( $have_users_on_this_task == false )
									{
										$have_users_on_this_task = true;
										echo "Also on this part ( <b>" . $part->get_part_name() . "</b> ): ";
									}

									echo "<a href=\"index.php?path=user_controller/view/" . $user->get_id() . "\"> ".  $user->get_firstname() . ' ' . $user->get_lastname() . ' ( ' . $user->get_username() . ' )</a> ';

									if ( $have_users_on_this_task )
										echo "/";
								}
						}		


						if ( $have_users_on_this_task == false  )
						{
							if ( $_SESSION[ 'id' ] == $this->data[ 'id' ] )
								echo "<span style=\"font-size: 14px;\"> Others do not have this part yet. </span>";
							else
								echo "For now, <b>" . $part->get_part_name() . "</b> is <b> " . $this->data[ 'firstname' ] . ' ' . $this->data[ 'lastname' ] . "'s part only</b>. </b> ";					
						}

						echo "</div> <br>";

					}


			if ( $user_has_tasks == false )
				echo " does not have tasks. <br>";


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
				echo "<a class=\"add_part_href\" href=\"index.php?path=contract_controller/create/\" > Add Contract</a>";

				echo "<br>";
			}

		}











		if ( $_SESSION[ 'id' ] != $this->data[ 'id' ] )
			echo "<style> .auxiliary_data { display: none; } </style>";
		else
			echo "<style> .user_details { display: none; } </style>";

		?>		

		<div class="auxiliary_data" >

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
		{
			if ( $this->data[ 'type' ] != "client" )
				echo "<a title=\"Add Task\" href=\"index.php?path=task_controller/create/user(@)" . $this->data[ 'id' ] . "\" > Add Task </a> / ";
			echo "<a title=\"Edit user\" href=\"index.php?path=user_controller/edit/" . $this->data[ 'id' ] . "\" > Edit User</a> ";  
		}
		
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

		    	$contracts = get_contracts();

		    	foreach ( $contracts as $contract )
		    	{
		    		echo "<b>";

		    		echo "<span style=\"line-height\"> <a href=\"index.php?path=contract_controller/view/" . $contract->get_id() . "\" > " .  $contract->get_contract_name() . "</a> for ";

		    		$client = get_user_through_id( $contract->get_client_id() );

		    		echo "<a href=\"index.php?path=user_controller/view/" . $client->get_id() . "\"> " . $client->get_firstname() . ' ' . $client->get_lastname() . "</a>";

		    		echo ": </span> ";


					if ( contract_obsolete( $contract->get_id() ) and ! contract_finalised( $contract->get_id() ) )
						echo " ( Obsolete, but not finalised )";

					if ( ! contract_obsolete( $contract->get_id() ) and contract_finalised( $contract->get_id() ) )
						echo " ( Finalised )";

					if ( contract_obsolete( $contract->get_id() ) and contract_finalised( $contract->get_id() ) )
						echo " ( Finalised and obsolete )";


					if ( contract_final_month ( $contract->get_id() ) )
						echo " ( Final month! )";

		    		echo "</b>";



					echo " - Parts: ";

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


					echo "<br>";
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