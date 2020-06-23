<!DOCTYPE html>

<html>

	<?php 

	if ( isset( $_GET[ 'search' ] ) )
	{
        $data = "";
        foreach ( $_GET as $i )
        {
            if ( ! empty( $i ) and $i != "Submit" )
                $data = $data . $i . "(@)";

            if ( empty( $i ) )
    	        $data = $data . "empty(@)";

         }

        echo "<script> location.replace( \"/Procres/index.php?path=contract_controller/search/" . $data . "\" ); </script>";
    }	

    

	?>

	<head>
		<title> <?php echo $this->data[ 'title' ] ?> </title>
		<link rel="stylesheet" href="/Procres/files/style.css">
		<meta name="viewport" content="width=device-width, initial-scale=1">
	</head>






	<body>


		<?php $this->show_nav(); ?>

		<div class="company"> <?php echo $this->data[ 'company' ]; ?> </div>
		<?php if ( $_SESSION[ 'type' ] != "admin" ) echo "<style> #left_nav { display: none !important; } html { padding-left: 10px !important; } </style>"; ?>
		<a class="signed_in_user" href=<?php echo "\"index.php?path=user_controller/view/" . $_SESSION[ 'id' ] . "\""; ?> > <?php echo $_SESSION[ 'username' ]; ?> </a>



		<div class="center" >

			<h1> <?php echo $this->data[ 'title' ] ?> </h1>

	    </div>

	    <form action="views/contract/index.php">
		    <input style="margin-left: 2.5%;" name="search" placeholder="Search">  </input>
		    <input type="submit" style="font-weight: bold;" name="Submit" value="Search" >  </input>
	    </form>


	    <?php 

	    if ( $_SESSION[ 'type' ] == "admin" )
			echo "<a style=\"margin-left: 2.5%;\" title=\"Make Contract\" href=\"index.php?path=contract_controller/create\" > Make Contarct</a>";

		?>


	    <br>

	    <div class="table_center">

	    <?php 


	    function is_result ( contract $contract, $search_params )
	    {

	    	foreach ( $search_params as $search_param )
	    	{
		    	if ( $contract->get_id() == $search_param )
		    		return true;
		    	if ( $contract->get_contract_name() == $search_param )
		    		return true;
		    	if ( $contract->get_details() == $search_param )
		    		return true;
		    	if ( $contract->get_client_id() == $search_param )
		    		return true;
		    	if ( $contract->get_contract_date() == $search_param )
		    		return true;
		    	if ( $contract->get_deadline_date() == $search_param )
		    		return true;
		    }

		    return false;
	    }

	    $have_search = false;
	    $search_params = [];

 		if ( ! empty( $this->data[ 'search' ] ) )
 		{
			$have_search = true;

			$search_params = explode( " ", $this->data[ 'search' ] );
 		}



		$contracts = $this->data[ 'contracts' ];

		echo "<table>";
		echo "<tr class=\"th\"> <td> Contract Name </td> <td> Parts </td> <td> Client </td>  <td> Contract Date </td>  <td> Deadline Date </td>  <td> Id </td> <td class=\"table_options\"> Options </td> </tr>";

		include_once "models/User.php";

		foreach ( $contracts as $contract )
		{
			if ( ! empty( $this->data[ 'type' ] ) and $contract->get_type() != $this->data[ 'type' ] )
				continue;

			if ( $have_search == true and is_result( $contract, $search_params ) == false )
				continue;

			echo "<tr>";


			echo "<td class=\"href\"> <a style=\"text-decoration: underline; font-weight: 500;\" href=\"index.php?path=contract_controller/view/" . $contract->get_id() . "\"> " . $contract->get_contract_name() . "</a> </td>";
			echo "<td class=\"href\"> <a href=\"index.php?path=contract_controller/parts/" . $contract->get_id() . "\"> View parts </a> </td>";

			$client = get_user_through_id( $contract->get_client_id() );

			if ( $client != null )
				echo "<td class=\"href\"> <a href=\"index.php?path=user_controller/view/" . $contract->get_client_id() . "\"> " . $client->get_firstname() . ' ' . $client->get_lastname() . " </td>";
			else
				echo "<td> Client not available </td>";

			echo "<td>" . $contract->get_contract_date() . "</td>";
			echo "<td>" . $contract->get_deadline_date() . "</td>";

			echo "<td>" . $contract->get_id() . "</td>";


			echo "<td class=\"table_options\">";
			if ( $client != null )
			{
				echo " <a href=\"index.php?path=contract_controller/view/" . $contract->get_id() . "\"> View </a>";
				if ( $_SESSION[ 'type' ] == "admin" )
					echo "/ <a href=\"index.php?path=contract_controller/edit/" . $contract->get_id() . "\"> Edit </a> /";
			}

			if ( $_SESSION[ 'type' ] == "admin" )
				echo " <a href=\"index.php?path=contract_controller/delete/" . $contract->get_id() . "\"> Delete </a> </td>";
			echo "</tr>";
		}
		echo "</table>";

	    ?>

		</div>

	</body>

</html>