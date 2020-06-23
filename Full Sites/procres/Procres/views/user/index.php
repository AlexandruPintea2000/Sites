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

        echo "<script> location.replace( \"/Procres/index.php?path=user_controller/search/" . $data . "\" ); </script>";
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

	    </div>

	    <form action="views/user/index.php">
		    <input style="margin-left: 2.5%;" name="search" placeholder="Search">  </input>
		    <input name="type" value=<?php echo "\"" . $this->data[ 'type' ] . "\""; ?> hidden>  </input>
		    <input type="submit" style="font-weight: bold;" name="Submit" value=<?php

		    $search = "\"Search"; 
		    if ( ! empty( $this->data[ 'type' ] ) )
		    	$search = $search . ' ' . ucfirst( $this->data[ 'type' ] ) . "s\""; 
		    $search = $search . "\"";

		    echo $search;

			?> >  </input>
	    </form>


	    <p class="table_p">


	    <?php 

	    if ( $_SESSION[ 'type' ] == "admin" )
			echo "<a class=\"add_part_href\" style=\"padding: 3px 10px; margin: 0.4px; margin-right: 5px; background-color: rgb( 243, 243, 243 ); border: 2px solid rgb( 204, 204, 204 );\" title=\"Make user\" href=\"index.php?path=user_controller/create\" > Make User</a> ";

		?>

			<span style="text-align: center;">

			    <a href="index.php?path=user_controller"> All Users</a> /
			    <a href="index.php?path=user_controller/index/admin"> Only Admins</a> /
			    <a href="index.php?path=user_controller/index/admin_employee"> Only Admins and Employees</a> /
			    <a href="index.php?path=user_controller/index/client"> Only Clients</a> /
			    <a href="index.php?path=user_controller/index/employee"> Only employees</a>
			</span>

		</p>


	    <div class="table_center">

	    <?php 

	    $have_search = false;
	    $search_params = [];

 		if ( ! empty( $this->data[ 'search' ] ) )
 		{
			$have_search = true;

			$search_params = explode( " ", $this->data[ 'search' ] );
 		}


		$users = $this->data[ 'users' ];

		echo "<table>";
		echo "<tr class=\"th\"> <td> First Name </td> <td> Last Name </td> <td> Username </td> <td id=\"td_address\"> Email </td> <td> Type </td> 

			<!-- <td> Id </td> --> 

			<td id=\"user_td_options\" class=\"table_options\"> Options </td> </tr>";

		echo "<tr></tr>";


		if ( count( $users ) == 0 )
			echo "<tr style=\"text-align: center; font-size: 10.9px; color: rgb( 150, 150, 150 ); font-weight: bold;\"> 
					<td> Empty </td> 
					<td> Empty </td> 
					<td> Empty </td> 
					<td id=\"td_address\"> Empty </td> 
					<td> Empty </td> 
					<td style=\"color: rgb( 123, 123, 123 );\"> Invalid </td> 

				</tr>";


		foreach ( $users as $user )
		{
			if ( $user->get_id() == -1 )
			{
				echo "<tr> </tr>";
				continue;
			}

//			if ( ! empty( $this->data[ 'type' ] ) and $user->get_type() != $this->data[ 'type' ] )
//				continue;

			$tasks = get_tasks_through_user_id( $user->get_id() );



	    	include_once "controllers/Search_controller.php";
			if ( $have_search == true and is_user_result( $user, $search_params ) == false )
				continue;

			$type_style = "style=\"background-color: rgb( ";
			if ( $user->get_type() == "admin" )
				$type_style = $type_style . " 209, 209, 209 );\" ";
			if ( $user->get_type() == "employee" )
				$type_style = $type_style . " 230, 230, 230 );\" ";
			if ( $user->get_type() == "client" )
				$type_style = $type_style . " 243, 243, 243 );\" ";

			echo "<tr " . $type_style;





					include_once "controllers/Task_controller.php";

					$empty_tasks = [];
					$i = 0;
					foreach ( $tasks as $task )
					{
						$given_part_tasks = get_part_tasks_through_task_id_and_status( $task->get_id(), "given" ); 

						if ( empty( $given_part_tasks ) )
						{
							$empty_tasks[ $i ] = $task;
							$i = $i + 1;
						}
					}


					$have_empty_tasks = false;
					if ( count( $empty_tasks ) !=  0 )
					{
						foreach ( $empty_tasks as $empty_task )
						{
							$empty_part_task = get_part_through_id( $empty_task->get_part_id() );

							if ( $empty_part_task->get_progress() == 100 )
								continue;

							$contract = get_contract_through_id( $empty_part_task->get_contract_id() );

							if ( contract_obsolete( $contract->get_id() ) or contract_finalised( $contract->get_id() ) ) // also obsolete contracts
								continue;

							$have_empty_tasks = true;
							break;					
						}
					}






			if ( count( $tasks ) != 0 )
			{
				if ( $user->get_type() == "admin" or $user->get_type() == "employee" )
				{
					echo " title=\"";

					echo $user->get_firstname() . ' ' . $user->get_lastname() . ":\n";

					foreach ( $tasks as $task )
					{
						$part = get_part_through_id( $task->get_part_id() );
						$contract = get_contract_through_id( $part->get_contract_id() );

						echo "  is on '" . $part->get_part_name() . "' for contract '" . $contract->get_contract_name() . "'\n";
					}

					if ( $have_empty_tasks )
						echo "\n( Does not have tasks for parts! )";

					echo "\" ";
				}
			}
			else
			{
				if ( $user->get_type() != "client" )
 					echo " title=\"Add " . $user->get_firstname() . ' ' . $user->get_lastname() . " to a part of a contract!\" ";
 				else
 				{
 					$user_contracts = get_contracts_through_client_id( $user->get_id() );


 					if ( count( $user_contracts ) != 0 )
 					{
	 					echo " title=\"" . $user->get_firstname() . ' ' . $user->get_lastname() . " contracts:\n";
 	
 						foreach ( $user_contracts as $user_contract )
 						{
 							echo "  '" . $user_contract->get_contract_name() . "'\n";
 						}

 						echo "\" ";
 					}
 					else
 						echo " title=\"" . $user->get_firstname() . ' ' . $user->get_lastname() . " has 0 Contracts.\" ";

 				}
			}

			echo " >";



			echo "<td>" . $user->get_firstname() . "</td>";
			echo "<td>" . $user->get_lastname() . "</td>";






			echo "<td class=\"href\">
				  	<a style=\"text-decoration: underline; font-weight: 500;\" href=\"index.php?path=user_controller/view/" . $user->get_id() . "\"> " . $user->get_username() . "</a> ";


			if ( $user->get_type() == "admin" or $user->get_type() == "employee" )
			{
				echo " <span style=\"color: rgb( 123, 123, 123 ); font-size: 10px; font-weight: bold; display: inline-block;";

				if ( count( $tasks ) < 1 )
				{
					echo "color: rgb( 90, 90, 90 );";
					echo "\">( 0 Parts! )</span>";
				}
				else
				{
					// if ( count( $tasks ) > 4 )
					// 	echo "background-color: rgb( 204, 204, 204 ); border-radius: 10px; padding: 4px; text-align: center; vertical-align: middle; border: 2px solid rgb( 150, 150, 150 );";


					echo "\">( Parts: " . count( $tasks ) . " )</span>";
				}
			}


			echo " </td>";


			echo "<td id=\"td_address\" class=\"href\"> <a href=\"index.php?path=user_controller/email/" . $user->get_id() . "\"> " . $user->get_email() . "</a> </td>";

			echo "<td>" . $user->get_type() . "</td>";
//			echo "<td>" . $user->get_id() . "</td>";

			echo "<td id=\"user_td_options\" class=\"table_options\" >";
			echo " <a href=\"index.php?path=user_controller/view/" . $user->get_id() . "\"> View </a>";

			if ( $_SESSION[ 'type' ] == "admin" )
			{


			// Edit

			// echo "/ <a href=\"index.php?path=user_controller/edit/" . $user->get_id() . "\"> Edit </a> ";

			if ( $user->get_type() == "admin" or $user->get_type() == "employee" )
			{
				if ( count( $tasks ) < 1 )
					echo " / <a href=\"index.php?path=task_controller/create/user(@)" . $user->get_id() . "\" style=\"color: rgb( 90, 90, 90 );\" > Add to a Part </a> ";
				else
				{

					if ( ! $have_empty_tasks )
						echo " / <a href=\"index.php?path=user_controller/admin_view#" . $user->get_id() . "\"> View Tasks </a> ";
					else
					{
						echo " / <a class=\"href\" href=\"index.php?path=task_controller/view/" . $task->get_id() . "\" style=\"color: rgb( 90, 90, 90 );\" ><b>Add Part Tasks </b> ";




						// A table for empty tasks


						// echo "<div class=\"table_center\">";
						// echo "<table>";

						// for ( $i = 0; $i < count( $empty_tasks ); $i = $i + 1 )
						// {
						// 	$empty_part_task = get_part_through_id( $empty_tasks[ $i ]->get_part_id() );
						// 	if ( $empty_part_task->get_progress() == 100 )
						// 		continue;

						// 	$contract = get_contract_through_id( $empty_part_task->get_contract_id() );

						// 	if ( contract_obsolete( $contract->get_id() ) )
						// 		continue; // obsolete contracts

						// 	$contract_status = "";
						// 	if ( contract_obsolete( $contract->get_id() ) )
						// 		$contract_status = " ( obsolete contract: <a href=\"index.php?path=contract_controller/view/" . $contract->get_id() . "\" style=\"\" >" . $contract->get_contract_name() . "</a> ) ";

						// 	echo "<tr style=\"background-color: rgb( 234, 234, 234 ) !important;\"><td><a href=\"index.php?path=task_controller/add_part_task/" . $empty_tasks[ $i ]->get_id() . "\">" . $empty_part_task->get_part_name() . " </a>";

						// 	if ( $contract_status != "" )
						// 		echo " <br> <span style=\"font-size: 10.9px;\">" . $contract_status . "</span> </td>";
						// 	else
						// 		echo "</td>";

						// 	echo "</tr>";

						// 	// if ( $i != count( $empty_tasks ) - 1 )
						// 	// {
						// 	// 	echo " <br> ";
						// 	// }
						// }

						// echo "</table>";
						// echo "</div>";

					}
				}
			}


			if ( $user->get_type() == "client" )
			{
				$user_contracts = get_contracts_through_client_id( $user->get_id() );

				if ( count( $user_contracts ) < 1 )
					echo " / <a href=\"index.php?path=contract_controller/create/" . $user->get_id() . "\" style=\"color: rgb( 90, 90, 90 );\" > Add Contract </a> ";
				else
					echo " / <a href=\"index.php?path=user_controller/admin_view#" . $user->get_id() . "\"> View Contracts </a> ";
			}


// Prevent user deleting Users that have Contracts

//				echo " / <a href=\"index.php?path=user_controller/delete/" . $user->get_id() . "\"> Delete </a> ";
			}

			echo "</td>";
			
			echo "</tr>";
		}
		echo "</table>";

	    ?>

		</div>

<!-- 
 		Users Shown


 		<br>

		<div class="center">
			<p style="margin: 0px;"><b><?php echo count( $users )?></b> users were shown.</p>
		</div>
 
 -->	</body>

</html>