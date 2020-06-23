<?php

include_once "Model.php";

class Editfile_model extends Table {

	private $id;
	private $fname;
	private $lname;
	private $email;


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


	public function __construct( $id, $fname, $lname, $email )
	{
		$this->id = $id;
		$this->fname = $fname;
		$this->lname = $lname;
		$this->email = $email;
	}

}


function get_editfiles()
{
	$db = new Database( "localhost", "root", "", "db" );
	$table = new Table( "emails", $db );

	$table_rows = $table->get_table();
	$count = $table->get_row_count();

	$editfiles = [];

	$k = 0;
	for ( $i = 0; $i < $count * 4; $i = $i + 4 )
	{
		$temp = new Editfile_model( $table_rows[ $i ], $table_rows[ $i + 1 ], $table_rows[ $i + 2 ], $table_rows[ $i + 3 ] );

		$editfiles[ $k ] = $temp;

		$k = $k + 1;
	}

	return $editfiles;
}
