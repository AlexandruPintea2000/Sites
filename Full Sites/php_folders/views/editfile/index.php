<!doctype html>
<html>
    <head>
        <title> editfiles </title>
    </head>
    <body>

	   	<nav> 

    		<a href="index.php?f=editfile"> index</a>,
    		<a href="index.php?f=editfile/create"> create</a>

    	</nav>

        <h1> Editfiles </h1>


		<?php 

            foreach ( $this->data[ 'editfiles' ] as $i )
            {
                $id = $i->get_id();

                echo "<a href=\"index.php?f=editfile/view/" . $id . "\"> " . ' ' . $i->get_fname() . ' ' . $i->get_lname(). "</a> / <a href=\"index.php?f=editfile/edit/" . $id . "\"> Edit</a> / <a href=\"index.php?f=editfile/delete/" . $id . "\"> Delete</a> <br> ";
            }

		?>

        <br> 

        <a href="index.php" ?> Return</a>


    </body>
</html>