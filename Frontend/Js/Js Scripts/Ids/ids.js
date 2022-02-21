var chld = document.body.children;

function add_ids ( chld )
{
  for ( var i = 0; i < chld.length; i = i + 1 )
  {
      if ( ! chld[i].hasAttribute( "id" ) )
        chld[i].setAttribute( "id", "id_" + String( i + 1 ) ;
      if ( chld[i].chldren.length != 0 )
      	add_ids( chld[i].chldren );
  }
}