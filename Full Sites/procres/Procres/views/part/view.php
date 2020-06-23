<!DOCTYPE html>

<html>


	<?

	if ( isset( $_GET[ 'increase' ] ) )
	{
        $data = "";
        foreach ( $_GET as $i )
        {
            if ( ! empty( $i ) and $i != "Submit" )
                $data = $data . $i . "(@)";

            if ( empty( $i ) )
    	        $data = $data . "empty(@)";

         }

        echo "<script> location.replace( \"/Procres/index.php?path=part_controller/increase_progress/" . $data . "\" ); </script>";
    }	


	?>


	<?

	$contract_name = $this->data[ 'contract' ]->get_contract_name();

	if ( isset( $_SESSION[ $contract_name ] ) )
	{
		echo "<style> #obsolete { display: none; } </style>";
	}
	else
		$_SESSION[ $contract_name ] = true;

	?>


	<head>
		<title> <?php echo $this->data[ 'part_name' ]; ?> </title>
		<link rel="stylesheet" href="/Procres/files/style.css">
		<meta name="viewport" content="width=device-width, initial-scale=1">
	</head>


	<body>

		

		<div class="company"> <?php echo $this->data[ 'company' ]; ?> </div>
		<?php if ( $_SESSION[ 'type' ] != "admin" ) echo "<style> #left_nav { display: none !important; } html { padding-left: 10px !important; } </style>"; ?>
		<a class="signed_in_user" href=<?php echo "\"index.php?path=user_controller/view/" . $_SESSION[ 'id' ] . "\""; ?> > <?php echo $_SESSION[ 'username' ]; ?> </a>


		<div class="center" >


			<script>
				
				function dismiss_obsolete ()
				{
					document.getElementById( "obsolete" ).style.display = "none";
				}

			</script>

			<div class="div_center">
			<div id="obsolete" onclick="dismiss_obsolete()" >

			<p style="margin: 10px 0px 0px 0px;"> ( click to dismiss ) </p>

			<?php

			$contract_obsolete = "";

			// Contract obsolete / finalised

			if ( contract_obsolete( $this->data[ 'contract_id' ] ) or contract_finalised ( $this->data[ 'contract_id' ] ) )
			{

				if ( contract_finalised ( $this->data[ 'contract_id' ] ) and ! contract_obsolete( $this->data[ 'contract_id' ] ) )
				{
					$contract_obsolete = "Done";
					echo "<h2> Contract is done. </h2>";
				}

				if ( ! contract_finalised ( $this->data[ 'contract_id' ] ) and contract_obsolete( $this->data[ 'contract_id' ] ) )
				{
					$contract_obsolete = "Obsolete, but not Done";
					echo "<h2> Contract is obsolete. </h2>";
					echo "<h3> When you know that contract is also done, delete it. </h3>";
				}

				if ( contract_finalised ( $this->data[ 'contract_id' ] ) and contract_obsolete( $this->data[ 'contract_id' ] ) )
				{
					$contract_obsolete = "Done and Obsolete";					
					echo "<h2> Contract is done and obsolete. </h2>";
				}


				echo "<style> html{ background-color: rgb( 234, 234, 234 ); } </style>";

				echo "<div class=\"div_center\">";
				if ( $_SESSION[ 'type' ] == "admin" )
					echo "<h2 class=\"delete_border\" style=\"width: 70%; text-align: center;\"> <a href=\"index.php?path=contract_controller/delete/" . $this->data[ 'contract_id' ] . "\">Delete the entire contract</a>, not each  part on its own. </h2>";
				else
					echo "<h2 class=\"delete_border\" style=\"width: 70%;\"> Please tell an admin to delete contract. </h2>";
				echo "</div>";
			}

			if ( contract_final_month( $this->data[ 'contract_id' ] ) )
			{
				$contract_obsolete = "Final month of Contract!";					
				echo "<h2> Final month of Contract! </h2>";
			}



			?>

		</div>
		</div>

		<h1> <?php echo $this->data[ 'part_name' ]; ?>  </h1>

		 Part is of contract: <a title="Contract" href=<?php echo "index.php?path=contract_controller/view/" . $this->data[ 'contract' ]->get_id(); ?> > "<?php echo $this->data[ 'contract' ]->get_contract_name(); ?>"</a> 

		 <b> <?php if ( $contract_obsolete != "" ) echo " ( " . $contract_obsolete . " ) "; ?> </b>

		 <div style="height: 5px;"> </div>

	    </div>

	    <br>

		<style>
			
			.progress
			{
				width: <?php echo $this->data[ 'progress' ] . "%"; ?>;
			}
	
			#obsolete
			{
				position: absolute;
				background-color: rgb( 204, 204, 204 );
				top: 20%;
				border: 3px solid rgb( 123, 123, 123 );
				border-radius: 10px;
			}

			#obsolete h2
			{
				background-color: rgb( 234, 234, 234 );
				padding: 10px;
				margin: 10px;
				border-radius: 10px;	
			}

		</style>



		<div class="progress_div"> 
			<b title="Progress">Progress:</b>
			<div class="behind_progress">
				<div class="progress"> 
					<span> <?php echo $this->data[ 'progress' ] . "%"; ?> </span> 
				</div> 
			</div> 
			<span> / 100% </span>
			<a href=<?php echo "index.php?path=part_controller/increase_progress/" . $this->data[ 'id' ] . "(@)5" ?> > +5% </a>
			<a href=<?php echo "index.php?path=part_controller/increase_progress/" . $this->data[ 'id' ] . "(@)-5" ?> > -5% </a>
			<a href=<?php echo "index.php?path=part_controller/increase_progress/" . $this->data[ 'id' ] . "(@)10" ?> > +10% </a>
			<a href=<?php echo "index.php?path=part_controller/increase_progress/" . $this->data[ 'id' ] . "(@)-10" ?> > -10% </a>
		</div>


		<form action="views/part/view.php" > 

			<b>Increase with:</b>
		
			<input type="number" name="id" value=<?php echo $this->data[ 'id' ]; ?> hidden> </input>
			<input type="number" name="increase" placeholder="0" style="width: 70px;" value=1 > </input>
			<input type="submit" value="Increase" > </input>

		</form>

		<p>
			<b> Users: </b>

			<?php

				$users = $this->data[ 'users' ];
				$tasks = $this->data[ 'tasks' ];

				$part_users = [];
				$i = 0;

				foreach ( $users as $user )
					foreach ( $tasks as $task )
						if ( $task->get_user_id() == $user->get_id() and $task->get_part_id() == $this->data[ 'id' ] )
						{
							$part_users[ $i ] = $user;
							$i = $i + 1;
						}


				foreach ( $part_users as $part_user )		
				{
					echo "<a href=\"index.php?path=user_controller/view/" . $part_user->get_id() . "\">" . $part_user->get_firstname() . ' ' . $part_user->get_lastname() . "</a> / ";
				}

			?>

			<a class="add_part_href" style="background-color: rgb( 234, 234, 234 );" href=<?php echo "index.php?path=task_controller/create/part(@)" . $this->data[ 'id' ]; ?> > Add Users </a>
		</p>



		<p>
			
			<b>Part tasks ( Given ):</b><br>
			<?php



			include_once "controllers/Task_controller.php";

			for ( $i = 0; $i < count( $part_users ); $i = $i + 1 )
			{
				$part_user = $part_users[ $i ];



				$tasks = get_tasks_through_user_id( $part_user->get_id() );
				for ( $l = 0; $l < count( $tasks ); $l = $l + 1 )
				{
					if ( $tasks[ $l ]->get_part_id() == $this->data[ 'id' ] )
					{
						$task = $tasks[ $l ];
						break;
					}
				}
				$given_part_tasks = get_part_tasks_through_task_id_and_status( $task->get_id(), "given" );


				echo "<a class=\"add_part_href\" style=\"background-color: rgb( 234, 234, 234 );\" href=\"index.php?path=task_controller/add_part_task/" . $task->get_id() . "\"> Add task </a>";

				echo " <a href=\"index.php?path=user_controller/view/" . $part_user->get_id() . "\" >" . $part_user->get_firstname() . ' ' . $part_user->get_lastname() . "</a>";



				if ( count( $given_part_tasks ) == 0 )
				{
					$completed_part_tasks = get_part_tasks_through_task_id_and_status( $task->get_id(), "completed" );


					if ( count( $completed_part_tasks ) == 0 )					
						echo ": <b>( Did not have any tasks! )</b> ";
					else
						echo ": ( Completed all given tasks ) ";

				}
				else
					echo " given: ";

				foreach ( $given_part_tasks as $given_part_task )
				{
					echo "<a class=\"add_part_href\" style=\"background-color: rgb( 234, 234, 234 );\" href=\"index.php?path=task_controller/view_part_task/" . $task->get_id() . '(@)' . $given_part_task->get_part_task() . "\"> " . $given_part_task->get_part_task() . " </a>";
				}



				if ( $i != count( $part_users ) - 1 )
					echo ',';

				echo "<br>";
			}




			?>

		</p>


			<b>Part tasks ( Completed ):</b><br>
			<?php


			for ( $i = 0; $i < count( $part_users ); $i = $i + 1 )
			{
				$part_user = $part_users[ $i ];



				$tasks = get_tasks_through_user_id( $part_user->get_id() );
				for ( $l = 0; $l < count( $tasks ); $l = $l + 1 )
				{
					if ( $tasks[ $l ]->get_part_id() == $this->data[ 'id' ] )
					{
						$task = $tasks[ $l ];
						break;
					}
				}
				$completed_part_tasks = get_part_tasks_through_task_id_and_status( $task->get_id(), "completed" );



				echo " <a href=\"index.php?path=user_controller/view/" . $part_user->get_id() . "\" >" . $part_user->get_firstname() . ' ' . $part_user->get_lastname() . "</a>";



				if ( count( $completed_part_tasks ) == 0 )
					echo ": ( Did not complete any part task yet ) ";
				else
					echo " given: ";

				foreach ( $completed_part_tasks as $completed_part_task )
				{
					echo "<a class=\"add_part_href\" href=\"index.php?path=task_controller/view_part_task/" . $task->get_id() . '(@)' . $completed_part_task->get_part_task() . "\"> " . $completed_part_task->get_part_task() . " </a>";
				}



				if ( $i != count( $part_users ) - 1 )
					echo ',';

				echo "<br>";
			}




			?>

		</p>



		<p style="margin-top: 15px;" title="Auxiliary data" style="margin: 10px 0px;"><span class="auxiliary_data" > ( <b>Id:</b> <?php echo $this->data[ 'id' ]; ?> ) </span></p>

		<br>

		<div class="div_center">		
			<div class="user_options">

				<a title="Add User to Part" href=<?php echo "index.php?path=task_controller/create/part(@)" . $this->data[ 'id' ]; ?> > Add User to Part</a> /  
				<a title="Edit this Part" href=<?php echo "index.php?path=part_controller/edit/" . $this->data[ 'id' ]; ?> > Edit Part</a> /  
				<a title="Delete this Part" href=<?php echo "index.php?path=part_controller/delete/" . $this->data[ 'id' ]; ?> > Delete Part</a>
			</div>

		</div>

		<br>


	</body>

</html>