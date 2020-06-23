<!DOCTYPE html>

<html>

	<head>
		<title> Task <?php echo $this->data[ 'id' ]; ?> </title>
		<link rel="stylesheet" href="/Procres/files/style.css">
		<meta name="viewport" content="width=device-width, initial-scale=1">
	</head>

	<body>

		<?php $this->show_nav(); ?>

		<div class="company"> <?php echo $this->data[ 'company' ]; ?> </div>
		<?php if ( $_SESSION[ 'type' ] != "admin" ) echo "<style> #left_nav { display: none !important; } html { padding-left: 10px !important; } </style>"; ?>
		<a class="signed_in_user" href=<?php echo "\"index.php?path=user_controller/view/" . $_SESSION[ 'id' ] . "\""; ?> > <?php echo $_SESSION[ 'username' ]; ?> </a>


		<div class="center" >

			<h1> Task <?php echo ucfirst( $this->data[ 'id' ] ); ?>  </h1>


			<?php

			$part = $this->data[ 'part' ];
			$contract = get_contract_through_id( $part->get_contract_id() );
			$contract_id = $contract->get_id();

			// Contract obsolete / finalised

			if ( contract_obsolete( $contract_id ) or contract_finalised ( $contract_id ) )
			{

				if ( contract_finalised ( $contract_id ) and ! contract_obsolete( $contract_id ) )
					echo "<h2> Contract is done. </h2>";

				if ( ! contract_finalised ( $contract_id ) and contract_obsolete( $contract_id ) )
					echo "<h2> Contract is obsolete. </h2>";

				if ( contract_finalised ( $contract_id ) and contract_obsolete( $contract_id ) )
					echo "<h2> Contract is done and obsolete. </h2>";


				echo "<style> html{ background-color: rgb( 234, 234, 234 ); } </style>";

				echo "<div class=\"div_center\">";
				if ( $_SESSION[ 'type' ] == "admin" )
					echo "<h2 class=\"delete_border\" style=\"width: 70%; text-align: center;\"> <a href=\"index.php?path=contract_controller/delete/" . $contract_id . "\">Delete the entire contract</a>, not each  part on its own. </h2>";
				else
					echo "<h2 class=\"delete_border\" style=\"width: 70%;\"> Please tell an admin to delete contract. </h2>";
				echo "</div>";
			}


			if ( contract_final_month( $contract_id ) )
				echo "<h2> Final month of Contract! </h2>";

			?>

	    </div>

	    <?php

	    $user = $this->data[ 'user' ];
	    $part = $this->data[ 'part' ];

	    ?>

		<p title="User"> <b>User:</b> <a href=<?php echo "index.php?path=user_controller/view/" . $user->get_id(); ?> > <?php echo $user->get_firstname() . ' ' . $user->get_lastname(); ?> </a> </p>
		<p title="Part"> <b>Part:</b> <a href=<?php echo "index.php?path=part_controller/view/" . $part->get_id(); ?> > <?php echo $part->get_part_name(); ?> </a> </p>


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

		<a title="Edit this Task" href=<?php echo "index.php?path=task_controller/edit/" . $this->data[ 'id' ]; ?> > Edit Task</a> /  
		<a title="Delete this Task" href=<?php echo "index.php?path=task_controller/delete/" . $this->data[ 'id' ]; ?> > Delete Task</a>

		<br>


	</body>

</html>