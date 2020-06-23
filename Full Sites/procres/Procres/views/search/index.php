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

        echo "<script> location.replace( \"/Procres/index.php?path=search_controller/search/" . $data . "\" ); </script>";
    }	

	?>

	<head>
		<title> Search <?php echo $this->data[ 'title' ] ?> </title>
		<link rel="stylesheet" href="/Procres/files/style.css">
		<meta name="viewport" content="width=device-width, initial-scale=1">
	</head>






	<body>


		

		<div class="company"> <?php echo $this->data[ 'company' ]; ?> </div>
		<?php if ( $_SESSION[ 'type' ] != "admin" ) echo "<style> #left_nav { display: none !important; } html { padding-left: 10px !important; } </style>"; ?>
		<a class="signed_in_user" href=<?php echo "\"index.php?path=user_controller/view/" . $_SESSION[ 'id' ] . "\""; ?> > <?php echo $_SESSION[ 'username' ]; ?> </a>




		<div class="center" >

			<h1> Search <?php echo $this->data[ 'title' ] ?> </h1>

			<?php

			if ( $_SESSION[ 'type' ] == "admin" )
				echo "<h3> Obsolete / Done contracts: Delete Contract, not its parts <br> <span style=\"font-size: 14px;\"> ( add contract to \"Deleted Contracts\" only when sure ) </span> </h3>";

			?>


	    </div>


	    <br>

	    <div class="center" style="font-size: 20px;">
			<a href="#users" > Users</a> /
			<a href="#parts" > Parts</a> /
			<a href="#tasks" > Tasks</a> /
			<a href="#contracts" > Contracts</a> /
			<a href="#deleted_contracts" > Deleted_contracts</a>
		</div>

	    <br>
	    <br>








		<p style="margin-top: -54px; left: -500px; font-size: 40px; position: absolute;" id="contracts" > span </p>

   		<div class="center" >
   			<h2> Contract <?php echo $this->data[ 'title' ]; ?> </h2>
   			<p id="empty_contracts" style="margin: 0px; display: none;"> Empty </p>
   		</div>

		<div class="table_center" id="contract_results" >

		<?php

		$contracts = $this->data[ 'contracts' ];


		if ( count( $contracts ) < 1 )
		{
			echo "<style> #contract_results{ display: none; } #empty_contracts { display: initial !important; } </style>";
		}

		echo "<table>";

		echo "<tr class=\"th\"> <td> Contract Name </td> <td> Parts </td> <td> Client </td>  <td> Contract Date </td>  <td> Deadline Date </td> 

			<!-- <td> Id </td> --> 

			<td class=\"table_options\"> Options </td> </tr>";


		foreach ( $contracts as $contract )
		{
			if ( contract_finalised( $contract->get_id() ) or contract_obsolete( $contract->get_id() ) )
			{

				if ( contract_finalised( $contract->get_id() ) and ! contract_obsolete( $contract->get_id() ) )
					echo "<tr style=\"background-color: rgb( 204, 204, 204 );\">";

				if ( ! contract_finalised( $contract->get_id() ) and contract_obsolete( $contract->get_id() ) )
					echo "<tr style=\"background-color: rgb( 234, 234, 234 );\">";

				if ( contract_finalised( $contract->get_id() ) and contract_obsolete( $contract->get_id() ) )
					echo "<tr style=\"background-color: rgb( 190, 190, 190 );\">";
			}
			else
				echo "<tr>";


			echo "<td class=\"href\"> <a style=\"text-decoration: underline; font-weight: 500;\" href=\"index.php?path=contract_controller/view/" . $contract->get_id() . "\"> " . $contract->get_contract_name() . "</a> ";

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

			echo " </td>";
			echo "<td class=\"href\"> <a href=\"index.php?path=contract_controller/parts/" . $contract->get_id() . "\"> View parts</a>";

			// Contract Progress

			$parts = get_parts_through_contract_id( $contract->get_id() );

			$completed_parts = 0;
			$contract_progress = 0;
			$parts_count = 0;
			foreach ( $parts as $part )
			{
				if ( $part->get_contract_id() != $contract->get_id() )
					continue;			

				if ( $part->get_progress() == 100 )
					$completed_parts = $completed_parts + 1;

				$contract_progress = $contract_progress + $part->get_progress();
				$parts_count = $parts_count + 1;
			}

			if ( $parts_count != 0 )
				$contract_progress = (int) ( $contract_progress / $parts_count );

			echo " <span style=\"color: rgb( 123, 123, 123 );\"><b>" . $contract_progress . "% </b></span>";

			echo "</td>";






			$client = get_user_through_id( $contract->get_client_id() );

			if ( $client != null )
				echo "<td class=\"href\"> <a href=\"index.php?path=user_controller/view/" . $contract->get_client_id() . "\"> " . $client->get_firstname() . ' ' . $client->get_lastname() . " </td>";
			else
				echo "<td> Client not available </td>";

			echo "<td>" . $contract->get_contract_date() . "</td>";

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


			echo "<td class=\"table_options\" style=\"min-width: 140px;\">";
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

		<br> <br>








		<p style="margin-top: -54px; left: -500px; font-size: 40px; position: absolute;" id="parts" > span </p>

   		<div class="center"  >
   			<h2> Part <?php echo $this->data[ 'title' ]; ?> </h2>
   			<p id="empty_parts" style="margin: 0px; display: none;"> Empty </p>
   		</div>

	    <div class="table_center" id="part_results" >

	    <?php 

		$parts = $this->data[ 'parts' ];

		if ( count( $parts ) < 1 )
		{
			echo "<style> #part_results{ display: none; } #empty_parts { display: initial !important; } </style>";
		}

		echo "<table>";
		echo "<tr class=\"th\">  <td> Contract </td> <td> Part Name </td> <td> Progress </td> 

			  <!-- <td> Id </td> --> 

			  <td class=\"table_options\"> Options </td> </tr>";

		foreach ( $parts as $part )
		{
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

		<br> <br>
















		<p style="margin-top: -54px; left: -500px; font-size: 40px; position: absolute;" id="tasks" > span </p>

 		<div class="center" >
   			<h2> Task <?php echo $this->data[ 'title' ]; ?> </h2>
   			<p id="empty_tasks" style="margin: 0px; display: none;"> Empty </p>
  		</div>

		<div class="table_center" id="task_results" >

		<?php


		$tasks = $this->data[ 'tasks' ];

		if ( count( $tasks ) < 1 )
		{
			echo "<style> #task_results{ display: none; } #empty_tasks { display: initial !important; } </style>";
		}

		echo "<table>";
		echo "<tr class=\"th\"> <td> User </td> <td> Contract </td> <td> Part </td> 

			  <!-- <td> Id </td> --> 

			  <td class=\"table_options\"> Options </td> </tr>";


		foreach ( $tasks as $task )
		{

			$user = get_user_through_id( $task->get_user_id() );
			$part = get_part_through_id( $task->get_part_id() );

			if ( contract_finalised( $part->get_contract_id() ) or contract_obsolete( $part->get_contract_id() ) )
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

			if ( $user != null )
				echo "<td class=\"href\"> <a href=\"index.php?path=user_controller/view/" . $user->get_id() . "\">" . $user->get_firstname() . ' ' . $user->get_lastname() . "</a> </td>";
			else
				echo "<td> User not available </td>";

			$contract = get_contract_through_id( $part->get_contract_id() );
			echo "<td class=\"href\"> <a href=\"index.php?path=contract_controller/view/" . $contract->get_id() . "\">" . $contract->get_contract_name() . "</a> ";

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


//			echo "<td>" . $task->get_id() . "</td>";

			echo "<td class=\"table_options\" style=\"min-width: 90px;\">";
			if ( $part != null and $user != null )
			{
				echo " <a href=\"index.php?path=task_controller/view/" . $task->get_id() . "\"> View </a>";
				if ( $_SESSION[ 'type' ] == "admin" )
				{
					echo " / <a href=\"index.php?path=task_controller/edit/" . $task->get_id() . "\"> Edit </a>";
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

		<br> <br>
















		<p style="margin-top: -54px; left: -500px; font-size: 40px; position: absolute;" id="users" > span </p>

 		<div class="center" >
   			<h2> User <?php echo $this->data[ 'title' ]; ?> </h2>
   			<p id="empty_users" style="margin: 0px; display: none;"> Empty </p>
   		</div>

		<div class="table_center" id="user_results">

		<?php

		$users = $this->data[ 'users' ];


		if ( count( $users ) < 1 )
		{
			echo "<style> #user_results{ display: none; } #empty_users { display: initial !important; } </style>";
		}


		echo "<table>";
		echo "<tr class=\"th\"> <td> First Name </td> <td> Last Name </td> <td> Username </td> <td> Email </td> <td> Type </td> 

			<!-- <td> Id </td> --> 

			<td class=\"table_options\"> Options </td> </tr>";

		foreach ( $users as $user )
		{
//			if ( ! empty( $this->data[ 'type' ] ) and $user->get_type() != $this->data[ 'type' ] )
//				continue;


			$type_style = "style=\"background-color: rgb( ";
			if ( $user->get_type() == "admin" )
				$type_style = $type_style . " 209, 209, 209 );\" ";
			if ( $user->get_type() == "employee" )
				$type_style = $type_style . " 230, 230, 230 );\" ";
			if ( $user->get_type() == "client" )
				$type_style = $type_style . " 243, 243, 243 );\" ";

			echo "<tr " . $type_style . " >";

			$tasks = get_tasks_through_user_id( $user->get_id() );

			echo "<td>" . $user->get_firstname() . "</td>";
			echo "<td>" . $user->get_lastname() . "</td>";






			echo "<td class=\"href\">
				  	<a style=\"text-decoration: underline; font-weight: 500;\" href=\"index.php?path=user_controller/view/" . $user->get_id() . "\"> " . $user->get_username() . "
				  	</a> ";


			if ( $user->get_type() == "admin" or $user->get_type() == "employee" )
			{
				echo " <span style=\"color: rgb( 123, 123, 123 ); font-size: 10px; font-weight: bold;";

				if ( count( $tasks ) < 1 )
					echo "color: rgb( 90, 90, 90 );";
//				if ( count( $tasks ) > 4 )
//					echo "background-color: rgb( 204, 204, 204 ); border-radius: 10px; padding: 4px; text-align: center; vertical-align: middle; border: 2px solid rgb( 150, 150, 150 );";

				echo "\">( Parts: " . count( $tasks ) . " )</span>";
			}


			echo " </td>";


			echo "<td class=\"href\"> <a href=\"index.php?path=user_controller/email/" . $user->get_id() . "\"> " . $user->get_email() . "</a> </td>";

			echo "<td>" . $user->get_type() . "</td>";
//			echo "<td>" . $user->get_id() . "</td>";

			echo "<td class=\"table_options\" style=\"max-width: 150px;\">";
			echo " <a href=\"index.php?path=user_controller/view/" . $user->get_id() . "\"> View </a>";

			if ( $_SESSION[ 'type' ] == "admin" )
			{
				echo "/ <a href=\"index.php?path=user_controller/edit/" . $user->get_id() . "\"> Edit </a> ";

			// if ( $user->get_type() == "admin" or $user->get_type() == "employee" )
			// 	if ( count( $tasks ) < 1 )
			// 	{
			// 		echo "/";

			// 		echo " <a href=\"index.php?path=task_controller/create/user(@)" . $user->get_id() . "\"> ( Add Task ) </a> ";
			// 	}


// Prevent user deleting Users that have Contracts

//				echo " / <a href=\"index.php?path=user_controller/delete/" . $user->get_id() . "\"> Delete </a> ";
			}

			echo "</td>";
			
			echo "</tr>";
		}
		echo "</table>";

	    ?>

		</div>		

		<br> <br>











		<p style="margin-top: -54px; left: -500px; font-size: 40px; position: absolute;" id="deleted_contracts" > span </p>

		<div class="center" >
   			<h2> Deleted Contract <?php echo $this->data[ 'title' ]; ?> </h2>
   			<p id="empty_deleted_contracts" style="margin: 0px; display: none;"> Empty </p>
   		</div>

		<div class="table_center" id="deleted_contract_results">

		<?php

		$deleted_contracts = $this->data[ 'deleted_contracts' ];


		if ( count( $deleted_contracts ) < 1 )
		{
			echo "<style> #deleted_contract_results{ display: none; } #empty_deleted_contracts { display: initial !important; } </style>";
		}



		echo "<table>";

		echo "<tr class=\"th\"> <td> Deleted Contract Name </td> 

			<!-- <td> Id </td> -->";


		foreach ( $deleted_contracts as $deleted_contract )
		{
			echo "<tr>";

			echo "<td>" . $deleted_contract->get_deleted_contract_name() . " </td>";

			echo "</tr>";
		}
		echo "</table>";

	    ?>

		</div>

		<br> <br>
		
	</body>

</html>