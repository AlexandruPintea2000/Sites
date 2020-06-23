<!DOCTYPE html>

<html>


	<?

	$part = $this->data[ 'part' ];
	$contract = get_contract_through_id( $part->get_contract_id() );
	$contract_name = $contract->get_contract_name();

	if ( isset( $_SESSION[ $contract_name ] ) )
	{
		echo "<style> #obsolete { display: none; } </style>";
	}
	else
		$_SESSION[ $contract_name ] = true;

	?>


	<head>
		<title> Task <?php echo $this->data[ 'id' ]; ?> </title>
		<link rel="stylesheet" href="/Procres/files/style.css">
		<meta name="viewport" content="width=device-width, initial-scale=1">
	</head>

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

			$user = $this->data[ 'user' ];
			$contract_id = $contract->get_id();

			// Contract obsolete / finalised

			$contract_obsolete = "";

			if ( contract_obsolete( $contract_id ) or contract_finalised ( $contract_id ) )
			{

				if ( contract_finalised ( $contract_id ) and ! contract_obsolete( $contract_id ) )
				{
					$contract_obsolete = "( Done )";
					echo "<h2> Contract is done. </h2>";
				}

				if ( ! contract_finalised ( $contract_id ) and contract_obsolete( $contract_id ) )
				{
					$contract_obsolete = "( Obsolete, but not Done )";
					echo "<h2> Contract is obsolete. </h2>";
					echo "<h3> When you know that contract is also done, delete it. </h3>";
				}

				if ( contract_finalised ( $contract_id ) and contract_obsolete( $contract_id ) )
				{
					$contract_obsolete = "( Done and Obsolete )";					
					echo "<h2> Contract is done and obsolete. </h2>";
				}


				echo "<style> html{ background-color: rgb( 234, 234, 234 ); } </style>";

				echo "<div class=\"div_center\">";
				if ( $_SESSION[ 'type' ] == "admin" )
					echo "<h2 class=\"delete_border\" style=\"width: 70%; text-align: center;\"> <a href=\"index.php?path=contract_controller/delete/" . $contract_id . "\">Delete the entire contract</a>, not each  part on its own. </h2>";
				else
					echo "<h2 class=\"delete_border\" style=\"width: 70%;\"> Please tell an admin to delete contract. </h2>";
				echo "</div>";
			}


			if ( contract_final_month( $contract_id ) )
			{
				$contract_obsolete = "Final month of Contract!";					
				echo "<h2> Final month of Contract! </h2>";
			}



			?>


	    </div>
	    </div>

			<h1 style="font-size: 20px; font-weight: 500;"> 

				<a style="font-size: 25px;" style="text-decoration: none !important;" href=<?php echo "index.php?path=user_controller/view/" . $user->get_id(); ?> > <?php echo "<b>" . $user->get_firstname() . ' ' . $user->get_lastname() . "</b>"; ?></a><?php

				echo "'s tasks <br> for "; ?>

				<a href=<?php echo "index.php?path=part_controller/view/" . $part->get_id(); ?> ><?php
				echo "<b>" . $part->get_part_name() . "</b>";?></a><?php

				echo " of contract: "; ?>

				<a href=<?php echo "index.php?path=contract_controller/view/" . $contract->get_id(); ?> ><?php
				echo "<b>\"" .$contract->get_contract_name() . "\"";?></a><?php

				echo "<span style=\"font-size: 15px;\"> " . $contract_obsolete . " <span> </b>" ; ?></a> </h1>

	    </div>

	    <div style="height: 5px;"></div>

		<p title="User, Part and Contract"> <b>User:</b> <a href=<?php echo "index.php?path=user_controller/view/" . $user->get_id(); ?> > <?php echo $user->get_firstname() . ' ' . $user->get_lastname(); ?></a> /
		<b>Part:</b> <a href=<?php echo "index.php?path=part_controller/view/" . $part->get_id(); ?> > <?php echo $part->get_part_name(); ?> </a> <br>
		<b>Contract:</b> <a href=<?php echo "index.php?path=contract_controller/view/" . $contract->get_id(); ?> > <?php echo $contract->get_contract_name(); ?> </a> </p>
		<p title="Given Part tasks" style="line-height: 30px;"> <b>Given Part tasks:</b> 

		<?php 

		$part_tasks = get_part_tasks_through_task_id_and_status( $this->data[ 'id' ], "given" ); 

		if ( count( $part_tasks ) == 0 )
			echo " not available ";

		foreach ( $part_tasks as $part_task )
		{
			echo "<a href=\"index.php?path=task_controller/view_part_task/" . $this->data[ 'id' ] . '(@)' . $part_task->get_part_task() . "\" class=\"add_part_href\" style=\"margin-left: 10px; background-color: rgb( 234, 234, 234 ) !important;\" >" . $part_task->get_part_task() . "</a>";
		}


		?> 
		
		<br>
		<br>




		<a class="add_part_href" style="padding: 10px 20px;  background-color: rgb( 234, 234, 234 );" href=<?php echo "\"index.php?path=task_controller/add_part_task/" . $this->data[ 'id' ] . "\""; ?> > Add Part Task </a>

		 </p>
		<p title="Completed Part tasks" style="line-height: 30px;"> <b>Completed Part tasks:</b> 

		<?php 

		$part_tasks = get_part_tasks_through_task_id_and_status( $this->data[ 'id' ], "completed" ); 

		if ( count( $part_tasks ) == 0 )
			echo " not available ";

		foreach ( $part_tasks as $part_task )
		{
			echo "<a href=\"index.php?path=task_controller/view_part_task/" . $this->data[ 'id' ] . '(@)' . $part_task->get_part_task() . "\" class=\"add_part_href\" style=\"margin-left: 10px;\">" . $part_task->get_part_task() . "</a>";
		}


		?> 
		
		 </p>


		<div class="progress_div" style="margin-top: -5px;"> 
			<b title="Progress">Part progress:</b>
			<div class="behind_progress">
				<div class="progress" style="width: <?php echo $part->get_progress() . '%'; ?>;"> 
					<span> <?php echo $part->get_progress()	. "%"; ?> </span> 
				</div> 
			</div> 
			<span> / 100% </span>
			<a href=<?php echo "index.php?path=part_controller/view/" . $part->get_id(); ?> > View Part </a>
		</div>

		<br>

		<p title="Auxiliary data" style="margin: 10px 0px;"><span class="auxiliary_data" > ( <b>Id:</b> <?php echo $this->data[ 'id' ]; ?> ) </span></p>

		<br>

		<div class="div_center">		
			<div class="user_options">

<!-- 				<h3 style="margin: 0px; font-size: 14px; color: rgb( 123, 123, 123 );"> ( when you view a task, not much detail is offered ) <br> A task only means that a user is assigned to a part of a contract. </h3> 

			    <div style="height: 5px;"></div>
 -->

				<a title="( or Part of User )" href=<?php echo "index.php?path=task_controller/edit/" . $this->data[ 'id' ]; ?> > Edit User on Part </a> /  
				<a title="Remove User Part" href=<?php echo "index.php?path=task_controller/delete/" . $this->data[ 'id' ]; ?> > Remove user part </a>
			</div>
		</div>

		<br>


	</body>

</html>