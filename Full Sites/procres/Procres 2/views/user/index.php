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


		<?php $this->show_nav(); ?>

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

	    <a style="margin-left: 2.5%;" href="index.php?path=user_controller"> All Users</a> /
	    <a href="index.php?path=user_controller/index/admin"> Only Admins</a> /
	    <a href="index.php?path=user_controller/index/client"> Only Clients</a> /
	    <a href="index.php?path=user_controller/index/employee"> Only employees</a>

	    <?php 

	    if ( $_SESSION[ 'type' ] == "admin" )
			echo "/ <a title=\"Make user\" href=\"index.php?path=user_controller/create\" > Make User</a>";

		?>

	    <br>

	    <div class="table_center">

	    <?php 


	    function is_result ( User $user, $search_params )
	    {

	    	foreach ( $search_params as $search_param )
	    	{
		    	if ( $user->get_id() == $search_param )
		    		return true;
		    	if ( $user->get_username() == $search_param )
		    		return true;
		    	if ( $user->get_firstname() == $search_param )
		    		return true;
		    	if ( $user->get_lastname() == $search_param )
		    		return true;
		    	if ( $user->get_username() == $search_param )
		    		return true;
		    	if ( $user->get_email() == $search_param )
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



		$users = $this->data[ 'users' ];

		echo "<table>";
		echo "<tr class=\"th\"> <td> First Name </td> <td> Last Name </td> <td> Username </td> <td> Email </td> <td> Type </td> 

			<!-- <td> Id </td> --> 

			<td class=\"table_options\"> Options </td> </tr>";

		foreach ( $users as $user )
		{
			if ( ! empty( $this->data[ 'type' ] ) and $user->get_type() != $this->data[ 'type' ] )
				continue;

			if ( $have_search == true and is_result( $user, $search_params ) == false )
				continue;

			$type_style = "style=\"background-color: rgb( ";
			if ( $user->get_type() == "admin" )
				$type_style = $type_style . " 209, 209, 209 );\" ";
			if ( $user->get_type() == "employee" )
				$type_style = $type_style . " 230, 230, 230 );\" ";
			if ( $user->get_type() == "client" )
				$type_style = $type_style . " 243, 243, 243 );\" ";

			echo "<tr " . $type_style . " >";

			echo "<td>" . $user->get_firstname() . "</td>";
			echo "<td>" . $user->get_lastname() . "</td>";

			echo "<td class=\"href\"> <a style=\"text-decoration: underline; font-weight: 500;\" href=\"index.php?path=user_controller/view/" . $user->get_id() . "\"> " . $user->get_username() . "</a> </td>";
			echo "<td class=\"href\"> <a href=\"index.php?path=user_controller/email/" . $user->get_id() . "\"> " . $user->get_email() . "</a> </td>";

			echo "<td>" . $user->get_type() . "</td>";
//			echo "<td>" . $user->get_id() . "</td>";

			echo "<td class=\"table_options\" style=\"max-width: 50px;\">";
			echo " <a href=\"index.php?path=user_controller/view/" . $user->get_id() . "\"> View </a>";

			if ( $_SESSION[ 'type' ] == "admin" )
			{
				echo "/ <a href=\"index.php?path=user_controller/edit/" . $user->get_id() . "\"> Edit </a> ";

// Prevent user deleting Users that have Contracts

//				echo " / <a href=\"index.php?path=user_controller/delete/" . $user->get_id() . "\"> Delete </a> </td>";
			}
			
			echo "</tr>";
		}
		echo "</table>";

	    ?>

		</div>

	</body>

</html>