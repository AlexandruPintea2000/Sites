<!DOCTYPE html>
<html>
	<body>



	<?php

	require "model.php";

	function style ()
	{
		echo "
			<link rel=\"stylesheet\" href=\"files/style.css\">
			<meta name=\"viewport\" content=\"width=device-width, initial-scale=1\">
			";	
	}





	class Person extends Table
	{
		private $id;
		private $fname;
		private $lname;
		private $email;

		private $table;

		private function array_of_empty ( $data )
		{
			foreach ( $data as $i )
				if ( !empty( $i ) )
					return false;

			return true;
		}




		public function get_id ()
		{
			return $this->id;
		}

		public function get_fname ()
		{
			return $this->fname;
		}

		public function get_lname ()
		{
			return $this->lname;
		}

		public function get_email ()
		{
			return $this->email;
		}

		public function get_table ()
		{
			return $this->table;
		}





		public function set_id ( int $id )
		{
			$this->id = $id;
		}

		public function set_fname ( string $fname )
		{
			$this->fname = $fname;
		}

		public function set_lname ( string $lname )
		{
			$this->lname = $lname;
		}

		public function set_email ( string $email )
		{
			$this->email = $email;
		}

		public function set_table ( Table $table )
		{
			$this->table = $table;
		}




		public function __construct( Table $table, int $id, string $fname, string $lname, string $email )
		{
			$this->table = new Table( $table->get_table_name(), $table->get_database() );

			$this->id = $id;
			$this->fname = $fname;
			$this->lname = $lname;
			$this->email = $email;
		}

		public function person_remake ( Person $person )
		{
			$this->__construct( $person->get_table(), $person->get_id(), $person->get_fname(), $person->get_lname(), $person->get_email() );
		}





		public function save ()
		{
			$row = array( $this->id, $this->fname, $this->lname, $this->email );

			if ( $this->array_of_empty(  $this->table->get_row_through_id( $this->id )  ) )
				$this->table->add_row( $row );
			else
				$this->table->update_row( $row );
		}

		public function remove ()
		{
			$this->table->remove_row_through_id( $this->id );
		}


	}











	function get_persons ( Table $table )
	{
		$persons_array = array();
		$persons_array = $table->get_table();

		$row_count = $table->get_row_count();

		$persons = array();
		$k = 0;
		$fname;
		$lname;
		$email;
		for ( $i = 0; $i < $row_count * 4; $i = $i + 1 )
		{
			if ( $i % 4 == 0 )
				continue;

			if ( $i % 4 == 1 )
				$fname = $persons_array[ $i ];

			if ( $i % 4 == 2 )
				$lname = $persons_array[ $i ];

			if ( $i % 4 == 3 )
			{
				$email = $persons_array[ $i ];

				$person = new Person( $table, $persons_array[ $i - 3 ], $fname, $lname, $email );

				$persons[ $k ] = $person;
				$k = $k + 1;
			}
		}

		return $persons;
	}


	function add_person_to_mailchimp ( Person $person, Mailchimp_Lists $Mailchimp_Lists, string $list_id )
	{
		try
		{
		    $subscriber = $Mailchimp_Lists->subscribe(
		        $list_id,
		        array('email' => $person->get_email() ),      // Specify the e-mail address you want to add to the list.
		        array('FNAME' => $person->get_fname(), 'LNAME' => $person->get_lname() ),   // Set the first name and last name for the new subscriber.
		        'text',    // Specify the e-mail message type: 'html' or 'text'
		        FALSE,     // Set double opt-in: If this is set to TRUE, the user receives a message to confirm they want to be added to the list.
		        TRUE       // Set update_existing: If this is set to TRUE, existing subscribers are updated in the list. If this is set to FALSE, trying to add an existing subscriber causes an error.
		    );
		} 
		catch (Exception $e) 
		{
		    die("Caught exception: ". $e);
		}

		if ( ! empty($subscriber['leid']) )
			return true;
		else
			return false;
	}












	// emails: id, fname, lname, email

	// Actual website

	style();


	$db = new Database( "localhost", "root", "", "db"  );
	$table = new Table( "emails", $db );

	$person = new Person( $table, 104, "fperson104", "lperson104", "email104@mail.com" );
	$person->person_remake( $person ); // only to make sure it actually does what it should

	$person->save();
//	$person->remove();


	$table_rows = $table->get_table();
	$row_count = $table->get_row_count();

	echo "'emails' <br>";
	echo "id  fname  lname  email <hr>";

	for ( $i = 0; $i < $row_count * 4; $i = $i + 1 )
	{
		echo $table_rows[ $i ] . " ";
		if ( $i % 4 == 3 )
			echo " <br> ";
	}

	?>




	<?php

	echo " <br> Added: <br> ";

	?>




	<?php

	require('mailchimp-api-php/src/Mailchimp.php');    // You may have to modify the path based on your own configuration.

	$api_key = "50729600d3ed998b72a385c40c14b4e2-us19";
	$list_id = "a44056eaab";

	$Mailchimp = new Mailchimp( $api_key );
	$Mailchimp_Lists = new Mailchimp_Lists( $Mailchimp );


	$table = new Table( "emails", $db );
	$persons = get_persons( $table );


	foreach ( $persons as $i )
	{
		if ( add_person_to_mailchimp( $i, $Mailchimp_Lists, $list_id ) )
		    echo "'" . $person->get_fname() . ' ' . $i->get_lname() . "' added successfully.";
		else
		    echo "'" . $person->get_lname() . ' ' . $i->get_lname() . "' add attempt failed.";

		echo "<br>";
	}

	?>

	</body>
</html>
