<?php

include_once "Controller.php";
include_once "models/User.php";

class Sign_in_controller extends Controller
{

	// For "Signing in"


	function sign_in ( $username_password = null )
	{
		if ( isset( $_SESSION[ 'id' ] ) )
		{
			$this->replace_location( "index.php?path=error" );	
			return;	
		}

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
		if ( isset( $_SESSION[ 'id' ] ) )
		{
			$this->replace_location( "index.php?path=error" );	
			return;	
		}


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
			{
				if ( $user->get_type() == "admin" )
					$_SESSION[ 'admin_welcome' ] = true;

				$this->replace_location( "index.php?path=user_controller/view/" . $user->get_id() );
			}
		}

		$this->alert( "Unable to Sign in. Please Retry." );

		$this->load_view( "sign_in", "sign_in", $data );	
	}



	// For "Signing up"



	function sign_up ( $username_password = null )
	{
		if ( isset( $_SESSION[ 'id' ] ) )
		{
			$this->replace_location( "index.php?path=error" );	
			return;	
		}



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

	function user_sign_up ( $data )
	{
		if ( isset( $_SESSION[ 'id' ] ) )
		{
			$this->replace_location( "index.php?path=error" );	
			return;	
		}



		$parts = explode( "(@)", $data );

		$parts[ 3 ] = ucfirst( $parts[ 3 ] );
		$parts[ 4 ] = ucfirst( $parts[ 4 ] );

    	$data = [
            'company'=>$this->get_company(),
            'initialization_id'=>$parts[ 0 ],
            'username'=>$parts[ 1 ],
            'password'=>$parts[ 2 ],
            'firstname'=>$parts[ 3 ],
            'lastname'=>$parts[ 4 ],
            'email'=>$parts[ 5 ],
            'type'=>$parts[ 6 ]
            ];

        if ( ! $this->initialization_id_corresponds( $parts[ 0 ], $parts[ 6 ] ) )
        {
//        	$this->alert( "Client Id - Invalid." );
			$this->load_view( "sign_in", "sign_up", $data );
        	return;
        }

		if ( username_taken( $parts[ 1 ] ) )
		{
			$this->alert( "Username already taken. Please retry." );
			$this->load_view( "sign_in", "sign_up", $data );
			return;
		}

		$db = new Database( $this->get_server(), $this->get_user(), $this->get_password(), "application_database" );
		$table = new Table ( "users", $db );

		$row = [ get_user_id(), $parts[ 1 ], $parts[ 2 ], $parts[ 3 ], $parts[ 4 ], $parts[ 5 ], $parts[ 6 ] ];
		$table->add_row( $row );

		$this->alert( "User registered" );


		// Email users



		// $users = get_users();
		// foreach ( $users as $user )
		// {
		// 	if ( $user->get_type() == "client" )
		// 		continue;


		// 	$subject = ucfirst( $parts[ 6 ] ) . ": " . $parts[ 3 ] . ' ' . $parts[ 4 ] . " has signed up!";

		// 	$email = "";

		// 	$type = $parts[ 6 ];

		// 	if ( $type == "client" )
		// 	{
		// 		if ( $user->get_type() == "admin" )
		// 			$email = "Make sure to add a Countract for " . $parts[ 3 ] . ' ' . $parts[ 4 ] . '!';
		// 		else
		// 			$email = "Client: " $parts[ 3 ] . ' ' . $parts[ 4 ] . "has signed up with username: " . $parts[ 1 ] . '.';
		// 	}

		// 	if ( $type == "employee" )
		// 	{
		// 		if ( $user->get_type() == "admin" )
		// 			$email = "Make sure to add " . $parts[ 3 ] . ' ' . $parts[ 4 ] . " to a part of a contract and give " . $parts[ 3 ] . ' ' . $parts[ 4 ] . " part tasks!";
		// 		else
		// 			$email = "Employee: " . $parts[ 3 ] . ' ' . $parts[ 4 ] . "has signed up with username: " . $parts[ 1 ] . '.';

		// 	}

		// 	if ( $type == "admin" )
		// 		$email = "Admin: " . $parts[ 3 ] . ' ' . $parts[ 4 ] . "has signed up with username: " . $parts[ 1 ] . '.';				

		// 	mail( $user->get_email(), $subject, $email );
		// }


		$this->load_view( "sign_in", "sign_in", $data );
	}

	function initialization_id_corresponds ( $initialization_id, $type )
	{
		if ( isset( $_SESSION[ 'id' ] ) )
		{
			$this->replace_location( "index.php?path=error" );	
			return;	
		}



		$initialization_id_file = fopen( 'initialization_id', 'r' );

		$strings = fgets( $initialization_id_file );

		fclose( $initialization_id_file );

		if ( empty( $strings ) )
		{
			$initialization_id_file = fopen( 'initialization_id', 'w' );
//			$this->make_initialization_id();
			fclose( $initialization_id_file );

			return false;
		}



		$file_initialization_ids = explode( ' ', $strings );

		foreach ( $file_initialization_ids as $file_initialization_id )
			if ( $file_initialization_id == $initialization_id )
			{
				$digit = (int) ( $initialization_id / 100000000 );
				$initialization_type = "invalid";

				if ( $digit == 1 )
					$initialization_type = "admin";

				if ( $digit == 4 )
					$initialization_type = "employee";

				if ( $digit == 5 )
					$initialization_type = "client";

				if ( $type == $initialization_type )
				{
					$initialization_id_file = fopen( 'initialization_id', 'w' );
					fclose( $initialization_id_file ); // clears the file

				
					$initialization_id_file = fopen( 'initialization_id', 'w' );
					foreach ( $file_initialization_ids as $remaining_id )
						if ( $remaining_id != $initialization_id )
							fwrite( $initialization_id_file, $remaining_id . ' ' );
					fclose( $initialization_id_file ); // deletes used id


					return true;
				}

				$this->alert( "Almost right - Your type should be: " . $initialization_type );
				return false;
			}
		

		$this->alert( "Initialization Id - Invalid" );
		return false;
	} 


	// For "Signing out"



	function user_sign_out ()
	{
        session_unset();
		session_destroy();

        $this->signed_in();
	}
}