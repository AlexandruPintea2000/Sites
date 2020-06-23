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