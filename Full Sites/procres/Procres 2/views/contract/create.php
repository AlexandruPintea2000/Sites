<!DOCTYPE html>

<html>

	<?php

	if ( isset( $_GET[ 'contract_name' ] ) )
	{
	    $data = "";

	    foreach ( $_GET as $i )
	    {
	        if ( ! empty( $i ) and $i != "Submit" )
	            $data = $data . $i . "(@)";

	        if ( empty( $i ) )
		        $data = $data . "empty(@)";

	     }

	    echo "<script> location.replace( \"/Procres/index.php?path=contract_controller/create_contract/	" . $data . "\" ); </script>";
	}

	?>

	<head>
		<title> Make Contract </title>
		<link rel="stylesheet" href="/Procres/files/style.css">
		<meta name="viewport" content="width=device-width, initial-scale=1">
	</head>


	<body>

		<?php $this->show_nav(); ?>

		<div class="company"> <?php echo $this->data[ 'company' ]; ?> </div>
		<?php if ( $_SESSION[ 'type' ] != "admin" ) echo "<style> #left_nav { display: none !important; } html { padding-left: 10px !important; } </style>"; ?>
		<a class="signed_in_user" href=<?php echo "\"index.php?path=user_controller/view/" . $_SESSION[ 'id' ] . "\""; ?> > <?php echo $_SESSION[ 'username' ]; ?> </a>


		<div class="center" >

			<h1> Make Contract </h1>

	    </div>


	        <form action="views/contract/create.php" >

	           	<input type="number" name="id" placeholder="id" value=<?php echo $this->data[ 'contract_id' ]; ?> hidden> </input>

	            <b>Contract name:</b> <input type="text" name="contract_name" placeholder="contract name" required> </input> <br> <br>
	            <b>Details:</b> <br> <textarea name="details" rows=5 cols=40 ></textarea> <br> <br>
	            <b>Client: </b> 

	            <select name="client"> 

	            <?php 

	            $clients = $this->data[ 'clients' ];

	            foreach ( $clients as $client )
	            {
	            	$client_name = $client->get_firstname() . ' ' . $client->get_lastname();

	            	echo "<option value=\"" . $client->get_id() . "\"> " . $client_name . " </option>";
	            }

	            ?>

	            </select>

				- <a href="index.php?path=user_controller/create/client"> Add Client </a> <br>

	            <b>Contract Date:</b> <input type="date" name="contract_date" placeholder="contract date" required> </input> <br>
	            <b>Deadline Date:</b> <input type="date" name="deadline_date" placeholder="deadline date" required> </input> <br> <br>


	            <input class="submit" type="submit" name="Submit" value="Submit"> </input>

	        </form>



	</body>

</html>