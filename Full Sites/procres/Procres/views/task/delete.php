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

		

		<div class="company"> <?php echo $this->data[ 'company' ]; ?> </div>
		<?php if ( $_SESSION[ 'type' ] != "admin" ) echo "<style> #left_nav { display: none !important; } html { padding-left: 10px !important; echo \"echo\"; } </style>"; ?>
		<a class="signed_in_user" href=<?php echo "\"index.php?path=user_controller/view/" . $_SESSION[ 'id' ] . "\""; ?> > <?php echo $_SESSION[ 'username' ]; ?> </a>


		<div class="center" >
			<div class="delete_div" >

				<h1> Remove User Part? </h2>

				<h2> Deleting tasks is not a regular practice! </h2>

				<h4> ( Deleting contracts however should be ) </h4>


				<div id="delete_contract" class="div_center" style="font-size: 0.75rem;">
					<div style="width: 40%;">
						<h3> If you want to delete the entire contract, please do so. </h3> 
						<h4> Contract will be added to "Deleted Contracts", when deleted. If you delete a task on its own, the task you delete will not de added to "Deleted Contract" details when the entire contract is deleted. </h4> 
					</div>
				</div> 


				<div class="div_center">
				    <div class="delete_border">
						<span> <b>Id:</b> <?php echo $this->data[ 'id' ]; ?> </span> <br>
						<span> <b>User:</b>  <?php echo $this->data[ 'user_name' ]; ?> </span> <br>
						<span> <b>Part:</b> <?php echo $this->data[ 'part_name' ]; ?> </span> <br>
					</div> 
				</div>

				<br>

				<a class="delete" href=<?php echo "index.php?path=task_controller/delete_task/" . $this->data[ 'id' ]; ?> > Delete</a> - cannot be reversed <br> <br>

				<a href=<?php echo "index.php?path=task_controller/view/" . $this->data[ 'id' ]; ?> > Return to View task </a>

		    </div>
	    </div>

	</body>

</html>