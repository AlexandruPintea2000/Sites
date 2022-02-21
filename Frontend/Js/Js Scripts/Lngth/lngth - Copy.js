console.log( "thme" );

function get_children ( v ) // gives array of chld
{
	var chldn = v.children;
	var result = [];

	for ( var i = 0; i < chldn.length; i = i + 1 )
	{
		if ( chldn[i] == undefined )
			continue;
		result.push( chldn[i] );
		// if ( chldn[i].children.length > 0 )
		//	result = result.concat( get_children( chldn[i] ) );
	}

	return result;
}

var chldn = get_children( document.body ); // get all

var max_length = 0;
var max_chld;

for ( var i = 0; i < chldn.length; i = i + 1 ) // mark all
{
	if ( chldn[i].innerText.length > max_length && chldn[i].tagName != "SCRIPT" )
	{
		max_length = chldn[i].innerText.length;
		max_chld = i;
	}
}

for ( var i = 0; i < chldn.length; i = i + 1 ) // mark all
{
	if ( i != max_chld )
	{
		chldn[i].style.display = "none";
		console.log( "Child of tag '" + chldn[i].tagName + "' was hidden." );
	}
	else
	{
		console.log( "Child of tag '" + chldn[i].tagName + "' stays." );
	}
}