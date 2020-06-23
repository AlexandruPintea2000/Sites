<!DOCTYPE html>

<html>

	<?php

	if ( isset( $_GET[ 'contract_name' ] ) )
	{
	    $data = "";

	    foreach ( $_GET as $i )
	    {




	    	if ( $_GET[ 'details' ] == $i )
	    	{
	            $data = $data . "details(@)";

				// $file_details = fopen( '../../details', 'r' );

				// $details = "";

				// while ( $temp = fgets( $file_details ) )
				// 	$details = $details . $temp;

				// fclose( $file_details );




				$details_file = fopen( '../../details', 'w' );

				// fwrite( $details_file, $details . "\n" . $_GET[ 'id' ] . "\n" . $_GET[ 'details' ] . "\n" . $_GET[ 'id' ] . "\n"  );

				fwrite( $details_file, $_GET[ 'details' ]  );

				fclose( $details_file );


	    		continue;
	    	}




	        if ( ! empty( $i ) and $i != "Submit" )
	            $data = $data . $i . "(@)";

	        if ( empty( $i ) )
		        $data = $data . "empty(@)";
	     }



		// $_SESSION[ 'make_contract' ] = $_GET;

		// $_SESSION[ 'contract_id' ] = $_GET[ 'id' ];

		// $_SESSION[ 'contract_name' ] = $_GET[ 'contract_name' ];
		// $_SESSION[ 'contract_details' ] = $_GET[ 'details' ];
		// $_SESSION[ 'contract_client_id' ] = $_GET[ 'client' ];
		// $_SESSION[ 'contract_date' ] = $_GET[ 'contract_date' ];
		// $_SESSION[ 'contract_deadline_date' ] = $_GET[ 'deadline_date' ];

        // echo "<script> alert( \"" . (int) isset( $_SESSION[ 'make_contract' ] ) . "\" ); </script>";
    echo "<script> location.replace( \"/Procres/index.php?path=contract_controller/create_contract/" . $data . "\" ); </script>";
	}

	?>

	<head>
		<title> Make Contract </title>
		<link rel="stylesheet" href="/Procres/files/style.css">
		<meta name="viewport" content="width=device-width, initial-scale=1">
	</head>


	<body>


		

		<div class="company"> <?php echo $this->data[ 'company' ]; ?> </div>
		<?php if ( $_SESSION[ 'type' ] != "admin" ) echo "<style> #left_nav { display: none !important; } html { padding-left: 10px !important; } </style>"; ?>
		<a class="signed_in_user" href=<?php echo "\"index.php?path=user_controller/view/" . $_SESSION[ 'id' ] . "\""; ?> > <?php echo $_SESSION[ 'username' ]; ?> </a>


		<div class="center" >

			<h1> Make Contract </h1>

	    </div>


	    <style>
	    	

	    	#contract_form
	    	{
	    		background-color: rgb( 243, 243, 243 ); 
	    		padding: 10px 70px 10px 70px; 
	    		border: 3px solid rgb( 204, 204, 204 ); 
	    		border-radius: 10px;
	    	}


	    </style>


	    <div class="div_center" >

	        <form id="contract_form" action="views/contract/create.php" >

	           	<input type="number" name="id" placeholder="id" value=<?php echo $this->data[ 'contract_id' ]; ?> hidden> </input>

	           	<!-- if contract was already taken, place data -->
	           	<?php 

	           	$taken = false;
	           	if ( isset( $this->data[ 'contract_name' ] ) ) 
	           		$taken = true;

	           	?>

	            <b>Contract name:</b> <input <?php if ( $taken ) echo " value=\"" . $this->data[ 'contract_name' ] . "\" "; ?> type="text" name="contract_name" placeholder="contract name" required> </input> <br> <br>
	            <b>Details: <span style="font-weight: 100; font-size: 10.9px;">( visible to client )</spam> </b> <br> <textarea name="details" rows=5 cols=40 ><?php if ( $taken ) echo $this->data[ 'details' ]; ?></textarea> <br> <br>
	            <b>Client: </b> 

	            <select name="client"> 

	            <?php 

	            $clients = $this->data[ 'clients' ];

	            foreach ( $clients as $client )
	            {
	            	$client_name = $client->get_firstname() . ' ' . $client->get_lastname();

	            	echo "<option value=\"" . $client->get_id() . "\" ";

	            	if ( isset( $this->data[ 'client_id' ] ) and $client->get_id() == $this->data[ 'client_id' ] ) echo "selected";

	            	echo "> " . $client_name . " </option>";
	            }

	            ?>

	            </select>

				- <a href="index.php?path=user_controller/create/client"> Add Client </a> <br>

	            <b>Contract Date:</b> <input <?php if ( $taken ) echo " value=\"" . $this->data[ 'contract_date' ] . "\" "; ?> type="date" name="contract_date" placeholder="contract date" required> </input> <br>
	            <b>Deadline Date:</b> <input <?php if ( $taken ) echo " value=\"" . $this->data[ 'deadline_date' ] . "\" "; ?> type="date" name="deadline_date" placeholder="deadline date" required> </input> <br> <br>


	            <input class="submit" type="submit" name="Submit" value="Submit"> </input>

	        </form>

	    </div>

	</body>

</html>