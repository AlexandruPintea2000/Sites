<!DOCTYPE html>

<html>

	<head>
		<title> <?php echo $this->data[ 'title' ]; ?> </title>
		<link rel="stylesheet" href="/Procres/files/style.css">
		<meta name="viewport" content="width=device-width, initial-scale=1">
	</head>



	<style>

		span 
		{
			margin: 5px 0px !important;
			font-size: 10px;
		}

	</style>



	<body>

		<?php $this->show_nav(); ?>

		<div class="company"> <?php echo $this->data[ 'company' ]; ?> </div>
		<?php if ( $_SESSION[ 'type' ] != "admin" ) echo "<style> #left_nav { display: none !important; } html { padding-left: 10px !important; } </style>"; ?>
		<a class="signed_in_user" href=<?php echo "\"index.php?path=user_controller/view/" . $_SESSION[ 'id' ] . "\""; ?> > <?php echo $_SESSION[ 'username' ]; ?> </a>


		<div class="center" >

			<h1> Delete contract? </h2>

			<div class="delete_border_center">
			    <div class="delete_border">
					<span> <b>Id:</b> <?php echo $this->data[ 'id' ]; ?> </span> <br>
					<span> <b>Contract Name:</b>  <?php echo $this->data[ 'contract_name' ]; ?> </span> <br>
					<span> <b>Parts Num:</b> <?php echo $this->data[ 'details' ]; ?> </span> <br>
					<span> <b>Client:</b> <?php echo $this->data[ 'client_name' ]; ?> </span> <br>
					<span> <b>Contract Date:</b> <?php echo $this->data[ 'contract_date' ]; ?> </span> <br>
					<span> <b>Deadline Date:</b> <?php echo $this->data[ 'deadline_date' ]; ?> </span> <br>
				</div> 
			</div>

			<br>

			<b> Deleting Contract also deletes its parts and tasks. </b> <br> <br>

			<a class="delete" href=<?php echo "index.php?path=contract_controller/delete_contract/" . $this->data[ 'id' ]; ?> > Delete</a> - cannot be reversed <br> <br>

			<a href=<?php echo "index.php?path=contract_controller/view/" . $this->data[ 'id' ]; ?> > Return to View contract </a>

	    </div>

	</body>

</html>