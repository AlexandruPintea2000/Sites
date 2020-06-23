<?php

include_once "Controller.php";
include_once "models/User.php";

class Sign_in_controller extends Controller
{

	// For "Signing in"


	function sign_in ( $username_password = null )
	{
		$username = "";
		$password = "";

		if ( $username_password != null )
		{
			$parts = explode( '(@)', $username_password );

			$username = $parts[ 0 ];
			$password = $parts[ 1 ];
		}

    	$data = [
            'username'=>$username,
            'password'=>$password
            ];

		$this->load_view( "sign_in", "sign_in", $data );
	}

	function user_sign_in ( $data )
	{
		$parts = explode( "(@)", $data );

		$username = $parts[ 0 ];
		$password = $parts[ 1 ];

    	$data = [
            'username'=>$username,
            'password'=>$password
            ];

        $user = username_corresponds_with_password( $username, $password );

		if ( $user != null )
		{
	        $_SESSION[ 'signed_in' ] = true;
	        $_SESSION[ 'id' ] = $user->get_id();
	        $_SESSION[ 'name' ] = $user->get_firstname() . ' ' . $user->get_lastname();
	        $_SESSION[ 'email' ] = $user->get_email();
	        $_SESSION[ 'username' ] = $user->get_username();
	        $_SESSION[ 'type' ] = $user->get_type();

			if ( $user->get_type() == "client" )
				$this->replace_location( "index.php?path=user_controller/client_view/" . $user->get_id() );
			else
				$this->replace_location( "index.php?path=user_controller/view/" . $user->get_id() );
		}

		$this->alert( "Unable to Sign in. Please Retry." );

		$this->load_view( "sign_in", "sign_in", $data );	
	}



	// For "Signing up"



	function sign_up ( $username_password = null )
	{
		$username = "";
		$password = "";

		if ( $username_password != null )
		{
			$parts = explode( '(@)', $username_password );

			$username = $parts[ 0 ];
			$password = $parts[ 1 ];
		}


    	$data = [
            'company'=>$this->get_company(),
            'id'=>0,
            'username'=>$username,
            'password'=>$password,
            'firstname'=>"",
            'lastname'=>"",
            'email'=>"",
            'type'=>"client"
            ];

		$this->load_view( "sign_in", "sign_up", $data );
	}

	function username_taken ( $username )
	{
		$users = get_users();

		foreach ( $users as $user )
		{
			if ( $user->get_username() == $username ) 
				return true;
		}

		return false;
	}

	function user_sign_up ( $data )
	{
		$parts = explode( "(@)", $data );

    	$data = [
            'id'=>$parts[ 0 ],
            'username'=>$parts[ 1 ],
            'password'=>$parts[ 2 ],
            'firstname'=>$parts[ 3 ],
            'lastname'=>$parts[ 4 ],
            'email'=>$parts[ 5 ],
            'type'=>$parts[ 6 ]
            ];

		if ( $this->username_taken( $parts[ 1 ] ) )
		{
			$this->alert( "Username already taken. Please retry." );
			$this->load_view( "sign_in", "sign_up", $data );
			return;
		}

		$db = new Database( $this->get_server(), $this->get_user(), $this->get_password(), "application_database" );
		$table = new Table ( "users", $db );

		$row = [ $parts[ 0 ], $parts[ 1 ], $parts[ 2 ], $parts[ 3 ], $parts[ 4 ], $parts[ 5 ], $parts[ 6 ] ];
		$table->add_row( $row );

		$this->alert( "User registered" );

		$this->load_view( "sign_in", "sign_in", $data );
	}



	// For "Signing out"



	function user_sign_out ()
	{
        session_unset();
		session_destroy();

        $this->signed_in();
	}
}