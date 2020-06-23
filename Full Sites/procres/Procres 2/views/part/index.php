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

			<?php

			if ( $_SESSION[ 'type' ] == "admin" )
				echo "<h3> Obsolete / Done contracts: Delete Contract, not its parts <br> <span style=\"font-size: 14px;\"> ( add contract to \"Deleted Contracts\" only when sure ) </span> </h3>";

			?>


	    </div>

	    <form action="views/part/index.php">
		    <input style="margin-left: 2.5%;" name="search" placeholder="Search">  </input>
		    <input type="submit" style="font-weight: bold;" name="Submit" value="Search" >  </input>
	    </form>


	    <?php 

	    if ( $_SESSION[ 'type' ] == "admin" )
			echo "<a style=\"margin-left: 2.5%;\" title=\"Make Part\" href=\"index.php?path=part_controller/create\" > Make Part</a>";

//	    if ( $_SESSION[ 'type' ] == "admin" )
//			echo "<a style=\"margin-left: 2.5%;\" title=\"Make Part\" href=\"index.php?path=part_controller/create\" > Make Part</a> / <b>Obsolete or Done contracts:</b> Delete Contract, not its parts <span style=\"font-size: 14px; line-height: 1px;\"> ( add contract to \"Deleted Contracts\" only when sure ) </span> ";


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
		echo "<tr class=\"th\">  <td> Contract </td> <td> Part Name </td> <td> Progress </td> 

			  <!-- <td> Id </td> --> 

			  <td class=\"table_options\"> Options </td> </tr>";

		foreach ( $parts as $part )
		{
			if ( $have_search == true and is_result( $part, $search_params ) == false )
				continue;

			// Contract obsolete / finalised

			if ( contract_obsolete( $part->get_contract_id() ) or contract_finalised ( $part->get_contract_id() ) )
			{

				if ( contract_finalised( $part->get_contract_id() ) and ! contract_obsolete( $part->get_contract_id() ) )
					echo "<tr style=\"background-color: rgb( 204, 204, 204 );\">";

				if ( ! contract_finalised( $part->get_contract_id() ) and contract_obsolete( $part->get_contract_id() ) )
					echo "<tr style=\"background-color: rgb( 234, 234, 234 );\">";

				if ( contract_finalised( $part->get_contract_id() ) and contract_obsolete( $part->get_contract_id() ) )
					echo "<tr style=\"background-color: rgb( 190, 190, 190 );\">";
			}
			else
				echo "<tr>";

			$contract = get_contract_through_id( $part->get_contract_id() );

			if ( $contract != null )
			{
				echo "<td class=\"href\"> <a href=\"index.php?path=contract_controller/view/" . $contract->get_id() .  "\">" . $contract->get_contract_name() . "</a> ";

				if ( contract_finalised( $contract->get_id() ) or contract_obsolete( $contract->get_id() ) )
				{
					if ( contract_finalised( $contract->get_id() ) and ! contract_obsolete( $contract->get_id() ) )
						echo "<span style=\"color: rgb( 123, 123, 123 ); font-size: 10px; font-weight: bold;\"> ( Done ) ";

					if ( ! contract_finalised( $contract->get_id() ) and contract_obsolete( $contract->get_id() ) )
						echo "<span style=\"color: rgb( 123, 123, 123 ); font-size: 10px;\"> ( Obsolete ) ";

					if ( contract_finalised( $contract->get_id() ) and contract_obsolete( $contract->get_id() ) )
						echo "<span style=\"color: rgb( 123, 123, 123 ); font-size: 10px; font-weight: bold;\"> ( Done and Obsolete ) ";

					echo " </span>";
				}



				if ( contract_final_month( $contract->get_id() ) )
				{
					echo "<b style=\"color: rgb( 123, 123, 123 ); font-size: 10px;\"> Final Month! </b> ";					
				}

				echo" </td>";
			}
			else
				echo "<td> Contarct not available </td>";


			echo "<td class=\"href\"> <a href=\"index.php?path=part_controller/view/" . $part->get_id() . "\"> " . $part->get_part_name() . "</a> </td>";

			if ( $part->get_progress() != 100 )
			{
				echo "<td style=\"min-width: 240px;\">
						<div class=\"progress_div\" style=\"padding: 0px;\"> 
							<div class=\"behind_progress\" style=\"padding: 0px; width: 100% !important;\">
								<div class=\"progress\" style=\"width: " . $part->get_progress() . "%; ";

									if ( contract_obsolete( $part->get_contract_id() ) )
										echo "background-color: rgb( 204, 204, 204 ); color: rgb( 123, 123, 123 );";

									echo " \" > 
									<span> " . $part->get_progress() . "% </span> 
								</div> 
							</div> 
						</div> 
					</td>";
			}
			else
				echo "<td style=\"line-height: 30px; text-align: center; font-weight: bold; max-width: 50px;\"> <span style=\"background-color: rgb( 243, 243, 243 ); padding: 5px 15px; border-radius: 10px; color: rgb( 123, 123, 123 );\"> Completed </span> </td> ";

//			echo "<td>" . $part->get_id() . "</td>";

			echo "<td class=\"table_options\" style=\"min-width: 90px;\">";
			if ( $contract != null )
			{
				echo " <a href=\"index.php?path=part_controller/view/" . $part->get_id() . "\"> View </a> ";
				if ( $_SESSION[ 'type' ] == "admin" )
					echo "/ <a href=\"index.php?path=part_controller/edit/" . $part->get_id() . "\"> Edit </a>";
			}

// Prevent admin user deleting parts without adding contract to "Deleted Contracts"

//			if ( $_SESSION[ 'type' ] == "admin" )
//				echo " / <a href=\"index.php?path=part_controller/delete/" . $part->get_id() . "\"> Delete </a> </td>";
//			else
				echo "</td>";

			echo "</tr>";
		}
		echo "</table>";

	    ?>

		</div>

	</body>

</html>