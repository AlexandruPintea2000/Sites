console.log( "Encapsulation" );

var count = {}; // for every tag name, count num of appearances
var enc_frev = {}; // for every encapsulation know its freqvency

function get_children ( v, encapsulation ) // gives array of { chld: ..., enc: ... }
{
	var chldn = v.children;
	var result = [];

	for ( var i = 0; i < chldn.length; i = i + 1 )
	{
		if ( chldn[i] == undefined )
			continue;
		result.push( { "chld":chldn[i], "enc":encapsulation } );
		if ( chldn[i].children.length > 0 )
			result = result.concat( get_children( chldn[i] , encapsulation + 1 ) );
	}

	return result;
}

var chldn = get_children( document.body, 0 ); // get all with their encapsulation

for ( var i = 0; i < chldn.length; i = i + 1 ) // mark all
{
	if ( chldn[i].chld == undefined )
		continue;

	// chldn[i].chld.addEventListener( "mouseover", function(){ console.log( "hover" ) } ); // show encapsulation, tag name and color background on hover

	if ( count[ chldn[i].chld.tagName ] == undefined )
		count[ chldn[i].chld.tagName ] = 1;
	else
		count[ chldn[i].chld.tagName ] = count[ chldn[i].chld.tagName ] + 1;

	if ( enc_frev[ chldn[i].enc ] == undefined )
		enc_frev[ chldn[i].enc ] = 1;
	else
		enc_frev[ chldn[i].enc ] = enc_frev[ chldn[i].enc ] + 1;

	chldn[i].chld.style.color = "black";
	chldn[i].chld.style.backgroundColor = "rgb( 132, 123, 123 )"; // could also have encapsulation
	chldn[i].chld.style.border = "3px solid black";	
	chldn[i].chld.style.borderRadius = "5px";
	chldn[i].chld.style.padding = "2px";	
}

// container to show data
var control = document.createElement( "div" );
control.style.width = "290px";
control.style.height = "390px";
control.style.position = "fixed";
control.style.padding = "15px";
control.style.borderRadius = "15px";
control.style.bottom = "15px";
control.style.right = "15px";
control.style.border = "3px solid black";
control.style.zIndex = "900000";
control.style.backgroundColor = "white";

// for visible table data
var table = document.createElement( "table" );
table.style.width = "100%";

// add table row
function row ( table, text1, text2 )
{
	var table_row = document.createElement( "tr" );
	var td1 = document.createElement( "td" );
	td1.style.border = "2px solid black";
	td1.style.borderRadius = "5px";
	td1.style.padding = "5px 10px";
	var td2 = document.createElement( "td" );
	td2.style.border = "2px solid black";
	td2.style.borderRadius = "5px";
	td2.style.padding = "5px 10px";
	td1.innerText = text1;
	td2.innerText = text2;
	table_row.appendChild( td1 );
	table_row.appendChild( td2 );
	table.appendChild( table_row );
}

row( table, "Count", chldn.length );
var avg_frev = 0;
var max_frev = 0;
var last_frev;
for ( i in enc_frev )
{
	if ( enc_frev[i] > max_frev )
	{
		max_frev = enc_frev[i]
		avg_frev = i;
	}
	last_frev = i;
}
row( table, "Avg. Encp.", avg_frev );
row( table, "Max. Encp.", last_frev );

control.appendChild( table );

// sort count
var count_tag = [];
var count_frev = [];

for ( i in count )
{
	count_tag.push( i );
	count_frev.push( count[i] );
}

for ( var i = 0; i < count_frev.length - 1; i = i + 1 )
	for ( var l = i + 1; l < count_frev.length; l = l + 1 )
		if ( count_frev[i] > count_frev[l] )
		{
			var swap_frev = count_frev[i];
			var swap_tag = count_tag[i];

			count_frev[i] = count_frev[l];
			count_tag[i] = count_tag[l];

			count_frev[l] = swap_frev;
			count_tag[l] = swap_tag;
		}

count = {};

for ( var i = 0; i < count_frev.length; i = i + 1 )
	count[ count_tag[i] ] = count_frev[i];


function dropdown ( summary_text, table ) // make dropdowns
{
	var details = document.createElement( "details" );
	var container = document.createElement( "div" );
	container.style.height = "39px";
	container.style.overflowY = "scroll";
	var summary = document.createElement( "summary" );
	summary.style.border = "2px solid black";
	summary.style.borderRadius = "5px";
	summary.style.padding = "2px 5px";
	summary.innerText = summary_text;

	details.appendChild( summary );
	container.appendChild( table );	
	details.appendChild( container );	
	control.appendChild( details );

	// for separation
	var div = document.createElement( "div" );
	div.style.height = "2px";
	control.appendChild( div );
}

function length ( v ) // length for Json 'array' object
{
	var count = 0;
	for ( var i in v )
		count = count + 1;
	return count;
}

// dropdowns
var frev_table = document.createElement( "table" );
frev_table.style.width = "100%";
for ( i in enc_frev )
	row( frev_table, i, enc_frev[i] );
dropdown( "Count by encp. ( " + length( enc_frev ) + " ) asc", frev_table );

var count_table = document.createElement( "table" );
count_table.style.width = "100%";
for ( i in count )
	row( count_table, i, count[i] );
dropdown( "Count by tag ( " + length( count ) + " ) asc", count_table );

var appear_table = document.createElement( "table" );
appear_table.style.width = "100%";
for ( var i = 0; i < chldn.length; i = i + 1 )
	row( appear_table, chldn[i].chld.tagName, chldn[i].enc );
dropdown( "Encp. appear order ( " + chldn.length + " )", appear_table );

// show console data
console.log( "Encp. frev = { frev: count, frev: count, ... }" );
console.log( enc_frev );
console.log( "Count tags = { tag: count, tag: count, ... }" );
console.log( count );

// show data
document.body.appendChild( control ); // used to control and show data