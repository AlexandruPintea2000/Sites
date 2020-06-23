var nav_closed = false;

function nav ()
{
	var navs = document.getElementsByTagName( "nav" );
	nav = navs[ 0 ];

	var hrefs = nav.children;


	var nav_div = document.createElement( "div" );
	nav_div.setAttribute( "class", "nav_div" );


	var d_nav = document.createElement( "div" );
	d_nav.setAttribute( "id", "d_nav" );
	d_nav.setAttribute( "class", "d_nav" );
	nav_closed = true;


	var title_href = document.createElement( "a" );
	title_href.setAttribute( "class", "nav_title" );
	title_href.setAttribute( "id", "title_href" );
	title_href.setAttribute( "onclick", "toggle_d_nav()" );
	title_href.innerHTML = "=";
	d_nav.appendChild( title_href );

	var d = document.createElement( "div" );
	d.setAttribute( "class", "nav_d" );


	for ( var l = 0; l < hrefs.length; l = l + 1 )
	{
		var href = document.createElement( "a" );
		href.innerText = hrefs[ l ].innerText;
		if ( hrefs[ l ].hasAttribute( "href" ) )
			href.setAttribute( "href", hrefs[ l ].getAttribute( "href" ) );
		d.appendChild( href );
	}
	
	var width = 0;
	for ( var l = 0; l < hrefs.length; l = l + 1 )
	{
		var href = document.createElement( "a" );
		href.innerText = hrefs[ l ].innerText;
		if ( hrefs[ l ].hasAttribute( "href" ) )
			href.setAttribute( "href", hrefs[ l ].getAttribute( "href" ) );
		nav_div.appendChild( href );

		var inner_text = href.innerText.length;
		width = width + inner_text * 15 + 10;

		hrefs[ l ].style.display = "none";
	}
	
	console.log( width );
	nav_div.style.width = width + "px";

	d_nav.appendChild( d );
	nav.appendChild( d_nav );
	nav.appendChild( nav_div );


	var div_height = document.createElement( "div" );
	div_height.style.height = "39px";	
	nav.insertAdjacentElement( "afterend", div_height );
}

function toggle_d_nav ()
{
	var nav = document.getElementById( "d_nav" );

	var title_href = document.getElementById( "title_href" );

	if ( nav_closed )
	{
		nav.style.top = "0px"
		title_href.style.color = "rgb( 90, 90, 90 )";
		title_href.style.backgroundColor = "rgb( 234, 234, 234 )";
		nav_closed = false;
	}
	else
	{
		nav.style.top = "-340px"
		title_href.style.color = "rgb( 123, 123, 123 )";
		title_href.style.backgroundColor = "rgb( 243, 243, 243 )";
		nav_closed = true;
	}
}

nav();