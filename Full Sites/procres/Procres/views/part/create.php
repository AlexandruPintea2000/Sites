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

        echo "<script> location.replace( \"/Procres/index.php?path=part_controller/create_part/" . $data . "\" ); </script>";
    }

	?>

	<head>
		<title> Make Part </title>
		<link rel="stylesheet" href="/Procres/files/style.css">
		<meta name="viewport" content="width=device-width, initial-scale=1">
	</head>

	<body>

		

		<div class="company"> <?php echo $this->data[ 'company' ]; ?> </div>
		<?php if ( $_SESSION[ 'type' ] != "admin" ) echo "<style> #left_nav { display: none !important; } html { padding-left: 10px !important; } </style>"; ?>
		<a class="signed_in_user" href=<?php echo "\"index.php?path=user_controller/view/" . $_SESSION[ 'id' ] . "\""; ?> > <?php echo $_SESSION[ 'username' ]; ?> </a>


		<div class="center" >

			<h1> Make Part / Add it to a Contract </h1>
	    	<div style="height: 5px;"></div>

	    </div>


	    <div class="div_center">

	        <form action="views/part/create.php" style="background-color: rgb( 243, 243, 243 ); padding: 10px 70px 10px 70px; border: 3px solid rgb( 204, 204, 204 ); border-radius: 10px;" >

	            <input type="text" name="id" placeholder="id" value=<?php echo $this->data[ 'part_id' ]; ?> hidden> </input>

	            <b>Part name:</b> <input type="text" name="part_name" placeholder="part name" required> </input> <br>
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

	             - <a href="index.php?path=contract_controller/create"> Add Contract </a> <br>
	            
	            <input type="hidden" name="progress" placeholder="progress" value=0 required> </input> <br> 

	            <input class="submit" type="submit" name="Submit" value="Submit"> </input>

	        </form>

	    </div>


	</body>

</html>