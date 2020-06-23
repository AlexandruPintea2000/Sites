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

    	

        echo "<script> location.replace( \"/Procres/index.php?path=task_controller/search/" . $data . "\" ); </script>";
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
				echo "<h3 style=\"font-size: 14px; margin: 0px;\"> Obsolete / Done contracts: Delete Contract, not its tasks <br> <span style=\"font-size: 10.9px;\"> ( add contract to \"Deleted Contracts\" only when sure ) </span> </h3>";

			?>


	    </div>

	    <form action="views/task/index.php">
		    <input style="margin-left: 2.5%;" name="search" placeholder="Search">  </input>
		    <input type="submit" style="font-weight: bold;" name="Submit" value="Search" >  </input>
	    </form>


	    <p class="table_p">


	    <?php 
	
	    if ( $_SESSION[ 'type' ] == "admin" )
	    {
			echo "<a class=\"add_part_href\" style=\"padding: 3px 10px; margin-right: 5px; background-color: rgb( 243, 243, 243 ); font-size: 17px; border: 2px solid rgb( 204, 204, 204 );\" title=\"Add Part to User\" href=\"index.php?path=task_controller/create\" > Add Part to User </a> ";
	    }

	    ?>

			<a title="All Tasks" href="index.php?path=task_controller/index" > All Tasks <!-- <span style="color: rgb( 90, 90, 90 );">( Default )</span> --></a> /

			<a title="Obsolete Tasks" href="index.php?path=task_controller/index/obsolete" > Obsolete Tasks</a> /

			<a title="Final Month Tasks" href="index.php?path=task_controller/index/final_month" > Final Month Tasks</a> /

			<a title="Interesting Tasks" href="index.php?path=task_controller/index/interesting" > Interesting Tasks</a> /

			<a title="Finalised Tasks" href="index.php?path=task_controller/index/finalised" > Finalised Tasks</a> 

		</p>

		<div style="height: 5px;"> </div>


		<?php

		echo "<p class=\"order\">";

		echo "<b> Ascen: </b>";
		echo "<a title=\"Completion\" href=\"index.php?path=task_controller/index/completion(@)asc\" > Completion</a> / ";

		echo "<a title=\"Contract Deadline\" href=\"index.php?path=task_controller/index/contract_deadline(@)asc\" > Contract Deadline</a> / ";
		echo "<a title=\"Contract Completion\" href=\"index.php?path=task_controller/index/contract_completion(@)asc\" > Contract Completion</a> / ";
		echo "<a title=\"Users\" href=\"index.php?path=task_controller/index\" > Users <span style=\"color: rgb( 90, 90, 90 );\">( Default )</span></a>  ";

		echo "<br>";

		echo "<b> Descen: </b>";
		echo "<a title=\"Completion\" href=\"index.php?path=task_controller/index/completion(@)desc\" > Completion</a> / ";
		echo "<a title=\"Contract Deadline\" href=\"index.php?path=task_controller/index/contract_deadline(@)desc\" > Contract Deadline</a> / ";
		echo "<a title=\"Contract Completion\" href=\"index.php?path=task_controller/index/contract_completion(@)desc\" > Contract Completion</a> ";

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



		$tasks = $this->data[ 'tasks' ];

		include_once "models/Part.php";
		include_once "models/User.php";

		echo "<table>";
		echo "<tr class=\"th\"> <td> User </td> <td> Contract </td> <td> Part </td> <td> Given </td> <td id=\"td_completed\"> Completed </td> 

			  <!-- <td> Id </td> --> 

			  <td class=\"table_options\"> Options </td> </tr>";

		if ( count( $tasks ) == 0 )
			echo "<tr style=\"text-align: center; font-size: 10.9px; color: rgb( 150, 150, 150 ); font-weight: bold;\"> 
					<td> Empty </td> 
					<td> Empty </td> 
					<td> Empty </td> 
					<td> Empty </td> 
					<td id=\"td_completed\"> Empty </td> 
					<td style=\"color: rgb( 123, 123, 123 );\"> Invalid </td> 
				</tr>";

		foreach ( $tasks as $task )
		{
			if ( $task->get_id() == -1 )
			{
				// Users


				// echo "<tr></tr>";

				// echo "<tr style=\"font-size: 15.9px; text-align: center; \">

				// 	<td style=\"padding: 0px !important;\">
				// 		<div style=\"margin: 0px !important; padding: 10px; background-color: rgb( 150, 150, 150 );\">
				// 			<span style=\"background-color: rgb( 250, 250, 250 ); border-radius: 10px; padding: 5px 20px;\">";


				// echo $task->get_details();


				// echo"</div> </span> </td>

				//  </tr>";

				echo "<tr></tr>";

				continue;
			}



	    	include_once "controllers/Search_controller.php";
			if ( $have_search == true and is_task_result( $task, $search_params ) == false )
				continue;

			$user = get_user_through_id( $task->get_user_id() );
			$part = get_part_through_id( $task->get_part_id() );

			if ( contract_finalised( $part->get_contract_id() ) or contract_obsolete( $part->get_contract_id() ) )
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

			echo " title=\"";


			$tasks_of_part = get_tasks_through_part_id( $task->get_part_id() );

			$part = get_part_through_id( $task->get_part_id() );
			$contract = get_contract_through_id( $part->get_contract_id() );
			if ( count( $tasks_of_part ) != 1 )
			{
				echo "Also on this part ( '" . $part->get_part_name() . "' of '" . $contract->get_contract_name() . "' ):\n";

				foreach ( $tasks_of_part as $task_of_part )
				{
					$part_user = get_user_through_id( $task_of_part->get_user_id() );
					if ( $part_user->get_id() == $user->get_id() )
						continue;

					echo "  " . $part_user->get_firstname() . ' ' . $part_user->get_lastname() . ' ';

					include_once "controllers/Task_controller.php";
					$parts_tasks = get_part_tasks_through_task_id_and_status( $task->get_id(), "given" );

					if ( count( $parts_tasks ) != 0 )
					{
						echo '( ' . count( $parts_tasks ) . " given tasks )";
					}
					else
						echo " Give part tasks!";

					echo "\n";
				}

				echo "\n( and " . $user->get_firstname() . ' ' . $user->get_lastname() . "  )";
			}
			else
				echo $user->get_firstname() . ' ' . $user->get_lastname() . "'s task.";


			echo "\" > ";






			$user = get_user_through_id( $task->get_user_id() );
			$part = get_part_through_id( $task->get_part_id() );

			if ( $user != null )
			{
				echo "<td class=\"href\" style=\"";

				// User types

				// if ( $user->get_type() == "admin" )
				// 	echo "background-color: rgb( 234, 234, 234 );";
				// else
				// 	echo "background-color: rgb( 243, 243, 243 );";

				echo "\" > <a href=\"index.php?path=user_controller/view/" . $user->get_id() . "\">" . $user->get_firstname() . ' ' . $user->get_lastname() . "</a> </td>";
			}
			else
				echo "<td> User not available </td>";

			$contract = get_contract_through_id( $part->get_contract_id() );
			echo "<td class=\"href\"> <a href=\"index.php?path=contract_controller/view/" . $contract->get_id() . "\">" . $contract->get_contract_name() . "</a> ";



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


			echo "</td>";

			if ( $part != null )
			{
				if ( $part->get_progress() != 100 )
				{
					echo "<td style=\"min-width: 240px;\"> 
							<div class=\"progress_div\" style=\"padding: 0px; display: flex;\"> 
								<b title=\"Progress\" style=\"padding: 0px;\"> <a style=\"padding: 3px; background-color: inherit;\" href=\"index.php?path=part_controller/view/" . $part->get_id() . "\">" . $part->get_part_name() . "</a>: </b>
								<div class=\"behind_progress\" style=\"padding: 0px; width: 100% !important;\">
									<div class=\"progress\" style=\"width: " . $part->get_progress() . "%; ";

							if ( contract_obsolete( $part->get_contract_id() ) or contract_finalised( $part->get_contract_id() ) )
								echo "background-color: rgb( 204, 204, 204 ); color: rgb( 123, 123, 123 );";

							echo " \" > 
										<span> " . $part->get_progress() . "%</span> 
									</div>  ";

					echo "		</div> 
							</div>
						  </td>";
				}
				else
					echo "<td style=\"min-width: 240px; line-height: 30px;\">
							<div class=\"progress_div\" style=\"padding: 0px; display: flex; margin: 0px;\"> 
								<b title=\"Progress\" style=\"padding: 0px;\"> <a style=\"padding: 3px; background-color: inherit;\" href=\"index.php?path=part_controller/view/" . $part->get_id() . "\">" . $part->get_part_name() . "</a>: </b>
								Completed
							</div> </td>";

			}
			else
				echo "<td> Part not available </td>";





			include_once "controllers/Task_controller.php";
			$part_tasks = get_part_tasks_through_task_id_and_status( $task->get_id(), "given" );


			echo "<td style=\"text-align: center;  background-color: rgb( 243, 243, 243 );\" title=\"";

			if ( count( $part_tasks ) != 0 )
			{
				echo "Given tasks:\n";
				foreach ( $part_tasks as $part_task )
					echo "  " . $part_task->get_part_task() . "\n";
			}
			else
				echo "Empty, please add!";

			echo "\"> "; 

			if ( count( $part_tasks ) != 0 )
				echo "(<b style=\"margin: 1px;\">" . count( $part_tasks ) . "</b>)  Hover";
			else
				echo "<b>Empty!</b>";

			echo " </td>";


			$part_tasks = get_part_tasks_through_task_id_and_status( $task->get_id(), "completed" );


			echo "<td id=\"td_completed\" style=\"text-align: center; background-color: rgb( 204, 204, 204 );\" title=\"";

			if ( count( $part_tasks ) != 0 )
			{
				echo "Completed tasks:\n";
				foreach ( $part_tasks as $part_task )
					echo "  " . $part_task->get_part_task() . "\n";
			}
			else
				echo "0 Part tasks completed.";

			echo "\"> "; 

			if ( count( $part_tasks ) != 0 )
				echo "(<b style=\"margin: 1px;\">" . count( $part_tasks ) . "</b>)  Hover";
			else
				echo "<b>Empty!</b>";

			echo " </td>";


//			echo "<td>" . $task->get_id() . "</td>";

			echo "<td class=\"table_options\" style=\"width: 179px;\">";
			if ( $part != null and $user != null )
			{
				echo " <a href=\"index.php?path=task_controller/view/" . $task->get_id() . "\"> View </a>";
				if ( $_SESSION[ 'type' ] == "admin" )
				{
					// Edit

					// echo " / <a href=\"index.php?path=task_controller/edit/" . $task->get_id() . "\"> Edit </a>";
					echo " / <a href=\"index.php?path=task_controller/add_part_task/" . $task->get_id() . "\"> Add Part Task </a>";
				}
			}


// Prevent admin user deleting tasks without adding contract to "Deleted Contracts"

//			if ( $_SESSION[ 'type' ] == "admin" )
//				echo " / <a href=\"index.php?path=task_controller/delete/" . $task->get_id() . "\"> Delete </a> </td>";
//			else
				echo "</td>";

			echo "</tr>";
		}
		echo "</table>";

	    ?>

		</div>

	</body>

</html>