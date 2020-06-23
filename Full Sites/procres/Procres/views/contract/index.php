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


		

		<div class="company"> <?php echo $this->data[ 'company' ]; ?> </div>
		<?php if ( $_SESSION[ 'type' ] != "admin" ) echo "<style> #left_nav { display: none !important; } html { padding-left: 10px !important; } </style>"; ?>
		<a class="signed_in_user" href=<?php echo "\"index.php?path=user_controller/view/" . $_SESSION[ 'id' ] . "\""; ?> > <?php echo $_SESSION[ 'username' ]; ?> </a>



		<div class="center" >

			<h1> <?php echo $this->data[ 'title' ] ?> </h1>

			<?php

			if ( $_SESSION[ 'type' ] == "admin" )
			{
				echo "<h3 style=\"font-size: 17px; margin: 0px;\"> ";

				if ( isset( $this->data[ 'delete' ] ) )
				{
					if ( $this->data[ 'delete' ] == "obsolete" )
					echo " <a href=\"index.php?path=contract_controller/delete_obsolete_finalised/obsolete\"> Delete All \"Obsolete\" Contracts </a> ";

					if ( $this->data[ 'delete' ] == "finalised" )
					echo " <a href=\"index.php?path=contract_controller/delete_obsolete_finalised/finalised\"> Delete All \"Finalised\" Contracts </a> ";

					echo "<br> <span style=\"font-size: 14px;\"> ( add contracts to \"Deleted Contracts\" only when sure ) </span> </h3>";
				}
				else
					echo "Obsolete / Done contracts: Delete<br> <span style=\"font-size: 10.9px;\"> ( add contracts to \"Deleted Contracts\" only when sure ) </span> </h3>";

				echo "";
			}

			?>

	    </div>

	    <form action="views/contract/index.php">
		    <input style="margin-left: 2.5%;" name="search" placeholder="Search">  </input>
		    <input type="submit" style="font-weight: bold;" name="Submit" value="Search" >  </input>
	    </form>


	    <p class="table_p" style="font-size: 14px;">


	    <?php 
	
	    if ( $_SESSION[ 'type' ] == "admin" )
	    {
			echo "<a class=\"add_part_href\" style=\"padding: 3px 10px; margin-right: 5px; background-color: rgb( 243, 243, 243 ); font-size: 17px; border: 2px solid rgb( 204, 204, 204 );\" title=\"Make contract\" href=\"index.php?path=contract_controller/create\" > Make Contract</a> ";
	    }

	    ?>

			<a title="All Contracts" href="index.php?path=contract_controller/index" > All Contracts <!-- <span style="color: rgb( 90, 90, 90 );">( Default )</span> --></a> /

			<a title="Obsolete Contracts" href="index.php?path=contract_controller/index/obsolete" > Obsolete Contracts</a> /

			<a title="Final Month Contracts" href="index.php?path=contract_controller/index/final_month" > Final Month Contracts</a> /

			<a title="Interesting Contracts" href="index.php?path=contract_controller/index/interesting" > Interesting Contracts</a> /

			<a title="Finalised Contracts" href="index.php?path=contract_controller/index/finalised" > Finalised Contracts</a> /

			<a title="Deleted Contracts" style="line-break: none;" href="index.php?path=contract_controller/view_deleted_contracts" > ( Deleted Contracts )</a> 


		</p>

		<div style="height: 5px;"> </div>


	    <?php

	    echo "<p class=\"order\">";

		echo "<b> Ascen: </b>";
		echo "<a title=\"Completion\" href=\"index.php?path=contract_controller/index/completion(@)asc\" > Completion</a> / ";
		echo "<a title=\"Deadline\" href=\"index.php?path=contract_controller/index/deadline(@)asc\" > Deadline <span style=\"color: rgb( 90, 90, 90 );\">( Default )</span></a> / ";
		echo "<a title=\"Contract Date\" href=\"index.php?path=contract_controller/index/contract_date(@)asc\" > Contract Date</a> / ";
		echo "<a title=\"Contract Name\" href=\"index.php?path=contract_controller/index/contract_name(@)asc\" > Contract Name</a>  ";

		echo "<br>";

		echo "<b> Descen: </b>";
		echo "<a title=\"Completion\" href=\"index.php?path=contract_controller/index/completion(@)desc\" > Completion</a> / ";
		echo "<a title=\"Deadline\" href=\"index.php?path=contract_controller/index/deadline(@)desc\" > Deadline</a> / ";
		echo "<a title=\"Contract Date\" href=\"index.php?path=contract_controller/index/contract_date(@)desc\" > Contract Date</a> / ";
		echo "<a title=\"Contract Name\" href=\"index.php?path=contract_controller/index/contract_name(@)desc\" > Contract Name</a>  ";


	    echo "</p>";



		?>




	    <div class="table_center">

	    <?php 


	    $have_search = false;
	    $search_params = [];

 		if ( ! empty( $this->data[ 'search' ] ) )
 		{
			$have_search = true;

			$search_params = explode( " ", $this->data[ 'search' ] );
 		}



		$contracts = $this->data[ 'contracts' ];

		echo "<table>";

		echo "<tr class=\"th\"> <td> Contract Name </td> <td id=\"th_parts\"> Parts </td> <td> Client </td> <td id=\"th_contract_date\"> Contract Date </td>  <td> Deadline Date </td> 

			<!-- <td> Id </td> --> 

			<td id=\"contract_table_options\" class=\"table_options\"> Options </td> </tr>";

		echo "<tr></tr>";

		if ( count( $contracts ) == 0 )
			echo "<tr style=\"text-align: center; font-size: 10.9px; color: rgb( 150, 150, 150 ); font-weight: bold;\"> 
					<td> Empty </td> 
					<td> Empty </td> 
					<td> Empty </td> 
					<td id=\"th_contract_date\"> Empty </td> 
					<td> Empty </td> 
					<td style=\"color: rgb( 123, 123, 123 );\"> Invalid </td> 
				</tr>";


		include_once "models/User.php";

		foreach ( $contracts as $contract )
		{
	    	include_once "controllers/Search_controller.php";
			if ( $have_search == true and is_contract_result( $contract, $search_params ) == false )
				continue;


			if ( contract_finalised( $contract->get_id() ) or contract_obsolete( $contract->get_id() ) )
			{

				if ( contract_finalised( $contract->get_id() ) and ! contract_obsolete( $contract->get_id() ) )
					echo "<tr style=\"background-color: rgb( 204, 204, 204 );\" ";

				if ( ! contract_finalised( $contract->get_id() ) and contract_obsolete( $contract->get_id() ) )
					echo "<tr style=\"background-color: rgb( 234, 234, 234 );\" ";

				if ( contract_finalised( $contract->get_id() ) and contract_obsolete( $contract->get_id() ) )
					echo "<tr style=\"background-color: rgb( 190, 190, 190 );\" ";
			}
			else
				echo "<tr ";



			echo " title=\"" . $contract->get_contract_name();

			$parts = get_parts_through_contract_id( $contract->get_id() );
			if ( count( $parts ) != 0 )
			{
				echo " has parts:\n";

				foreach ( $parts as $part )
				{
					echo "  '" . $part->get_part_name() . "' " . $part->get_progress() . " / 100\n";
				}
			}
			else
				echo " does not have parts! Please Add.";


			echo "\" > ";

			echo "<td class=\"href\"> <a style=\"text-decoration: underline; font-weight: 500;\" href=\"index.php?path=contract_controller/view/" . $contract->get_id() . "\"> " . $contract->get_contract_name() . "</a> ";








				$contract_completion = 0;

				$contract_parts = get_parts_through_contract_id( $contract->get_id() );

				if ( count( $contract_parts ) != 0 )
				{
					foreach ( $contract_parts as $contract_part )
					{
						$contract_completion = $contract_completion + $contract_part->get_progress();
					}

					$contract_completion = $contract_completion / count( $contract_parts );
				}

				$contract_completion = (int) $contract_completion;

				echo "<b class=\"contract_completion\" style=\"color: rgb( 150, 150, 150 ); display: inline-block;\">( " . $contract_completion . "% )</b> ";









			if ( contract_finalised( $contract->get_id() ) or contract_obsolete( $contract->get_id() ) )
			{
				if ( contract_finalised( $contract->get_id() ) and ! contract_obsolete( $contract->get_id() ) )
					echo "<span style=\"color: rgb( 123, 123, 123 ); font-size: 10px; font-weight: bold; display: inline-block;\"> ( Done ) ";

				if ( ! contract_finalised( $contract->get_id() ) and contract_obsolete( $contract->get_id() ) )
					echo "<span style=\"color: rgb( 123, 123, 123 ); font-size: 10px; display: inline-block;\"> ( Obsolete ) ";

				if ( contract_finalised( $contract->get_id() ) and contract_obsolete( $contract->get_id() ) )
					echo "<span style=\"color: rgb( 123, 123, 123 ); font-size: 10px; font-weight: bold; display: inline-block;\"> ( Done and Obsolete ) ";

				echo " </span>";
			}



			if ( contract_final_month( $contract->get_id() ) )
			{
					echo "<b style=\"color: rgb( 123, 123, 123 ); font-size: 10px; display: inline-block;\"> Final Month! </b> ";					
			}

			echo " </td>";
			echo "<td style=\"text-align: center;\" id=\"td_parts\" class=\"href\"> <a href=\"index.php?path=contract_controller/parts/" . $contract->get_id() . "\"> View parts</a>";

			// Contract Progress

			// echo " <span style=\"color: rgb( 123, 123, 123 );\"><b>" . $contract_completion . "% </b></span>";

			if ( count( $parts ) != 0 )
				echo " <span style=\"color: rgb( 123, 123, 123 );\"><b>( " . count( $parts ) . " )</b></span>";
			else
				echo " <span style=\"color: rgb( 123, 123, 123 );\"><b>( / )</b></span>";

			echo "</td>";




			$client = get_user_through_id( $contract->get_client_id() );

			if ( $client != null )
				echo "<td class=\"href\"> <a href=\"index.php?path=user_controller/view/" . $contract->get_client_id() . "\"> " . $client->get_firstname() . ' ' . $client->get_lastname() . " </td>";
			else
				echo "<td> Client not available </td>";

			echo "<td id=\"td_contract_date\">" . $contract->get_contract_date() . "</td>";

			if ( contract_finalised( $contract->get_id() ) or contract_obsolete( $contract->get_id() ) )
			{
				if ( contract_finalised( $contract->get_id() ) and ! contract_obsolete( $contract->get_id() ) )
					echo "<td>  ";

				if ( ! contract_finalised( $contract->get_id() ) and contract_obsolete( $contract->get_id() ) )
					echo "<td style=\"background-color: rgb( 243, 243, 243 ); font-size: 14px; font-weight: bold;\"> ";

				if ( contract_finalised( $contract->get_id() ) and contract_obsolete( $contract->get_id() ) )
					echo "<td>  ";

				echo " </span>";
			}

			else
				echo "<td>";

			echo $contract->get_deadline_date() . "</td>";

//			echo "<td>" . $contract->get_id() . "</td>";


			echo "<td id=\"contract_table_options\" class=\"table_options\">";
			if ( $client != null )
			{
				echo " <a href=\"index.php?path=contract_controller/view/" . $contract->get_id() . "\"> View </a>";
				if ( $_SESSION[ 'type' ] == "admin" )
					echo "/ <a href=\"index.php?path=contract_controller/edit/" . $contract->get_id() . "\"> Edit </a> ";
			}

			if ( $_SESSION[ 'type' ] == "admin" )
				echo " <span id=\"td_delete_contract_span\">/</span> <a id=\"td_delete_contract\" href=\"index.php?path=contract_controller/delete/" . $contract->get_id() . "\"> Delete </a> </td>";
			echo "</tr>";
		}
		echo "</table>";

	    ?>

		</div>

	</body>

</html>