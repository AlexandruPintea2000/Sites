<!DOCTYPE html>

<html>

	<head>
		<title> <?php echo $this->data[ 'part_name' ]; ?> </title>
		<link rel="stylesheet" href="/Procres/files/style.css">
		<meta name="viewport" content="width=device-width, initial-scale=1">
	</head>


	<body>

		<?php $this->show_nav(); ?>

		<div class="company"> <?php echo $this->data[ 'company' ]; ?> </div>
		<?php if ( $_SESSION[ 'type' ] != "admin" ) echo "<style> #left_nav { display: none !important; } html { padding-left: 10px !important; } </style>"; ?>
		<a class="signed_in_user" href=<?php echo "\"index.php?path=user_controller/view/" . $_SESSION[ 'id' ] . "\""; ?> > <?php echo $_SESSION[ 'username' ]; ?> </a>


		<div class="center" >

			<h1> <?php echo $this->data[ 'part_name' ]; ?>  </h1>

			<?php

			// Contract obsolete / finalised

			if ( contract_obsolete( $this->data[ 'contract_id' ] ) or contract_finalised ( $this->data[ 'contract_id' ] ) )
			{

				if ( contract_finalised ( $this->data[ 'contract_id' ] ) and ! contract_obsolete( $this->data[ 'contract_id' ] ) )
					echo "<h2> Contract is done. </h2>";

				if ( ! contract_finalised ( $this->data[ 'contract_id' ] ) and contract_obsolete( $this->data[ 'contract_id' ] ) )
				{
					echo "<h2> Contract is obsolete. </h2>";
					echo "<h3> When you know that contract is also done, delete it. </h3>";
				}

				if ( contract_finalised ( $this->data[ 'contract_id' ] ) and contract_obsolete( $this->data[ 'contract_id' ] ) )
					echo "<h2> Contract is done and obsolete. </h2>";


				echo "<style> html{ background-color: rgb( 234, 234, 234 ); } </style>";

				echo "<div class=\"div_center\">";
				if ( $_SESSION[ 'type' ] == "admin" )
					echo "<h2 class=\"delete_border\" style=\"width: 70%; text-align: center;\"> <a href=\"index.php?path=contract_controller/delete/" . $this->data[ 'contract_id' ] . "\">Delete the entire contract</a>, not each  part on its own. </h2>";
				else
					echo "<h2 class=\"delete_border\" style=\"width: 70%;\"> Please tell an admin to delete contract. </h2>";
				echo "</div>";
			}

			if ( contract_final_month( $this->data[ 'contract_id' ] ) )
				echo "<h2> Final month of Contract! </h2>";

			?>

	    </div>


		<p title="Part name"> <b>  <?php echo $this->data[ 'part_name' ]; ?> </b> ( of 
		<a title="Contract" href=<?php echo "index.php?path=contract_controller/view/" . $this->data[ 'contract' ]->get_id(); ?> > "<?php echo $this->data[ 'contract' ]->get_contract_name(); ?>"</a> contract ) </p>


		<style>
			
			.progress
			{
				width: <?php echo $this->data[ 'progress' ] . "%"; ?>;
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

		<p>
			<b> Users: </b>

			<?php

				$users = $this->data[ 'users' ];
				$tasks = $this->data[ 'tasks' ];

				foreach ( $users as $user )
					foreach ( $tasks as $task )
						if ( $task->get_user_id() == $user->get_id() and $task->get_part_id() == $this->data[ 'id' ] )
						{
							echo "<a href=\"index.php?path=user_controller/view/" . $user->get_id() . "\">" . $user->get_firstname() . ' ' . $user->get_lastname() . "</a> / ";
						}

			?>

			<a class="add_part_href" href=<?php echo "index.php?path=task_controller/create/part(@)" . $this->data[ 'id' ]; ?> > Add Users </a>
		</p>


		<p style="margin-top: 15px;" title="Auxiliary data" style="margin: 10px 0px;"><span class="auxiliary_data" > ( <b>Id:</b> <?php echo $this->data[ 'id' ]; ?> ) </span></p>

		<br>

		<a title="Add Task" href=<?php echo "index.php?path=task_controller/create/part(@)" . $this->data[ 'id' ]; ?> > Add Task</a> /  
		<a title="Edit this Part" href=<?php echo "index.php?path=part_controller/edit/" . $this->data[ 'id' ]; ?> > Edit Part</a> /  
		<a title="Delete this Part" href=<?php echo "index.php?path=part_controller/delete/" . $this->data[ 'id' ]; ?> > Delete Part</a>

		<br>


	</body>

</html>