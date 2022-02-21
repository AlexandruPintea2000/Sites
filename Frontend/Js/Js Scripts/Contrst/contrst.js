console.log ( "contrst" );

function get_attributes ()
{
	console.log( "fle" );
	var v;
	var tag = v.tagName;
	var text = innerText;
	var attr = v.attributes;
	var attr_arr = [];
	var l = 0;
	while ( attr.item( l ) != null )
	{
		attr_arr.push( attr.item( l ) );
		l = l + 1;
	}
	console.log( attr );
	console.log( attr_arr );
}

function set_click ()
{
	var chld = document.body.children;
	for ( var i = 0; i < chld.length; i = i + 1 )
	{
		if ( chld[i].hasAttribute( "onclick" ) )
			chld[i].setAttribute( "onclick", chld[i].getAttribute( "onclick" ) + "; " + "get_attributes()" );
		else
			chld[i].setAttribute( "onclick", "get_attributes()" );
	}
}

set_click ();