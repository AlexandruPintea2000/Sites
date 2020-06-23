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

        echo "<script> location.replace( \"/Procres/index.php?path=part_controller/search/" . $data . "\" ); </script>";
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

	    <form action="views/part/index.php">
		    <input style="margin-left: 2.5%;" name="search" placeholder="Search">  </input>
		    <input type="submit" style="font-weight: bold;" name="Submit" value="Search" >  </input>
	    </form>


	    <?php 

	    if ( $_SESSION[ 'type' ] == "admin" )
			echo "<a style=\"margin-left: 2.5%;\" title=\"Make Part\" href=\"index.php?path=part_controller/create\" > Make Part</a>";

		?>



	    <br>

	    <div class="table_center">

	    <?php 


	    function is_result ( part $part, $search_params )
	    {

	    	foreach ( $search_params as $search_param )
	    	{
		    	if ( $part->get_id() == $search_param )
		    		return true;
		    	if ( $part->get_part_name() == $search_param )
		    		return true;
		    	if ( $part->get_contract_id() == $search_param )
		    		return true;
		    	if ( $part->get_progress() == $search_param )
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



		$parts = $this->data[ 'parts' ];

		include_once "models/Contract.php";

		echo "<table>";
		echo "<tr class=\"th\"> <td> Part Name </td> <td> Contract </td> <td> Progress </td> <td> Id </td> <td class=\"table_options\"> Options </td> </tr>";
		foreach ( $parts as $part )
		{
			if ( $have_search == true and is_result( $part, $search_params ) == false )
				continue;

			echo "<tr>";


			echo "<td class=\"href\"> <a href=\"index.php?path=part_controller/view/" . $part->get_id() . "\"> " . $part->get_part_name() . "</a> </td>";

			$contract = get_contract_through_id( $part->get_contract_id() );

			if ( $contract != null )
				echo "<td class=\"href\"> <a href=\"index.php?path=contract_controller/view/" . $contract->get_id() .  "\">" . $contract->get_contract_name() . "</a> </td>";
			else
				echo "<td> Contarct not available </td>";

			echo "<td>" . $part->get_progress() . "% </td>";

			echo "<td>" . $part->get_id() . "</td>";

			echo "<td class=\"table_options\">";
			if ( $contract != null )
			{
				echo " <a href=\"index.php?path=part_controller/view/" . $part->get_id() . "\"> View </a> ";
				if ( $_SESSION[ 'type' ] == "admin" )
					echo "/ <a href=\"index.php?path=part_controller/edit/" . $part->get_id() . "\"> Edit </a> /";
			}
			if ( $_SESSION[ 'type' ] == "admin" )
				echo " <a href=\"index.php?path=part_controller/delete/" . $part->get_id() . "\"> Delete </a> </td>";
			echo "</tr>";
		}
		echo "</table>";

	    ?>

		</div>

	</body>

</html>