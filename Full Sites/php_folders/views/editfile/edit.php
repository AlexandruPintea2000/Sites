<!doctype html>
<html>



    <?php 
   
    if ( isset( $_GET[ 'firstname' ] ) )
    {
        $data = "";
        foreach ( $_GET as $i )
        {
            if ( ! empty( $i ) and $i != "Submit" )
                $data = $data . $i . "(@)";
         }



        echo "<script> location.replace( \"/php_folders/index.php?f=editfile/edit_editfile/" . $data . "\" ); </script>";
    }

    ?>



    <head>
        <title> <?php echo $this->data['title']; ?> </title>
    </head>

    <style>

        input 
        {
            margin: 3px;
        }

    </style>

    <body>



	   	<nav> 

            <a href="index.php?f=editfile"> index</a>,
            <a href="index.php?f=editfile/create"> create</a>

    	</nav>


        <h1> Edit <?php echo $this->data['fname'] . ' ' . $this->data['lname']; ?> </h1>

        <form action="views/editfile/edit.php" >

            id = <input type="text" name="id" value=<?php echo $this->data['id']; ?> > <br>
            <input type="text" name="firstname" placeholder="firstname" value=<?php echo $this->data['fname']; ?> > </input> <br>
            <input type="text" name="lastname" placeholder="lastname" value=<?php echo $this->data['lname']; ?> > </input> <br>
            <input type="text" name="email" placeholder="email" value=<?php echo $this->data['email']; ?> > </input> <br>
            <input type="submit" name="Submit" value="Submit" > </input>

        </form>

    </body>
</html>