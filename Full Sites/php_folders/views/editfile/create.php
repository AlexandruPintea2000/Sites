<!doctype html>
<html>


    <?php 
   


    if ( isset( $_GET[ 'firstname' ] ) )
    {
        $data = "";
        foreach ( $_GET as $i )
        {
            if ( !empty( $i ) and $i != "Submit" )
                $data = $data . $i . "(@)";
        }



        echo "<script> location.replace( \"/php_folders/index.php?f=editfile/create_editfile/" . $data . "\" ); </script>";
    }



    ?>



    <head>
        <title> <?php echo $this->data['title']; ?> </title>

    <style>

        input 
        {
            margin: 3px;
        }

    </style>

    </head>
    <body>


	   	<nav> 

            <a href="index.php?f=editfile"> index</a>,
            <a href="index.php?f=editfile/create"> create</a>
 
    	</nav>


        <h1> Make editfile </h1>

        <form action="views/editfile/create.php" >

            <input type="text" name="id" placeholder="id"> </input> <br>
            <input type="text" name="firstname" placeholder="firstname"> </input> <br>
            <input type="text" name="lastname" placeholder="lastname"> </input> <br>
            <input type="text" name="email" placeholder="email"> </input> <br>
            <input type="submit" name="Submit" value="Submit"> </input>

        </form>

    </body>
</html>