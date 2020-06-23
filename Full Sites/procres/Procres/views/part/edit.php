<!DOCTYPE html>

<html>

	<?php

	if ( isset( $_GET[ 'part_name' ] ) )
	{
        $data = "";
        foreach ( $_GET as $i )
        {
            if ( ! empty( $i ) and $i != "Submit" )
                $data = $data . $i . "(@)";

            if ( empty( $i ) )
    	        $data = $data . "empty(@)";

         }

        echo "<script> location.replace( \"/Procres/index.php?path=part_controller/edit_part/" . $data . "\" ); </script>";
    }

	?>

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

			<h1> <?php echo $this->data[ 'title' ]; ?> </h1>

	    </div>

	        <form action="views/part/edit.php" style="background-color: rgb( 243, 243, 243 ); padding: 10px 70px 10px 70px; border: 3px solid rgb( 204, 204, 204 ); border-radius: 10px;" >

	            <input type="number" name="id" value=<?php echo $this->data[ 'id' ]; ?> hidden> </input> <br>


	            <b>Part Name:</b> <input type="text" name="part_name" value=<?php echo "\"" . $this->data[ 'part_name' ] . "\""; ?>> </input> <br>
	            <b>Contract:</b>


	            <select name="contract">

	            <?php 

	            $contracts = $this->data[ 'contracts' ];

	            foreach ( $contracts as $contract )
	            {
	            	echo "<option value=\"" . $contract->get_id() . "\"";

	            	if ( $contract->get_id() == $this->data[ 'contract_id' ] )
	            		echo "selected";

	            	echo "> " . $contract->get_contract_name() . " </option>";
	            }

	            ?>

	            </select>


	            <br>
				
				<b>Progress:</b> <input style="width: 70px;" type="number" name="progress" value=<?php echo $this->data[ 'progress' ]; ?> > </input> % <br> <br>



	            <input class="submit" type="submit" name="Submit" value="Submit"> </input>

	        </form>

	        <br>

			<a style="margin-left: 7%;" href=<?php echo "index.php?path=part_controller/view/" . $this->data[ 'id' ]; ?> > Return to View part </a>

	</body>

</html>