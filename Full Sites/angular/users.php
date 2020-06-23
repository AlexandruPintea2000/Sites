<?php

include_once "Model.php";

$conn = new mysqli( "localhost", "root", "" );
$sql = "DROP DATABASE angular";
$conn->query( $sql );
$sql = "CREATE DATABASE angular";
$conn->query( $sql );
$sql = "CREATE TABLE users ( id int, fname varchar(255), lname varchar(255) );";

$db = new Database( "localhost", "root", "", "angular" );
$db->sql( $sql );


class User 
{

	private $id;
	private $fname;
	private $lname;

	function get_id()
	{
		return $this->id;
	}

	function get_fname()
	{
		return $this->fname;
	}

	function get_lname()
	{
		return $this->lname;
	}

	function __construct( $id, $fname, $lname )
	{
		$this->id = $id;
		$this->fname = $fname;
		$this->lname = $lname;
	}
}


$sql = "INSERT INTO users ( id, fname, lname ) VALUES ( 0, 'fname', 'lname' );";
$db->sql( $sql );
for ( $i = 1; $i < 5; $i = $i + 1 )
{
	$sql = "INSERT INTO users ( id, fname, lname ) VALUES ( " . $i . ", 'fname" . $i . "', 'lname" . $i . "' );";
	$db->sql( $sql );
}

$table = new Table( "users", $db );


$users_data = $table->get_table();
$users = [];
$l = 0;
for ( $i = 0; $i < count( $users_data ); $i = $i + 3 )
{
	$user = new User( $users_data[ $i ], $users_data[ $i + 1 ], $users_data[ $i + 2 ] );

	$users[ $l ] = $user;
	$l = $l + 1;
}


$data = "";
for ( $i = 0; $i < count( $users ); $i = $i + 1 )
{

	$data = $data . "{ 'Id': '" . $users[ $i ]->get_id() . "', ";
	$data = $data . " 'Fname': '" . $users[ $i ]->get_fname() . "', ";
	$data = $data . " 'Lname': '" . $users[ $i ]->get_lname() . "' } ";

	if ( $i != count( $users ) - 1 )
		$data = $data . ", ";
}



// $data = "{'':[" . $data . "]}";
 $data = "[ " . $data . " ]";

echo $data;