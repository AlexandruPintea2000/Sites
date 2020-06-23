<!DOCTYPE html>

<html>



	<?php


	if ( isset( $_GET[ 'id' ] ) )
	{
        $data = "";
        foreach ( $_GET as $i )
        {
            if ( ! empty( $i ) and $i != "Submit" )
                $data = $data . $i . "(@)";

            if ( empty( $i ) )
    	        $data = $data . "empty(@)";
         }

        echo "<script> location.replace( \"/Procres/index.php?path=task_controller/add_user_part_task/" . $data . "\" ); </script>";
    }


	?>
	


	<head>
		<title> Add Part Task </title>
		<link rel="stylesheet" href="/Procres/files/style.css">
		<meta name="viewport" content="width=device-width, initial-scale=1">
	</head>


	<body>

		

		<div class="company"> <?php echo $this->data[ 'company' ]; ?> </div>
		<?php if ( $_SESSION[ 'type' ] != "admin" ) echo "<style> #left_nav { display: none !important; } html { padding-left: 10px !important; } </style>"; ?>
		<a class="signed_in_user" href=<?php echo "\"index.php?path=user_controller/view/" . $_SESSION[ 'id' ] . "\""; ?> > <?php echo $_SESSION[ 'username' ]; ?> </a>


		<?

		$task = $this->data[ 'task' ];

		$user = get_user_through_id( $task->get_user_id() );
		$part = get_part_through_id( $task->get_part_id() );
		$contract = get_contract_through_id( $part->get_contract_id() );

		?>


		<div class="center" >

			<h1 style="font-size: 20px; font-weight: 500;"> 

			<span style="font-size: 25px;"> Add part task to

			<?php

			echo "<a href=\"index.php?path=user_controller/view/" . $user->get_id() . "\">" . $user->get_firstname() . ' ' . $user->get_lastname() . "</a>";

			?>

 			</span>

 			<br> for part: 

			"<?php

			echo "<a href=\"index.php?path=part_controller/view/" . $part->get_id() . "\">" . $part->get_part_name() . "</a>";

			?>"

			of contract: 

			"<?php

			echo "<a href=\"index.php?path=contract_controller/view/" . $contract->get_id() . "\">" . $contract->get_contract_name() . "</a>";

			?>"


			</h1>

			<br>
			<br>

			<div class="div_center">
				<div class="user_details">

			        <form action="views/task/add_part_task.php" >

			            <b style="font-size: 21px;"><?php echo $part->get_part_name(); ?> Task: </b> 

			            <input type="number" name="id" value=<?php echo $task->get_id(); ?> hidden> </input> 

			            <input class="submit" style="width: 70%;" type="text" name="part_task" placeholder=<?php echo "\"( for '" . $user->get_firstname() . ' ' . $user->get_lastname() . "' in '" . $contract->get_contract_name() . "' )\""; ?> > </input> 
					    <input class="submit" type="submit" name="Submit" value="Submit"> </input>

			        </form>

			    </div>
		    </div>
	    </div>


	</body>

</html>