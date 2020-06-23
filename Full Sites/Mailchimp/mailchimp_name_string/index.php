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
		private $person_name;
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

		public function get_person_name ()
		{
			return $this->person_name;
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

		public function set_person_name ( string $person_name )
		{
			$this->person_name = $person_name;
		}

		public function set_email ( string $email )
		{
			$this->email = $email;
		}

		public function set_table ( Table $table )
		{
			$this->table = $table;
		}




		public function __construct( Table $table, int $id, string $person_name, string $email )
		{
			$this->table = new Table( $table->get_table_name(), $table->get_database() );

			$this->id = $id;
			$this->person_name = $person_name;
			$this->email = $email;
		}

		public function person_remake ( Person $person )
		{
			$this->__construct( $person->get_table(), $person->get_id(), $person->get_person_name(), $person->get_email() );
		}





		public function save ()
		{
			$row = array( $this->id, $this->person_name, $this->email );

			if ( $this->array_of_empty(  $this->table->get_row_through_id( $this->id )  ) )
				$this->table->add_row( $row );
			else
				$this->table->update_row( $row );
		}

		public function remove ()
		{
			$this->table->remove_row_through_id(  $this->id );
		}


	}





	function get_persons ( Table $table )
	{
		$persons_array = array();
		$persons_array = $table->get_table();

		$row_count = $table->get_row_count();

		$persons = array();
		$k = 0;
		$person_name;
		$email;
		for ( $i = 0; $i < $row_count * 3; $i = $i + 1 )
		{
			if ( $i % 3 == 0 )
				continue;

			if ( $i % 3 == 1 )
				$person_name = $persons_array[ $i ];

			if ( $i % 3 == 2 )
			{
				$email = $persons_array[ $i ];

				$person = new Person( $table, $persons_array[ $i - 2 ], $person_name, $email );

				$persons[ $k ] = $person;
				$k = $k + 1;
			}
		}

		return $persons;
	}



	// Actual website

	style();


	$db = new Database( "localhost", "root", "", "db"  );
	$table = new Table( "emails", $db );

	$person = new Person( $table, 104, "person104", "email104@mail.com" );
	$person->person_remake( $person ); // only to make sure it actually does what it should

	$person->save();
//	$person->remove();


	$table_rows = $table->get_table();
	$row_count = $table->get_row_count();

	echo "'emails' <br>";
	echo "id  person  email <hr>";

	for ( $i = 0; $i < $row_count * 3; $i = $i + 1 )
	{
		echo $table_rows[ $i ] . " ";
		if ( $i % 3 == 2 )
			echo " <br> ";
	}

	?>




	<?php

//	"https://us19.api.mailchimp.com/3.0";
//	"curl --request GET --url 'https://us19.api.mailchimp.com/3.0/' --user 'anystring:50729600d3ed998b72a385c40c14b4e2'";


	require('mailchimp-api-php/src/Mailchimp.php');    // You may have to modify the path based on your own configuration.

	$api_key = "50729600d3ed998b72a385c40c14b4e2-us19";
	$list_id = "a44056eaab";

	$Mailchimp = new Mailchimp( $api_key );
	$Mailchimp_Lists = new Mailchimp_Lists( $Mailchimp );


	$table = new Table( "emails", $db );
	$persons = get_persons( $table );

	foreach ( $persons as $i )
	{
		try
		{
		    $subscriber = $Mailchimp_Lists->subscribe(
		        $list_id,
		        array('email' => $i->get_email() ),      // Specify the e-mail address you want to add to the list.
		        array('FNAME' => $i->get_person_name(), 'LNAME' => $i->get_person_name() ),   // Set the first name and last name for the new subscriber.
		        'text',    // Specify the e-mail message type: 'html' or 'text'
		        FALSE,     // Set double opt-in: If this is set to TRUE, the user receives a message to confirm they want to be added to the list.
		        TRUE       // Set update_existing: If this is set to TRUE, existing subscribers are updated in the list. If this is set to FALSE, trying to add an existing subscriber causes an error.
		    );
		} 
		catch (Exception $e) 
		{
		    echo "Caught exception: " . $e;
		}

		if ( ! empty($subscriber['leid']) )
		{
		    echo "Subscriber " . $i->get_person_name() . " added successfully.";
		}
		else
		{
		    echo "Subscriber " . $i->get_person_name() . " add attempt failed.";
		}

		echo "<br>";
	}

	?>

	</body>
</html>
