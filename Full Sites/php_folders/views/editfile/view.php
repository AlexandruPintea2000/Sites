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

        <h1> <?php echo $this->data['title']; ?> </h1>

        <h2> <?php echo $this->data['email']; ?> </h2>
    </body>
</html>