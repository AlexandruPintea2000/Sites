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

			<h3> ( Also, add it to "Deleted Contracts" ) </h3>

			<?php

			// Contract obsolete / finalised

			if ( contract_obsolete( $this->data[ 'id' ] ) or contract_finalised ( $this->data[ 'id' ] ) )
			{

				if ( contract_finalised ( $this->data[ 'id' ] ) and ! contract_obsolete( $this->data[ 'id' ] ) )
					echo "<h2> Contract is done. </h2>";

				if ( ! contract_finalised ( $this->data[ 'id' ] ) and contract_obsolete( $this->data[ 'id' ] ) )
				{
					echo "<h3 style=\"padding: 10px; background-color: rgb( 243, 243, 243 ); border-radius: 10px;\"> <span style=\"font-size: 30px;\"> Contract is obsolete, but not finalised </span> <br> <br> You decide on whether it is done or not. <br> In order to continue contract \"" . $this->data[ 'contract_name' ] . "\", <a href=\"index.php?path=contract_controller/edit/" . $this->data[ 'id' ] . "\">edit its deadline</a>. </h3>";
				}

				if ( contract_finalised ( $this->data[ 'id' ] ) and contract_obsolete( $this->data[ 'id' ] ) )
					echo "<h2> Contract is done and obsolete. </h2>";


				echo "<style> html{ background-color: rgb( 234, 234, 234 ); } </style>";
			}
			else
			{
				echo "<div class=\"div_center\">";
				echo "<div style=\"width: 90%; font-size: 20px; padding: 10px; border-radius: 10px; background-color: rgb( 234, 234, 234 ); font-weight: bold;\" > <span style=\"border: 3px solid rgb( 123, 123, 123 ); font-size: 20px; padding: 10px; border-radius: 10px; background-color: rgb( 234, 234, 234 ); font-weight: bold;\"> Contracts are deleted when obsolete or done </span> <br> <br>";
				echo "Contract \"" . $this->data[ 'contract_name' ] . "\" is not any of those, deleting is not reccomanded. </div> ";
				echo "</div> <br>";
			}

			?>


			<div class="div_center">
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

			<b> Contract details will be shown in "Deleted Contracts". </b> <br> <br>

			<a class="delete" href=<?php echo "index.php?path=contract_controller/delete_contract/" . $this->data[ 'id' ]; ?> > Delete Contract</a> - cannot be reversed <br> <br>

			<a href=<?php echo "index.php?path=contract_controller/view/" . $this->data[ 'id' ]; ?> > Return to View contract </a>

	    </div>

	</body>

</html>