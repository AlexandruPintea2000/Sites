<!DOCTYPE html>

<html>

	<head>
		<title> <?php echo $this->data[ 'title' ]; ?> </title>
		<link rel="stylesheet" href="/Procres/files/style.css">
		<meta name="viewport" content="width=device-width, initial-scale=1">
	</head>


	<body>

		

		<div class="company"> <?php echo $this->data[ 'company' ]; ?> </div>
		<?php if ( $_SESSION[ 'type' ] != "admin" ) echo "<style> #left_nav { display: none !important; } html { padding-left: 10px !important; } </style>"; ?>
		<a class="signed_in_user" href=<?php echo "\"index.php?path=user_controller/view/" . $_SESSION[ 'id' ] . "\""; ?> > <?php echo $_SESSION[ 'username' ]; ?> </a>


		<div class="center" >

			<h1> <?php echo $this->data[ 'title' ]; ?>  </h1>

			<?php

			$deleted_contracts = $this->data[ 'deleted_contracts' ];


			// if ( count(  ) < 1 )
			echo "<h3>Contracts were not deleted yet!</h3>";

			?>

	    </div>



		<style>

			h3
			{
				font-weight: 500;
				font-family: Sans;
				font-size: 25px;
				margin: 0px;					
			}

			h3 a
			{
				text-decoration: none;
			}

			.contracts_div
			{
				padding: 20px 80px;
				background-color: rgb( 150, 150, 150 );
				border-radius: 20px;
				margin: 30px 0px;
			}

			.contract_div
			{
				border: 3px solid black;
				margin: 20px 0px;
				background-color: rgb( 234, 234, 234 );
				padding: 40px;
				border: 1px solid rgb( 159, 159, 159 );
				border-right: 15px solid rgb( 159, 159, 159 );
				border-radius: 40px 20px 70px 40px;
			}


			pre
			{
				border: 3px solid rgb( 204, 204, 204 );
				padding: 15px;
				border-radius: 15px;
				background-color: rgb( 209, 209, 209 );
			}


			@media only screen and ( max-width: 1000px )
			{
				.contracts_div
				{
					padding: 10px;
				}
			}


		</style>

		<br>



	    <form action="views/search/index.php">
		    <input name="search" placeholder="Search Deleted Contracts">  </input>
		    <input type="submit" style="font-weight: bold;" name="Submit" value="Search Deleted Contracts" >  </input>

		    <br>
		    <span> ( actually, it searches through <b>current contracts</b> as well <b>( and more )</b>, to make sure ) </span>

	    </form>

	    <br>


	    <?

		foreach ( $deleted_contracts as $deleted_contract )
		{
			echo "<a href=\"#" . $deleted_contract->get_deleted_contract_name() . "	\">"  . $deleted_contract->get_deleted_contract_name() . "</a> / ";
		}
		echo "<br> <br>";

		foreach ( $deleted_contracts as $deleted_contract )
		{
			echo "<p style=\"margin-top: -54px; left: -500px; font-size: 40px; position: absolute;\" id=\"" . $deleted_contract->get_deleted_contract_name() . "\" > span </p>";


			echo "<h3>" . $deleted_contract->get_deleted_contract_name() . "</h3>";
			echo "<pre>" . $deleted_contract->get_contract_details() . "</pre> <br> ";
		}

		?>

		<br>


	</body>

</html>