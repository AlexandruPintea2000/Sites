<!DOCTYPE html>

<html>

	<?php

	if ( isset( $_GET[ 'id' ] ) )
	{
        $data = "";
        foreach ( $_GET as $i )
        {
            if ( ! empty( $i ) and $i != "Submit" )
                $data = $data . $i . "(@)";

            if ( empty( $i ) )
    	        $data = $data . "empty(@)";
         }

        echo "<script> location.replace( \"/Procres/index.php?path=task_controller/create_task/" . $data . "\" ); </script>";
    }

	?>

	<head>
		<title> Make Task </title>
		<link rel="stylesheet" href="/Procres/files/style.css">
		<meta name="viewport" content="width=device-width, initial-scale=1">
	</head>


	<body>

		<?php $this->show_nav(); ?>

		<div class="company"> <?php echo $this->data[ 'company' ]; ?> </div>
		<?php if ( $_SESSION[ 'type' ] != "admin" ) echo "<style> #left_nav { display: none !important; } html { padding-left: 10px !important; } </style>"; ?>
		<a class="signed_in_user" href=<?php echo "\"index.php?path=user_controller/view/" . $_SESSION[ 'id' ] . "\""; ?> > <?php echo $_SESSION[ 'username' ]; ?> </a>


		<div class="center" >

			<h1> Make Task </h1>

	    </div>


	        <form action="views/task/create.php" >

	            <input type="number" name="id" placeholder="id" value=<?php echo $this->data[ 'task_id' ]; ?> hidden> </input>


	            <b>User:</b> 

	            <select name="user">

	            <?php 

	            $admins = $this->data[ 'admins' ];

	            foreach ( $admins as $admin )
	            {
	            	echo "<option value=\"" . $admin->get_id() . "\"";

	            	if ( $admin->get_id() == $this->data[ 'user_id' ] )
	            		echo "selected";

	            	echo "> " . $admin->get_firstname() . ' ' . $admin->get_lastname() . " </option>";
	            }

	            $employees = $this->data[ 'employees' ];

	            foreach ( $employees as $employee )
	            {
	            	echo "<option value=\"" . $employee->get_id() . "\"";

	            	if ( $employee->get_id() == $this->data[ 'user_id' ] )
	            		echo "selected";

	            	echo "> " . $employee->get_firstname() . ' ' . $employee->get_lastname() . " </option>";
	            }

	            ?>

	            </select>

	             - <a href="index.php?path=user_controller/create/employee"> Add User </a> <br>



	            <b>Part:</b> 

	            <select name="part">

	            <?php 

	            $parts = $this->data[ 'parts' ];
	            $contracts = $this->data[ 'contracts' ];


	            function get_contract_name_through_id ( $contracts, int $id )
	            {

	            	foreach ( $contracts as $contract )
	            	{
	            		if ( $contract->get_id() == $id )
	            			return $contract->get_contract_name();
	            	}

	            	return "Unavailable";
	            }

	            echo count( $this->data[ 'parts' ] );

	            foreach ( $parts as $part )
	            {	
	            	echo "<option value=\"" . $part->get_id() . "\"";

	            	if ( $part->get_id() == $this->data[ 'part_id' ] )
	            		echo "selected";

	            	echo "> " . $part->get_part_name() . " ( of contract: \"" . get_contract_name_through_id( $contracts, $part->get_contract_id() ) . "\" ) </option>";
	            }

	            ?>

	            </select>

	             - <a href="index.php?path=contract_controller"> View Contracts</a> /
	               <a href="index.php?path=contract_controller/create"> Add Contract</a>  <br>

	            <br> 

	            <input class="submit" type="submit" name="Submit" value="Submit"> </input>

	        </form>



	</body>

</html>