<?php



// Make Database and Table

$q = $_GET["q"];

include_once "Model.php";

if ( $q == "load" )
{
  $conn = new mysqli( "localhost", "root", "" );

  $conn->query( "DROP DATABASE ajax;" );
  $conn->query( "CREATE DATABASE ajax;" );

  $db = new Database( "localhost", "root", "", "ajax" );
  $db->sql( "CREATE TABLE persons ( id int, name varchar(255) )" );
  $table = new Table ( "persons", $db );

  $a[] = "Amelia"; $a[] = "Brian";
  $a[] = "Diana"; $a[] = "Eva";
  $a[] = "Fiona"; $a[] = "Gunda";
  $a[] = "Hege"; $a[] = "Inga";
  $a[] = "Johanna"; $a[] = "Linda";
  $a[] = "Ophelia"; $a[] = "Wenche";
  $a[] = "Raquel"; $a[] = "Cindy";
  $a[] = "Doris"; $a[] = "Eve";
  $a[] = "Evita"; $a[] = "Sunniva";
  $a[] = "Liza"; $a[] = "Vicky"; 
  $a[] = "Ellen"; $a[] = "Luis";

  for ( $i = 0; $i < count( $a ); $i = $i + 1 )
  {
    $row = [ $i, $a[ $i ] ];
    $table->add_row( $row );
  }

  echo ( "<script> location.replace( \"/ajax/ajax.html\" ); </script>" );
}
else
{
  $db = new Database( "localhost", "root", "", "ajax" );
  $table = new Table ( "persons", $db );



  // Get Table Data




  $persons = $table->get_table();

  $names = [];
  $l = 0;
  for ( $i = 1; $i < count( $persons ); $i = $i + 2 )
  {
    $names[ $l ] = $persons[ $i ];
    $l = $l + 1;
  }

  $a = $names;








  // get the q parameter from URL
  $q = $_GET["q"];

  $hint = "";

  // lookup all hints from array if $q is different from ""
  if ($q !== "") {
    $q = strtolower($q);
    $len = strlen($q);
    foreach($a as $name)
    {
      if (stristr($q, substr($name, 0, $len))) 
      {
        if ($hint === "") { $hint = $name; } 
        else { $hint .= ", $name";} 
      }
    }
  }

  // Output "no suggestion" if no hint was found or output correct values
  echo $hint === "" ? "invalid" : $hint;

}


?>