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
		if ( chldn[i].children.length > 0 )
			result = result.concat( get_children( chldn[i] ) );
	}

	return result;
}

var chldn = get_children( document.body ); // get all

var color = "black";
var background_color = "white";

document.body.style.backgrounColor = background_color;

for ( var i = 0; i < chldn.length; i = i + 1 ) // mark all
{
	if ( chldn[i] == undefined )
		continue;
	chldn[i].style.color = color;
	chldn[i].style.backgroundColor = background_color; 
}
