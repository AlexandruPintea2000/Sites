<?php

function my_table_connection()
{
	$servername = "localhost";
	$username = "root";
	$password = "";
	$dbname = "db";

	$conn = new mysqli($servername, $username, $password, $dbname);

	if ($conn->connect_error) {
	    echo "alert( \"Failed\" )";
	}
	else
		return $conn;

	return -1;
}




function my_table_get_id ()
{
	$conn = my_table_connection();

	$sql = "SELECT id FROM my_table";
	$result = $conn->query($sql);

	$id = -1;

	if ($result->num_rows > 0) 
	{
	    // output data of each row
	    while($row = $result->fetch_assoc()) 
	    {
	        $id = $row["id"];
	    }
	}

	return $id;
}


function my_table_add ( string $value_1, string $value_2, string $value_3, string $value_4 )
{
	$id = my_table_get_id();


	$id = $id + 1;


	$conn = my_table_connection();


	$sql = "INSERT INTO my_table ( id, value_1, value_2, value_3, value_4 ) VALUES ( " . $id . ", '" . $value_1 . "', '" . $value_2 . "', '" . $value_3 . "', '" . $value_4 . "' )";

    $conn->query($sql);
}

function my_table_update ( int $id, string $value_1, string $value_2, string $value_3, string $value_4 )
{
	$last_id = my_table_get_id();

	if ( $id > $last_id or $id < 0 )
	{
	    echo "<script> alert( \"Failed, id is not valid.\" ) </script>";
		return;
	}

	$conn = my_table_connection();

	$sql = "UPDATE my_table SET value_1='" . $value_1 . "', value_2='" . $value_2 . "', value_3='" . $value_3 . "', value_4='" . $value_4 . "'  WHERE id=" . $id;

    $conn->query($sql);
}

function my_table_remove ( int $id )
{
	$last_id = my_table_get_id();

	if ( $id > $last_id or $id < 0 )
	{
	    echo "<script> alert( \"Failed, id is not valid.\" ) </script>";
		return;
	}

	$conn = my_table_connection();

	$sql = "DELETE FROM my_table WHERE id=" . $id;

    $conn->query($sql);
}

function my_table_show ()
{
	$conn = my_table_connection();

	$once = 0;
	while (true)
	{
		if ( $once != 0 )
			break;

		$sql = "SELECT id, value_1, value_2, value_3, value_4 FROM my_table";

		$result = $conn->query($sql);
		if ($result->num_rows > 0) 
		{
		    // output data of each row
		    while($row = $result->fetch_assoc()) 
		    {
				if ( $row["id"] == 0 and $once != 0 )
					break;

				if ( $row["id"] == 0 and $once == 0 )
					$once = $once + 1;

		        $line = $row["id"] . " " . $row["value_1"] . " " . $row["value_2"] . " " . $row["value_3"] . " " . $row["value_4"] . "<br>";

		        echo $line;
		    }
		}
		else
			break;
	}
}

?>
