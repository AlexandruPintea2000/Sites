
var have_alerts = false;

document.write( "<div style=\"left: -410px;\" id=\"alerts\" title=\"Click to Dismiss\" onclick=\"alerts_hidden()\"> " );
document.write( 	"<p><b>oftile:</b> [ Class Name / Tag Name / Id / Attribute ] - s are invalid in \"oftile()\": </p>" );
document.write( "</div>" );

for ( var i = 0; i < 10000005; i = i + 1 )
	var l = i;

document.write( "<link rel=\"stylesheet\" href=\"oftile/style.css\" />");


// For alerts
function alerts_visible()
{
	var alerts = document.getElementById( "alerts" );
	alerts.style.left = "10px";
}

function alerts_hidden()
{
	var alerts = document.getElementById( "alerts" );
	alerts.style.left = "-410px";
}

function alerted( tag_name )
{
	var alerts = document.getElementById( "alerts" );
	var children = alerts.children;

	for ( var i = 0; i < children.length; i = i + 1 )
		if ( children[ i ].innerHTML == "\"" + tag_name + "\" " )
			return true;

	return false;

}

function add_alert( tag_name )
{
	if ( alerted( tag_name ) )
		return;

	tag_name = "\"" + tag_name + "\" ";

	if ( have_alerts == false )
	{
		alerts_visible();
		have_alerts = true;
	}

	var node = document.createElement( "span" );             // Create a <span> node
	var textnode = document.createTextNode( tag_name );      // Create a text node
	node.appendChild(textnode);                              // Append the text to <span>
	document.getElementById("alerts").appendChild(node);     // Append <span> to #alerts 
}





// Getting Id-s / Attributes / Tag Names / Class Names
function get_oftiles ( tag_name )
{
	var child;
 	child = document.body.children;
 	var children = [];

	for ( var i = 0; i < child.length; i = i + 1 )
	    children.push( child.item( i ) );


 	for ( var i = 0; i < children.length; i = i + 1 )
 	{
 		var chld = children[ i ].children;

 		if ( chld.length != 0 )
 			for ( var l = 0; l < chld.length; l = l + 1 )
 				children.push( chld[ l ] );
 	}


	var oftls = [];


	// Tag Name
	if ( tag_name != null )
		oftls = document.getElementsByTagName( tag_name );

	// Class Name
	if ( oftls.length == 0 && tag_name != null )
	{
		oftls = document.getElementsByClassName( tag_name );

		// Id ( or more id-s )
		if ( oftls.length == 0 )
		{
			id_array = []; // ( in case you have more with the same id )
			id_counter = 0;
			for ( var l = 0; l < children.length; l = l + 1 )
			{
				if ( children[ l ].id == tag_name ) // id-s
				{
					id_array[ id_counter ] = children[ l ];
					id_counter = id_counter + 1;
				}

				if ( children[ l ].hasAttribute( tag_name ) ) // also with attr-s
				{
					id_array[ id_counter ] = children[ l ];
					id_counter = id_counter + 1;
					continue;
				}

				var atts = children[ l ].attributes; // and with attr values
				var attributes = [];

				for ( var i = 0; i < atts.length; i = i + 1 )
				    attributes.push(atts[i].nodeName);

				for ( var i = 0; i < attributes.length; i = i + 1 )
				    if ( children[ l ].getAttribute( attributes[ i ] ) == tag_name )
				    {
						id_array[ id_counter ] = children[ l ];
						id_counter = id_counter + 1;
						break;				    	
				    }

			}

			oftls = id_array;
		}
	}


	// For all
	if ( tag_name == null )
	 	oftls = children;

	 return oftls;
}





// Preventing oftile for Id-s / Attributes / Tag Names / Class Names
var prevent_oftile = [];

function is_in_prevent_oftile( oftl )
{
	for ( var i = 0; i < prevent_oftile.length; i = i + 1 )
		if ( prevent_oftile[ i ] == oftl )
			return true;

	return false;
}

function oftile_prevent ( tag_name )
{
	oftls = get_oftiles( tag_name );

	for ( var i = 0; i < oftls.length; i = i + 1 )
		prevent_oftile.push( oftls[ i ] );
}



// oftile for Id-s / Attributes / Tag Names / Class Names
function oftile ( tag_name = null )
{
	oftls = get_oftiles( tag_name );


	if ( oftls.length == 0 )
	{
		add_alert( tag_name );
		return;
	}



	var title = "";
	for ( var l = 0; l < oftls.length; l = l + 1 )
	{
		if ( oftls[ l ].hasAttribute( "oftile" ) )
			continue;

		if ( is_in_prevent_oftile( oftls[ l ] ) )
			continue;



		if ( oftls[ l ] == null )
		{
			add_alert( tag_name );
			return;
		}



		title = "<" + oftls[ l ].tagName.toLowerCase() + ">";



		var atts = oftls[ l ].attributes;
		var attributes = [];

		for ( var i = 0; i < atts.length; i = i + 1 )
		    attributes.push(atts[i].nodeName);

		if ( attributes.length != 0 )
			title = title + ":\n";

		for ( var i = 0; i < attributes.length; i = i + 1 )
		{
			attr = oftls[ l ].getAttribute( attributes[i] );

			if ( attr.length > 40 )
			{
				initialAttr = attr;
				attr = "";

				for ( var k = 0; k <= 40; k = k + 1 )
					attr = attr + initialAttr[ k ];

				attr = attr + "...";
			}

			title = title + attributes[i] + " = " + '"' + attr + '"' + "\n";			
		}


		innerHtml = oftls[ l ].innerHTML;

		if ( innerHtml.length > 40 )
		{
			initialInnerHtml = innerHtml;
			innerHtml = "";

			for ( var k = 0; k <= 40; k = k + 1 )
				innerHtml = innerHtml + initialInnerHtml[ k ];

			innerHtml = innerHtml + "...";
		}

	

	
		if ( attributes.length != 0 )
			title = title + "-\nInner Html:\n" + innerHtml + "\n";
		else
			title = title + " - Inner Html:\n" + innerHtml + "\n";



		oftls[ l ].setAttribute( "title", title );
		oftls[ l ].setAttribute( "oftile", "true" );

	}
}


oftile_prevent ( "script" );
oftile_prevent ( "style" );