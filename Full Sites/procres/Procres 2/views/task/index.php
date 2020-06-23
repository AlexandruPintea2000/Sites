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


		<?php $this->show_nav(); ?>

		<div class="company"> <?php echo $this->data[ 'company' ]; ?> </div>
		<?php if ( $_SESSION[ 'type' ] != "admin" ) echo "<style> #left_nav { display: none !important; } html { padding-left: 10px !important; } </style>"; ?>
		<a class="signed_in_user" href=<?php echo "\"index.php?path=user_controller/view/" . $_SESSION[ 'id' ] . "\""; ?> > <?php echo $_SESSION[ 'username' ]; ?> </a>



		<div class="center" >

			<h1> <?php echo $this->data[ 'title' ] ?> </h1>


			<?php

			if ( $_SESSION[ 'type' ] == "admin" )
				echo "<h3> Obsolete / Done contracts: Delete contract, not its tasks <br> <span style=\"font-size: 14px;\"> ( add contract to \"Deleted Contracts\" only when sure ) </span> </h3>";

			?>


	    </div>

	    <form action="views/task/index.php">
		    <input style="margin-left: 2.5%;" name="search" placeholder="Search">  </input>
		    <input type="submit" style="font-weight: bold;" name="Submit" value="Search" >  </input>
	    </form>

	    <?php 

	    if ( $_SESSION[ 'type' ] == "admin" )
			echo "<a style=\"margin-left: 2.5%;\" title=\"Make Task\" href=\"index.php?path=task_controller/create\" > Make Task</a>";
		else
			echo "echo";

		?>


	    <br>

	    <div class="table_center">

	    <?php 


	    function is_result ( task $task, $search_params )
	    {

	    	foreach ( $search_params as $search_param )
	    	{
		    	if ( $task->get_id() == $search_param )
		    		return true;
		    	if ( $task->get_task_name() == $search_param )
		    		return true;
		    	if ( $task->get_contract_id() == $search_param )
		    		return true;
		    	if ( $task->get_progress() == $search_param )
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



		$tasks = $this->data[ 'tasks' ];

		include_once "models/Part.php";
		include_once "models/User.php";

		echo "<table>";
		echo "<tr class=\"th\"> <td> User </td> <td> Contract </td> <td> Part </td> 

			  <!-- <td> Id </td> --> 

			  <td class=\"table_options\"> Options </td> </tr>";


		foreach ( $tasks as $task )
		{
			if ( $have_search == true and is_result( $task, $search_params ) == false )
				continue;

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
					echo " / <a href=\"index.php?path=task_controller/edit/" . $task->get_id() . "\"> Edit </a>";
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