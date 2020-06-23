<!doctype html>
<html>
    <head>
        <title> <?php echo $this->data['title']; ?> </title>
    </head>


    <body>


	   	<nav> 

            <a href="index.php?f=editfile"> index</a>,
            <a href="index.php?f=editfile/create"> create</a>

    	</nav>


        <h1> Delete <?php echo $this->data['name'];; ?>? </h1>
        <h2> Details: <?php echo $this->data['name'] . ' / ' . $this->data['email']; ?> </h1>

        <a href=<?php echo $this->data['editfile_delete_address']; ?> > Delete</a>

        <a href="index.php?f=editfile" ?> Return</a>

    </body>
</html>