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

			<h1> <?php echo $this->data[ 'title' ]; ?> </h1>
			<?php if ( $_SESSION[ 'id' ] == $this->data[ 'id' ] ) echo "<h3>Welcome!</h3>"; ?>

	    </div>


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
		<p title="Email"> <b>Email:</b> <?php echo $this->data[ 'email' ]; ?> </p>


		<?php

		if ( $this->data[ 'type' ] != "client" )
		{
			echo "<b>Parts assigned to this user:</b>";

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

						$user_has_tasks = true;


						echo "
						<div title=\"Progress\" class=\"progress_div\"> 
							<b title=\"Progress\">" . $contract->get_contract_name() . ' - '  . $part->get_part_name() . ": </b>
							<div class=\"behind_progress\">
								<div class=\"progress\" style=\"width: " . $part->get_progress() . "%;\" > 
									<span> " . $part->get_progress() . "% </span> 
								</div> 
							</div> 
							<span> / 100% </span>
							<a title=\"View Part\" href=\"index.php?path=part_controller/view/" .$part->get_id() . "\" > View Part</a>

							<b title=\"As of Contract Deadline date\"> <b></b> until " . $contract->get_deadline_date() . "</b></div> ";



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

						if ( $have_users_on_this_task )
							echo "<br>";					

						if ( $have_users_on_this_task == false  )
							echo "For now, <b>" . $part->get_part_name() . "</b> is <b> " . $this->data[ 'firstname' ] . ' ' . $this->data[ 'lastname' ] . "'s part only</b>. </b> ";					

					}


			if ( $user_has_tasks == false )
				echo " does not have tasks. <br>";

		}
		else
		{
			if ( $_SESSION[ 'type' ] == "admin" )
				echo "<a href=\"index.php?path=contract_controller/create/\" > Add Contract</a> <br> <br>";

			$contracts = $this->data[ 'contracts' ];

			foreach ( $contracts as $contract )
			{
				if ( $this->data[ 'id' ] == $contract->get_client_id() )
				{
					echo "<a href=\"index.php?path=user_controller/client_view/" . $this->data[ 'id' ] . "\"> Client View</a> <br> <br>";
					break;
				}
			}

		}

		?>		



		<p title="Auxiliary data" style="margin: 10px 0px;"><span class="auxiliary_data" > ( <b>Id:</b> <?php echo $this->data[ 'id' ]; ?> 
		<b>Type:</b> <?php echo $this->data[ 'type' ]; ?> ) </span></p>

		<br>

		<?php

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
			echo "<a title=\"View Admins\" href=\"index.php?path=user_controller/index/admin\" > View Admins </a> ";
			if ( $_SESSION[ 'id' ] == $this->data[ 'id' ] )
			echo "<br> <br> <br>

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

				 	<textarea name=\"database_file\" required></textarea> <br>
				 	<input type=\"submit\" value=\"Load Database file\"> </input>

				 </form>



				 ";
		}

		?>





		<br>


	</body>

</html>