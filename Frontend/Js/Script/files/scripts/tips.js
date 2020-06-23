document.write( "<div id=\"tooltip\"> " );
document.write( "	<span id=\"tooltiptext\"> " );
document.write( "	</span>" );
document.write( "</div>" );	


function tip_visible ( tip_content )
{
	var tooltiptext = document.getElementById ( "tooltiptext" );
	var tooltip = document.getElementById ( "tooltip" );

	tooltiptext.innerHTML = tip_content;


 	tooltip.style.right = "5px";
}

function tip_hidden ()
{
	var tooltiptext = document.getElementById ( "tooltiptext" );
	var tooltip = document.getElementById ( "tooltip" );

 	tooltip.style.right = "-20%";
}

function set_tips ( tag_name )
{
	var tips = document.getElementsByTagName( tag_name );


	for ( i = 0; i < tips.length; i = i + 1  )
	{
		if ( tips[ i ].getAttribute( "title" ) == null )
			continue;

		var title = tips[ i ].getAttribute( "title" );

		tips[ i ].setAttribute( "onmouseover", 'tip_visible( "' + title + '" )' );
		tips[ i ].setAttribute( "onmouseleave", "tip_hidden()" );

		tips[ i ].setAttribute( "title", "" );
	}
}


set_tips( "p" );
set_tips( "a" );
set_tips( "b" );
set_tips( "u" );
set_tips( "i" );
set_tips( "h1" );
set_tips( "h2" );
set_tips( "h3" );
set_tips( "h4" );
set_tips( "h5" );
set_tips( "h6" );
set_tips( "span" );
set_tips( "div" );
