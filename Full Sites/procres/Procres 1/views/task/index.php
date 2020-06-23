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
		echo "<tr class=\"th\"> <td> User </td> <td> Part </td> <td> Id </td> <td class=\"table_options\"> Options </td> </tr>";
		foreach ( $tasks as $task )
		{
			if ( $have_search == true and is_result( $task, $search_params ) == false )
				continue;

			echo "<tr>";

			$user = get_user_through_id( $task->get_user_id() );

			if ( $user != null )
				echo "<td class=\"href\"> <a href=\"index.php?path=user_controller/view/" . $user->get_id() . "\">" . $user->get_firstname() . ' ' . $user->get_lastname() . "</a> </td>";
			else
				echo "<td> User not available </td>";


			$part = get_part_through_id( $task->get_part_id() );

			if ( $part != null )
				echo "<td class=\"href\"> <a href=\"index.php?path=part_controller/view/" . $part->get_id() . "\">" . $part->get_part_name() . "</a> </td>";
			else
				echo "<td> Part not available </td>";

			echo "<td>" . $task->get_id() . "</td>";

			echo "<td class=\"table_options\">";
			if ( $part != null and $user != null )
			{
				echo " <a href=\"index.php?path=task_controller/view/" . $task->get_id() . "\"> View </a>";
				if ( $_SESSION[ 'type' ] == "admin" )
					echo " / <a href=\"index.php?path=task_controller/edit/" . $task->get_id() . "\"> Edit </a> /";
			}
			if ( $_SESSION[ 'type' ] == "admin" )
				echo " <a href=\"index.php?path=task_controller/delete/" . $task->get_id() . "\"> Delete </a> </td>";
			echo "</tr>";
		}
		echo "</table>";

	    ?>

		</div>

	</body>

</html>