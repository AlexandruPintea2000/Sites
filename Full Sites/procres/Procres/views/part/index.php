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


		

		<div class="company"> <?php echo $this->data[ 'company' ]; ?> </div>
		<?php if ( $_SESSION[ 'type' ] != "admin" ) echo "<style> #left_nav { display: none !important; } html { padding-left: 10px !important; } </style>"; ?>
		<a class="signed_in_user" href=<?php echo "\"index.php?path=user_controller/view/" . $_SESSION[ 'id' ] . "\""; ?> > <?php echo $_SESSION[ 'username' ]; ?> </a>




		<div class="center" >

			<h1> <?php echo $this->data[ 'title' ] ?> </h1>

			<?php

			if ( $_SESSION[ 'type' ] == "admin" )
				echo "<h3 style=\"font-size: 14px; margin: 0px;\"> Obsolete / Done contracts: Delete Contract, not its parts <br> <span style=\"font-size: 10.9px;\"> ( add contract to \"Deleted Contracts\" only when sure ) </span> </h3>";

			?>


	    </div>

	    <form action="views/part/index.php">
		    <input style="margin-left: 2.5%;" name="search" placeholder="Search">  </input>
		    <input type="submit" style="font-weight: bold;" name="Submit" value="Search" >  </input>
	    </form>



	    <p class="table_p">


	    <?php 
	
	    if ( $_SESSION[ 'type' ] == "admin" )
	    {

			echo "<a class=\"add_part_href\" style=\"padding: 3px 10px; margin-right: 5px; background-color: rgb( 243, 243, 243 ); font-size: 17px; border: 2px solid rgb( 204, 204, 204 );\" title=\"Add part to contract\" href=\"index.php?path=part_controller/create\" > Make Contract Part</a> ";
	    }

	    ?>

			<a title="All Parts" href="index.php?path=part_controller/index" > All Parts <!-- <span style="color: rgb( 90, 90, 90 );">( Default )</span> --></a> /

			<a title="Obsolete Parts" href="index.php?path=part_controller/index/obsolete" > Obsolete Parts</a> /

			<a title="Final Month Parts" href="index.php?path=part_controller/index/final_month" > Final Month Parts</a> /

			<a title="Interesting Parts" href="index.php?path=part_controller/index/interesting" > Interesting Parts</a> /

			<a title="Finalised Parts" href="index.php?path=part_controller/index/finalised" > Finalised Parts</a> 

		</p>

		<div style="height: 5px;"> </div>

		<?php

		echo "<p class=\"order\">";

		echo "<b> Ascen: </b>";
		echo "<a title=\"Completion\" href=\"index.php?path=part_controller/index/completion(@)asc\" > Completion</a> / ";
		echo "<a title=\"Part Name\" href=\"index.php?path=part_controller/index/part_name(@)asc\" > Part Name</a> / ";
		echo "<a title=\"Contract Completion\" href=\"index.php?path=part_controller/index/contract_completion(@)asc\" > Contract Completion</a> / ";
		echo "<a title=\"Default\" href=\"index.php?path=part_controller/index\" > Contract Deadline <span style=\"color: rgb( 90, 90, 90 );\">( Default )</span> </a> ";


		echo "<br>";

		echo "<b> Descen: </b>";
		echo "<a title=\"Completion\" href=\"index.php?path=part_controller/index/completion(@)desc\" > Completion</a> / ";
		echo "<a title=\"Part Name\" href=\"index.php?path=part_controller/index/part_name(@)desc\" > Part Name</a> / ";
		echo "<a title=\"Contract Completion\" href=\"index.php?path=part_controller/index/contract_completion(@)desc\" > Contract Completion</a> ";


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



		$parts = $this->data[ 'parts' ];

		include_once "models/Contract.php";

		echo "<table>";
		echo "<tr class=\"th\">  <td> Contract </td> <td> Part Name </td> <td> Progress </td> 

			  <!-- <td> Id </td> --> 

			  <td class=\"table_options\"> Options </td> </tr>";


		if ( count( $parts ) == 0 )
			echo "<tr style=\"text-align: center; font-size: 10.9px; color: rgb( 150, 150, 150 ); font-weight: bold;\"> 
					<td> Empty </td> 
					<td> Empty </td> 
					<td> Empty </td> 
					<td style=\"color: rgb( 123, 123, 123 );\"> Invalid </td> 

				</tr>";

		foreach ( $parts as $part )
		{
			if ( $part->get_id() == -1 )
			{
				echo "<tr> </tr>";
				continue;
			}



	    	include_once "controllers/Search_controller.php";
			if ( $have_search == true and is_part_result( $part, $search_params ) == false )
				continue;

			// Contract obsolete / finalised

			if ( contract_obsolete( $part->get_contract_id() ) or contract_finalised ( $part->get_contract_id() ) )
			{

				if ( contract_finalised( $part->get_contract_id() ) and ! contract_obsolete( $part->get_contract_id() ) )
					echo "<tr style=\"background-color: rgb( 204, 204, 204 );\" ";

				if ( ! contract_finalised( $part->get_contract_id() ) and contract_obsolete( $part->get_contract_id() ) )
					echo "<tr style=\"background-color: rgb( 234, 234, 234 );\" ";

				if ( contract_finalised( $part->get_contract_id() ) and contract_obsolete( $part->get_contract_id() ) )
					echo "<tr style=\"background-color: rgb( 190, 190, 190 );\" ";
			}
			else
				echo "<tr ";

			$tasks = [];
			$tasks = get_tasks_through_part_id( $part->get_id() );
			$contract = get_contract_through_id( $part->get_contract_id() );


			echo " title=\"";

			if ( count( $tasks ) != 0 and ! empty( $tasks ) and $tasks != null )
			{
				echo "Users on this part ( '" . $part->get_part_name() . "' of '" . $contract->get_contract_name() . "' ):\n";

				foreach ( $tasks as $task )
				{
					$user = get_user_through_id( $task->get_user_id() );

					echo "  " . $user->get_firstname() . ' ' . $user->get_lastname() . ' ';

					include_once "controllers/Task_controller.php";
					$part_tasks = get_part_tasks_through_task_id_and_status( $task->get_id(), "given" );

					if ( count( $part_tasks ) != 0 )
					{
						echo '( ' . count( $part_tasks ) . " given tasks )";
					}
					else
						echo " Give part tasks!";

					echo "\n";
				}
			}
			else
				echo "Part does not have users. Please Add.";

			echo "\" > ";


			// $contract = get_contract_through_id( $part->get_contract_id() );

			if ( $contract != null )
			{
				echo "<td style=\"max-width: 140px;\" class=\"href\"> <a href=\"index.php?path=contract_controller/view/" . $contract->get_id() .  "\">" . $contract->get_contract_name() . "</a> ";




				$contract_completion = 0;

				$contract_parts = [];
				$contract_parts = get_parts_through_contract_id( $contract->get_id() );

				if ( count( $contract_parts ) != 0 )
				{
					foreach ( $contract_parts as $contract_part )
					{
						$contract_completion = $contract_completion + $contract_part->get_progress();
					}

					$contract_completion = $contract_completion / count( $contract_parts );
				}

				echo "<b class=\"contract_completion\" style=\"color: rgb( 150, 150, 150 );\">( " . (int) $contract_completion . "% )</b> ";




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

				echo" </td>";
			}
			else
				echo "<td> Contarct not available </td>";


			echo "<td class=\"href\"> <a href=\"index.php?path=part_controller/view/" . $part->get_id() . "\"> " . $part->get_part_name() . "</a> ";

			echo " <b style=\"";

			// if ( count( $tasks ) != 0 )
				echo "color: rgb( 123, 123, 123 );";
			// else
			// 	echo "color: rgb( 90, 90, 90 );";

			echo " font-size: 10.9px; padding: 2px 5px; display: inline-block;\"> ";

			if ( count( $tasks ) != 0 )
				echo count( $tasks ) . " Users ";
			else
				echo " Add Users! ";

			echo "</b>";

			echo " </td>";



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

			echo "<td class=\"table_options\" style=\"width: 159px;\">";
			if ( $contract != null )
			{
				echo " <a href=\"index.php?path=part_controller/view/" . $part->get_id() . "\"> View </a> ";
				// if ( $_SESSION[ 'type' ] == "admin" )
				// 	echo "/ <a href=\"index.php?path=part_controller/edit/" . $part->get_id() . "\"> Edit </a>";
				if ( $_SESSION[ 'type' ] == "admin" )
					echo "/ <a href=\"index.php?path=task_controller/create/part(@)" . $part->get_id() . "\"> Add User </a>";
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