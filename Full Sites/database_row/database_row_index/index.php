<!DOCTYPE html>
<html>
	<body>



	<?php

	class Database
	{
		private $servername;
		private $username;
		private $password;
		private $dbname;

		private $connection;








		public function get_servername ()
		{
			return $this->servername;
		}

		public function get_username ()
		{
			return $this->username;
		}

		public function get_password ()
		{
			return $this->password;
		}

		public function get_dbname ()
		{
			return $this->dbname;
		}



		public function get_connection ()
		{
			return $this->connection;
		}









		private function db_connection()
		{
			$conn = new mysqli( $this->servername, $this->username, $this->password, $this->dbname );

			if ($conn->connect_error) {
			    die("Connection failed: ". $conn->connect_error);
			}
			else
				return $conn;
		}

		public function __construct( string $servername, string $username, string $password, string $dbname )
		{
			$this->servername = $servername;
			$this->username = $username;
			$this->password = $password;
			$this->dbname = $dbname;

			$this->connection = $this->db_connection();
		}

	}






	class Table extends Database
	{

		private $table_name;



		private $num_columns = 0;

		private $columns = array();
		private $types = array();

		private function have_column ( string $column )
		{
			foreach ( $this->columns as $i )
				if ( $column == $i )
					return true;

			return false;
		}






		public function get_table_name ()
		{
			return $this->table_name;
		}

		public function get_num_columns ()
		{
			return $this->num_columns;
		}

		public function get_columns ()
		{
			return $this->columns;
		}

		public function get_types ()
		{
			return $this->types;
		}








		public function description()
		{
			$sql = "DESCRIBE " . $this->table_name . ";";

			$result = $this->connection->query( $sql );

			if ( $result->num_rows > 0 ) 
			{
			    while( $row = $result->fetch_assoc() ) 
			    {
			        $this->columns[ $this->num_columns ] = $row[ "Field" ];
			        $this->types[ $this->num_columns ] = $row[ "Type" ];
			        $this->num_columns = $this->num_columns + 1;
			    }
			}
		}


		public function __construct( string $table_name, Database $db )
		{
			$this->table_name = $table_name;

			$this->servername = $db->get_servername();
			$this->username = $db->get_username();
			$this->password = $db->get_password();
			$this->dbname = $db->get_dbname();

			$this->connection = $db->get_connection();

			$this->description();
		}











		public function get_column_through_id ( string $column, int $id )
		{
			if ( !$this->have_column( $column ) )
				return;

			$sql = "SELECT " . $column . " FROM " . $this->table_name . " WHERE id = " . $id;

			$result = $this->connection->query( $sql );

			$column_result = "";
			if ( $result->num_rows > 0 ) 
			{
			    while( $row = $result->fetch_assoc() ) 
			    {
			        $column_result = $row[ $column ];
			    }
			}

			return $column_result;
		}

		public function get_row_through_id ( int $id )
		{
			$sql = "SELECT * FROM " . $this->table_name . " WHERE id = " . $id;

			$result = $this->connection->query( $sql );

			$row_result = array();

			$row = $result->fetch_assoc();
			for ( $i=0; $i<$this->num_columns; $i=$i+1 )
		        $row_result[ $i ] = $row[ $this->columns[ $i ] ];
			
			return $row_result;
		}

		public function add_row ( $data )
		{
			$sql = "INSERT INTO " . $this->table_name . ' ( ';

			for ( $i = 0; $i < $this->num_columns; $i = $i + 1 )
			{
				$sql = $sql . $this->columns[ $i ];
				if ( $i != $this->num_columns - 1 )
					$sql = $sql . ", ";
			}


			$sql = $sql . " ) VALUES ( ";
			for ( $i = 0; $i < $this->num_columns; $i = $i + 1 )
			{
				if ( is_string( $data[ $i ] ) )
					$sql = $sql . "'" . $data[ $i ] . "'";
				else
					$sql = $sql . $data[ $i ];

					
				if ( $i != $this->num_columns - 1 )
					$sql = $sql . ", ";
			}
			$sql = $sql . " )";

			$this->connection->query( $sql );
		}


		public function update_row ( $data )
		{
			$sql = "UPDATE " . $this->table_name . " SET ";

			for ( $i = 0; $i < $this->num_columns; $i = $i + 1 )
			{
				if ( $this->columns[ $i ] == "id" )
					continue;

				
				if ( is_string( $this->columns[ $i ] ) )
					$sql = $sql . $this->columns[ $i ] . "=" . "'" . $data[ $i ] . "'";
				else
					$sql = $sql . $this->columns[ $i ] . "=" . $data[ $i ];
				
					
				if ( $i != $this->num_columns - 1 )
					$sql = $sql . ", ";
			}

			for ( $i = 0; $i < $this->num_columns; $i = $i + 1 )
			{
				if ( $this->columns[ $i ] == "id" )
				{
					$sql = $sql . " WHERE id=" . $data[ $i ];
					break;
				}
			}

			$this->connection->query( $sql );
		}

		public function remove_row_through_id ( $id )
		{
			$sql = "DELETE FROM " . $this->table_name . " WHERE id=" . $id;

			$this->connection->query( $sql );
		}

	}









	$db = new Database( "localhost", "root", "", "db" );
	$my_table = new Table ( "my_table", $db );



	$my_table->add_row( array( 0, "val_1", "val_1", "val_1", "val_1"  ) );
	$my_table->add_row( array( 1, "val_1", "val_1", "val_1", "val_1"  ) );
	$my_table->add_row( array( 2, "val_1", "val_1", "val_1", "val_1"  ) );
	$my_table->add_row( array( 3, "val_1", "val_1", "val_1", "val_1"  ) );
	$my_table->add_row( array( 4, "val_1", "val_1", "val_1", "val_1"  ) );

	$my_table->add_row( array( 10, "val", "val", "val", "val"  ) ); // For removal




	foreach ( $my_table->get_columns() as $i )
		echo $i . " ";
	
	echo "<br>";

	foreach ( $my_table->get_types() as $i )
		echo $i . " ";

	echo "<br>";





	echo $my_table->get_column_through_id( "value_1", 10 ) . "<br>";
	foreach ( $my_table->get_row_through_id( 10 ) as $i )
		echo $i . ' ';




	$add_row = array( 19, "val_1", "val_1", "val_1", "val_1"  );
	$my_table->add_row( $add_row );


	$add_row = array( 19, "val_updated", "val_updated", "val_updated", "val_updated"  );
	$my_table->update_row( $add_row );


	$my_table->remove_row_through_id( 10 );


	?>

	</body>
</html>
