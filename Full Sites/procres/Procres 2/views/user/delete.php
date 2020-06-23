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
			<div class="delete_div" >


				<h1> Delete user? </h2>


				<div id="delete_contract" class="div_center" style="font-size: 0.75rem;">
					<div style="width: 50%;">
						
						<h2> Deleting users should only be done when they are not related to any contract! 
							<br> 
							<span style="font-size: 14px;"> ( so, make sure to 
								<a href=<?php echo "index.php?path=user_controller/view/" . $this->data[ 'id' ]; ?> > View User</a>  before deleting ) 
							</span> 
						</h2>

						<h3> If you want to add user's Contracts / Parts to "Deleted Contracts", <br> <span style="font-size: 20px;">do not Delete user</span>. </h3> <br>
						<h4> Please make sure that this user is not part of any contract before deleting. <br> <br> Delete all contracts this user might have ( when they are complete ), then delete this user. </h4> 
					</div>
				</div> 


				<div class="div_center">
				    <div class="delete_border">
						<span> <b>Id:</b> <?php echo $this->data[ 'id' ]; ?> </span> <br>
						<span> <b>Username:</b>  <?php echo $this->data[ 'username' ]; ?> </span> <br>
						<span> <b>Firstname:</b> <?php echo $this->data[ 'firstname' ]; ?> </span> <br>
						<span> <b>Lastname:</b> <?php echo $this->data[ 'lastname' ]; ?> </span> <br>
						<span> <b>Email:</b> <?php echo $this->data[ 'email' ]; ?> </span> <br>
						<span> <b>Type:</b> <?php echo $this->data[ 'type' ]; ?> </span> <br>
					</div> 
				</div>

				<br>

				<b> Deleting User also deletes </b> <br> <b> tasks / contracts ( if user is client ). </b> <br> <br>

				<a class="delete" href=<?php echo "index.php?path=user_controller/delete_user/" . $this->data[ 'id' ]; ?> > Delete</a> - cannot be reversed <br> <br>

				<a href=<?php echo "index.php?path=user_controller/view/" . $this->data[ 'id' ]; ?> > Return to View User </a>

		    </div>
	    </div>

	</body>

</html>