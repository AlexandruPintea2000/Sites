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
		<?php if ( $_SESSION[ 'type' ] != "admin" ) echo "<style> #left_nav { display: none !important; } html { padding-left: 10px !important; } </style>"; ?>
		<a class="signed_in_user" href=<?php echo "\"index.php?path=user_controller/view/" . $_SESSION[ 'id' ] . "\""; ?> > <?php echo $_SESSION[ 'username' ]; ?> </a>


		<div class="center" >
			<div class="delete_div" >

				<h1> Delete part? </h2>

				<h2> Deleting parts is not a regular practice! </h2>

				<h4> ( Deleting contracts however should be ) </h4>

				<div id="delete_contract" class="div_center" style="font-size: 0.75rem;">
					<div style="width: 30%;">
						<h3> If you want to delete the entire contract, please do so. </h3> 
						<h4> Contract will be added to "Deleted Contracts", when deleted. If you delete a part on its own, the part you delete will not de added to "Deleted Contract" details when the entire contract is deleted. </h4> 
					</div>
				</div> 

				<div class="div_center">
				    <div class="delete_border">
						<span> <b>Id:</b> <?php echo $this->data[ 'id' ]; ?> </span> <br>
						<span> <b>Part Name:</b>  <?php echo $this->data[ 'part_name' ]; ?> </span> <br>
						<span> <b>Contract:</b> <?php echo $this->data[ 'contract_name' ]; ?> </span> <br>
						<span> <b>Progress:</b> <?php echo $this->data[ 'progress' ]; ?>% </span> <br>
					</div> 
				</div>

				<br>

				<b> Deleting Part also deletes its tasks. </b> <br> <br>


				<a class="delete" href=<?php echo "index.php?path=part_controller/delete_part/" . $this->data[ 'id' ]; ?> > Delete</a> - cannot be reversed <br> <br>

				<a href=<?php echo "index.php?path=part_controller/view/" . $this->data[ 'id' ]; ?> > Return to View part </a>

		    </div>
	    </div>

	</body>

</html>