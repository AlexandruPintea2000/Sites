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

        echo "<script> location.replace( \"/Procres/index.php?path=contract_controller/edit_contract/" . $data . "\" ); </script>";
    }

	?>

	<head>
		<title> <?php echo $this->data[ 'title' ]; ?> </title>
		<link rel="stylesheet" href="/Procres/files/style.css">
		<meta name="viewport" content="width=device-width, initial-scale=1">
	</head>


	<body>

		<?php $this->show_nav(); ?>

		<div class="company"> <?php echo $this->data[ 'company' ]; ?> </div>
		<?php if ( $_SESSION[ 'type' ] != "admin" ) echo "<style> #left_nav { display: none !important; } html { padding-left: 10px !important; } </style>"; ?>
		<a class="signed_in_user" href=<?php echo "\"index.php?path=user_controller/view/" . $_SESSION[ 'id' ] . "\""; ?> > <?php echo $_SESSION[ 'username' ]; ?> </a>


		<div class="center" >

			<h1> <?php echo $this->data[ 'title' ]; ?> </h1>

	    </div>


	        <form action="views/contract/edit.php" >

	            <input type="number" name="id" value=<?php echo $this->data[ 'id' ]; ?> hidden> </input> <br>


	            <b>Contract Name:</b> <input type="text" name="contract_name" value=<?php echo $this->data[ 'contract_name' ]; ?>> </input> <br> <br>
				<b>Details:</b> <br> <textarea name="details" rows=5 cols=40 ><?php echo $this->data[ 'details' ]; ?></textarea> <br> <br>


	            <b>Client: </b> 

	            <select name="client"> 

	            <?php 

	            $clients = $this->data[ 'clients' ];

	            foreach ( $clients as $client )
	            {
	            	$client_name = $client->get_firstname() . ' ' . $client->get_lastname();

	            	echo "<option value=\"" . $client->get_id() . "\"";

	            	if ( $client->get_id() == $this->data[ 'client_id' ] )
	            		echo "selected";

	            	echo "> " . $client_name . " </option>";
	            }

	            ?>

	            </select>

	            <br>

	            <b>Contract Date:</b> <input type="date" name="contract_date" value=<?php echo $this->data[ 'contract_date' ]; ?> > </input> <br>
	            <b>Deadline Date:</b> <input type="date" name="deadline_date" value=<?php echo $this->data[ 'deadline_date' ]; ?> > </input> <br> <br>
				



	            <input class="submit" type="submit" name="Submit" value="Submit"> </input>

	        </form>

	        <br>

		<a href=<?php echo "index.php?path=contract_controller/view/" . $this->data[ 'id' ]; ?> > Return to View contract </a>

	</body>

</html>