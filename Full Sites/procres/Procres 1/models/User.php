<?php

include_once "Model.php";

class User extends Table 
{
	private $id;

	private $username;
	private $password;
	
	private $firstname;
	private $lastname;

	private $email;

	private $type;



 	public function get_id ()
	{
		return $this->id;
	}
	
 	public function get_username ()
	{
		return $this->username;
	}
	
 	public function get_password ()
	{
		return $this->password;
	}
	
 	public function get_firstname ()
	{
		return $this->firstname;
	}
	
 	public function get_lastname ()
	{
		return $this->lastname;
	}

 	public function get_email ()
	{
		return $this->email;
	}
	
 	public function get_type ()
	{
		return $this->type;
	}
	

	function __construct ( $id, $username, $password, $firstname, $lastname, $email, $type )
	{
		$this->id = $id;
		$this->username = $username;
		$this->password = $password;
		$this->firstname = $firstname;
		$this->lastname = $lastname;
		$this->email = $email;
		$this->type = $type;
	}

}

function get_users( string $type = "" )
{
	$configure_file = fopen( 'configure', 'r' );
	$strings = fgets( $configure_file );
	fclose( $configure_file );

	$config_file = explode ( ' ', $strings );

	for  ( $i = 0; $i < count( $config_file ); $i = $i + 1 )
		$config_file[ $i ] = remove_separators( $config_file[ $i ] );



	$db = new Database( $config_file[ 0 ], $config_file[ 1 ], $config_file[ 2 ], "application_database" );
	$table = new Table( "users", $db );

	$table_rows = $table->get_table();
	$count = $table->get_row_count();


	$users = [];

	$k = 0;
	for ( $i = 0; $i < $count * 7; $i = $i + 7 )
	{
		if ( $type != "" and $table_rows[ $i + 6 ] != $type )
			continue;


		$temp = new User( $table_rows[ $i ], $table_rows[ $i + 1 ], $table_rows[ $i + 2 ], $table_rows[ $i + 3 ], $table_rows[ $i + 4 ], $table_rows[ $i + 5 ], $table_rows[ $i + 6 ] );

		$users[ $k ] = $temp;

		$k = $k + 1;
	}

	return $users;
}

function get_user_through_id ( int $id )
{
	$users = get_users();

	foreach ( $users as $user )
	{
		if ( $user->get_id() == $id )
		{
			return $user;
		}
	}
}

function get_user_through_username ( string $username )
{
	$users = get_users();

	foreach ( $users as $user )
	{
		if ( $user->get_username() == $username )
		{
			return $user;
		}
	}
}







function username_corresponds_with_password ( string $username, string $password )
{
	$users = get_users();

	foreach ( $users as $user )
	{
		if ( $user->get_username() == $username and $user->get_password() == $password )
			return $user;
	}

	return null;
}

function get_user_id ()
{
	$users = get_users();

	$id = 0;
	foreach ( $users as $user )
	{
		if ( $user->get_id() == $id )
			$id = $id + 1;
		else
			return $id;
	}

	return $id;
}