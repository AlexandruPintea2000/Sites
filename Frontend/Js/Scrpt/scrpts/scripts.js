//alert( "scripts" );

// function tags ( tag ) { return document.getElementsByTagName( tag ); }

function set ( a )
{
	document.write( a );
}

set ( "<link rel=\"stylesheet\" href=\"style.css\" />" );

function all ()
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

 	return children;
}
function id ( id )
{
	return document.getElementById( id.toLowerCase() );
}
function all_children ( a, only_tag_name = null )
{
	var child;
 	child = a.children;
 	var children = [];

	for ( var i = 0; i < child.length; i = i + 1 )
	    children.push( child.item( i ) );

 	for ( var i = 0; i < children.length; i = i + 1 )
 	{
 		var chld = children[ i ].children;

 		if ( chld.length != 0 )
 			for ( var l = 0; l < chld.length; l = l + 1 )
 			{
				if ( only_tag_name != null )
					if ( child.item( i ).tagName.toLowerCase() != only_tag_name.toLowerCase() )
						continue;

 				children.push( chld[ l ] );
 			}
 	}

 	return children;
}
function create ( tag_name ) 
{
	var a = document.createElement( tag_name );
	var textnode = document.createTextNode( "" );
	a.appendChild( textnode );                              

	return a;
}
function insert_after( a, b ) { a.insertAdjacentElement( "afterend", b ); };
function insert_text_after( a, b ) { a.insertAdjacentText( "afterend", b ); };
function direct_children ( a )
{
	var child;
 	child = a.children;
 	var children = [];

	for ( var i = 0; i < child.length; i = i + 1 )
	    children.push( child.item( i ) );

	return children;
}
function append_before( a, b, id ) { chld = direct_children( a ); a.insertBefore( b, chld[ id ] ); }
function append_first_child ( a, b ) { chld = direct_children( a ); append_before( a, b, 0 ); }
function atribs_name ( a ) // returns attr_name as string
{
	var atrs = a.attributes;
	var attributes = [];

	for ( var i = 0; i < atrs.length; i = i + 1 )
	{
		var attr_name_val = atrs.item( i ).name;
		var attr_name = "";
		for ( var l = 0; l < attr_name_val.length; l = l + 1 )
		{
			if ( attr_name_val.charAt( l ) == '=' )
				break;

			attr_name = attr_name + attr_name_val.charAt( l );
		}

	    attributes.push( attr_name );
	}

	return attributes;
}
function atribs_val ( a ) // returns attr_val as string
{
	var attributes = atribs_name( a );
	var val = [];

	for ( var i = 0; i < attributes.length; i = i + 1 )
	    val.push( a.getAttribute( attributes[ i ] ) );

	return val;	
}
function set_atribs( a, b )
{
	var attributes = atribs_name( a );
	var val = atribs_val( a );

	for ( var i = 0; i < attributes.length; i = i + 1 )
		b.setAttribute( attributes[ i ], val[ i ] );
}

function allowDrop(ev) {
  ev.preventDefault();
}

function drag(ev) {
  ev.dataTransfer.setData("text", ev.target.id);
}

function drop(ev) {
  ev.preventDefault();
  var data = ev.dataTransfer.getData("text");
  ev.target.appendChild(document.getElementById(data));
}












var tags = [ "eb", "du", "ub", "title", "tag" ];
var tag_classes = [ "eb", "du", "ub", "title", "tag" ];

function is_in_tags ( tag )
{
	for ( var i = 0; i < tags.length; i = i + 1 )
		if ( tags[ i ] == tag )
			return true;

	return false;
}

function add_tag ( tag, tag_class )
{
	if ( is_in_tags( tag ) )
		return;

	tags.push( tag.toLowerCase() );
	tag_classes.push( tag_class );
}

var dism_id = 0;
var uses_id = 0;
var leave_id = 0;
var collapse_id = 0;
var leave_shown = [];
var drag_id = 0;
var drop_id = 0;

function set_tags ()
{
	var name_tags = all();

	for ( var i = 0; i < name_tags.length; i = i + 1 )
	{
		for ( var l = 0; l < tags.length; l = l + 1 )
			if ( name_tags[ i ].tagName.toLowerCase() == tags[ l ] )
			{
				var span = create( "span" );				
				set_atribs( name_tags[ i ], span );

				if ( name_tags[ i ].hasAttribute( "class" ) )
					span.setAttribute( "class", name_tags[ i ].getAttribute( "class" ) + ' ' + tag_classes[ l ] );
				else
					span.setAttribute( "class", tag_classes[ l ] );

				span.innerHTML = name_tags[ i ].innerHTML;

	
				insert_after( name_tags[ i ], span );
				name_tags[ i ].remove();
			i = 0;
			}
		









		if ( name_tags[ i ].tagName.toLowerCase() == "prlx" )
		{
			var div = create( "div" );
			set_atribs( name_tags[ i ], div );

			if ( name_tags[ i ].hasAttribute( "class" ) )
				div.setAttribute( "class", name_tags[ i ].getAttribute( "class" ) + " parallax" );
			else
				div.setAttribute( "class", "parallax" );
	
			div.innerHTML = name_tags[ i ].innerHTML;


			if ( name_tags[ i ].hasAttribute( "img" ) && name_tags[ i ].hasAttribute( "height" ) )
			{
				div.style.backgroundImage = name_tags[ i ].getAttribute( "img" );
				div.style.height = name_tags[ i ].getAttribute( "height" );
			}
			if ( name_tags[ i ].hasAttribute( "img" ) && ! name_tags[ i ].hasAttribute( "height" ) )
				div.style.backgroundImage = name_tags[ i ].getAttribute( "img" );
			if ( ! name_tags[ i ].hasAttribute( "img" ) && name_tags[ i ].hasAttribute( "height" ) )
				div.style.height = name_tags[ i ].getAttribute( "height" );

			if ( name_tags[ i ].hasAttribute( "img" ) )
				div.removeAttribute( "img" );

			if ( name_tags[ i ].hasAttribute( "height" ) )
				div.removeAttribute( "height" );


			insert_after( name_tags[ i ], div );
			name_tags[ i ].remove();
			i = 0;
		}

		if ( name_tags[ i ].tagName.toLowerCase() == "content" )
		{
			var div = create( "div" );
			set_atribs( name_tags[ i ], div );

			if ( name_tags[ i ].hasAttribute( "class" ) )
				div.setAttribute( "class", name_tags[ i ].getAttribute( "class" ) + " content" );
			else
				div.setAttribute( "class", "content" );

			div.innerHTML = name_tags[ i ].innerHTML;


			insert_after( name_tags[ i ], div );
			name_tags[ i ].remove();
			i = 0;
		}

		if ( name_tags[ i ].tagName.toLowerCase() == "contact" )
		{
			var div = create( "div" );
			set_atribs( name_tags[ i ], div );

			if ( name_tags[ i ].hasAttribute( "class" ) )
				div.setAttribute( "class", name_tags[ i ].getAttribute( "class" ) + " content" );
			else
				div.setAttribute( "class", "content" );

			div.setAttribute( "id", "contact" );
			div.innerHTML = name_tags[ i ].innerHTML;

			var have_title = false;
			var children = direct_children( name_tags[ i ] );
			for ( var l = 0; l < children.length; l = l + 1 )
			{
				if ( children[ l ].tagName.toLowerCase() == "h1" ) { have_title = true; break; }
				if ( children[ l ].tagName.toLowerCase() == "h2" ) { have_title = true; break; }
				if ( children[ l ].tagName.toLowerCase() == "h3" ) { have_title = true; break; }
				if ( children[ l ].tagName.toLowerCase() == "h4" ) { have_title = true; break; }
				if ( children[ l ].tagName.toLowerCase() == "h5" ) { have_title = true; break; }
				if ( children[ l ].tagName.toLowerCase() == "h6" ) { have_title = true; break; }
			}

			if ( ! have_title )
			{
				var h2 = create( "h2" );
				h2.innerHTML = "Contact";
				append_first_child( div, h2 );
			}


			insert_after( name_tags[ i ], div );
			name_tags[ i ].remove();
			i = 0;
		}

		if ( name_tags[ i ].tagName.toLowerCase() == "about" )
		{
			var div = create( "div" );
			set_atribs( name_tags[ i ], div );


			if ( name_tags[ i ].hasAttribute( "class" ) )
				div.setAttribute( "class", name_tags[ i ].getAttribute( "class" ) + " content" );
			else
				div.setAttribute( "class", "content" );

			div.setAttribute( "id", "about" );
			div.innerHTML = name_tags[ i ].innerHTML;


			var have_title = false;
			var children = direct_children( name_tags[ i ] );
			for ( var l = 0; l < children.length; l = l + 1 )
			{
				if ( children[ l ].tagName.toLowerCase() == "h1" ) { have_title = true; break; }
				if ( children[ l ].tagName.toLowerCase() == "h2" ) { have_title = true; break; }
				if ( children[ l ].tagName.toLowerCase() == "h3" ) { have_title = true; break; }
				if ( children[ l ].tagName.toLowerCase() == "h4" ) { have_title = true; break; }
				if ( children[ l ].tagName.toLowerCase() == "h5" ) { have_title = true; break; }
				if ( children[ l ].tagName.toLowerCase() == "h6" ) { have_title = true; break; }
			}

			if ( ! have_title )
			{
				var h2 = create( "h2" );
				h2.innerHTML = "About";
				append_first_child( div, h2 );
			}



			insert_after( name_tags[ i ], div );
			name_tags[ i ].remove();
			i = 0;
		}

		if ( name_tags[ i ].tagName.toLowerCase() == "persp" )
		{
			var div = create( "div" );
			set_atribs( name_tags[ i ], div );


			div.style.perspective = "100px";
			div.style.position = "relative";
			div.style.width = "70%";
			div.style.marginLeft = "15%";

			var have_title = false;
			var children = direct_children( name_tags[ i ] );
			for ( var l = 0; l < children.length; l = l + 1 )
			{
				children[ l ].style.transformStyle = "preserve-3d";
				children[ l ].style.transform = "rotateX( 45deg )";
				children[ l ].style.textAlign = "center";
			}

			var children = all_children( name_tags[ i ] );
			for ( var l = 0; l < children.length; l = l + 1 )
				children[ l ].style.textAlign = "center";

			children = direct_children( name_tags[ i ] );
			for ( var l = 0; l < children.length; l = l + 1 )
				div.appendChild( children[ l ] );

			insert_after( name_tags[ i ], div );
			name_tags[ i ].remove();
			i = 0;
		}


		if ( name_tags[ i ].tagName.toLowerCase() == "cn" )
		{
			var div = create( "div" );
			set_atribs( name_tags[ i ], div );

			div.style.position = "relative";
			div.style.minHeight = "150px";

			var cn_div = create( "div" );

			cn_div.style.position = "absolute";
			cn_div.style.top = "50%";
			cn_div.style.left = "50%";
			cn_div.style.transform = "translate(-50%, -50%)";
			cn_div.style.textAlign = "center";

			cn_div.innerHTML = name_tags[ i ].innerHTML;
			div.appendChild( cn_div );

			insert_after( name_tags[ i ], div );
			name_tags[ i ].remove();
			i = 0;
		}


		if ( name_tags[ i ].tagName.toLowerCase() == "dpdn" )
		{
			var div = create( "div" );
			set_atribs( name_tags[ i ], div );

			if ( name_tags[ i ].hasAttribute( "class" ) )
				div.setAttribute( "class", name_tags[ i ].getAttribute( "class" ) + " dpdn" );
			else
				div.setAttribute( "class", "dpdn" );

			var p = create( "b" );
			p.innerHTML = name_tags[ i ].getAttribute( "dpdn_title" );

			div.style.display = "inline";
			div.style.cursor = "default";	

			var dpdn_div = create( "span" );

			children = direct_children( name_tags[ i ] );

			for ( var l = 0; l < children.length; l = l + 1 )
			{
				children[ l ].style.display = "block";
				children[ l ].style.padding = "5px";
				children[ l ].style.cursor = "default";	
				children[ l ].style.color = "rgb( 253, 253, 253 )";
			}

			children = direct_children( name_tags[ i ] );
			for ( var l = 0; l < children.length; l = l + 1 )
				dpdn_div.appendChild( children[ l ] );



			div.appendChild( p );
			div.appendChild( dpdn_div );

			insert_after( name_tags[ i ], div );
			name_tags[ i ].remove();
			i = 0;
		}


		if ( name_tags[ i ].tagName.toLowerCase() == "title_img" )
		{
			var div = create( "div" );
			set_atribs( name_tags[ i ], div );

			if ( name_tags[ i ].hasAttribute( "img" ) )
			{
				var img = create( "img" );
				img.setAttribute( "src", name_tags[ i ].getAttribute( "img" ) );
				div.appendChild( img );
			}

			var p = create( "p" );
			p.innerText = name_tags[ i ].innerText;
			div.appendChild( p );

			if ( name_tags[ i ].hasAttribute( "class" ) )
				div.setAttribute( "class", name_tags[ i ].getAttribute( "class" ) + " title_img" );
			else
				div.setAttribute( "class", "title_img" );


			insert_after( name_tags[ i ], div );
			name_tags[ i ].remove();
			i = 0;
		}

		if ( name_tags[ i ].tagName.toLowerCase() == "border_img" )
		{
			var img = create( "img" );
			set_atribs( name_tags[ i ], img );

			if ( name_tags[ i ].hasAttribute( "img" ) )
				img.setAttribute( "src", name_tags[ i ].getAttribute( "img" ) );


			if ( name_tags[ i ].hasAttribute( "class" ) )
				img.setAttribute( "class", name_tags[ i ].getAttribute( "class" ) + " border_img" );
			else
				img.setAttribute( "class", "border_img" );

			insert_after( name_tags[ i ], img );			
			name_tags[ i ].remove();
			i = 0;
		}	

		if ( name_tags[ i ].tagName.toLowerCase() == "border_a" )
		{
			var a = create( "a" );
			set_atribs( name_tags[ i ], a );

			a.innerHTML = name_tags[ i ].innerHTML;

			if ( name_tags[ i ].hasAttribute( "class" ) )
				a.setAttribute( "class", name_tags[ i ].getAttribute( "class" ) + " border_a" );
			else
				a.setAttribute( "class", "border_a" );

			insert_after( name_tags[ i ], a );
			name_tags[ i ].remove();
			i = 0;
		}


		if ( name_tags[ i ].tagName.toLowerCase() == "script" && name_tags[ i ].hasAttribute( "visible" ) )
		{
			var pre = create( "pre" );
			pre.setAttribute( "class", "visible_script" );

			pre.innerText = name_tags[ i ].innerHTML;

			var chldrn = direct_children( pre );

			for ( var l = 0; l < chldrn.length; l = l + 1 )
				chldrn[ l ].remove();

			var inner_text = pre.innerText;
			var inner = "";

			for ( var l = 0; l < inner_text.length; l = l + 1 )
			{
				if ( inner_text[ l ] != '	' )
					inner = inner + inner_text[ l ];
				if ( inner_text[ l ] == ';' )
					inner = inner + "\n";
			}

			pre.innerText = inner;

			insert_after( name_tags[ i ], pre );
			name_tags[ i ].removeAttribute( "visible" )
		}

		if ( name_tags[ i ].tagName.toLowerCase() == "dism" )
		{
			var div = create( "div" );
			set_atribs( name_tags[ i ], div );

			div.setAttribute( "dism_id", dism_id );

			if ( name_tags[ i ].hasAttribute( "dism_color" ) )
				div.setAttribute( "dism-" + name_tags[ i ].getAttribute( "dism_color" ), "" );


			var h2 = create( "h2" );
			var span = create( "span" );
			span.innerHTML = "X";
			span.setAttribute( "onclick", "dismiss_dism( " + dism_id + " )" );

			if ( name_tags[ i ].hasAttribute( "dism_title" ) )
				h2.innerText = name_tags[ i ].getAttribute( "dism_title" );
			h2.appendChild( span );


			div.appendChild( h2 );
	
			dism_id = dism_id + 1;
			
			var dism_div = create( "div" );
			dism_div.innerText = name_tags[ i ].innerText;
			div.appendChild( dism_div );

			if ( name_tags[ i ].hasAttribute( "class" ) )
				div.setAttribute( "class", name_tags[ i ].getAttribute( "class" ) + " dism" );
			else
				div.setAttribute( "class", "dism" );


			insert_after( name_tags[ i ], div );
			name_tags[ i ].remove();
			i = 0;
		}

		if ( name_tags[ i ].tagName.toLowerCase() == "uses" )
		{
			var div = create( "div" );
			set_atribs( name_tags[ i ], div );

			div.setAttribute( "uses_id", uses_id );

			if ( name_tags[ i ].hasAttribute( "uses_color" ) )

			div.setAttribute( "uses-" + name_tags[ i ].getAttribute( "uses_color" ), "" );

			var h2 = create( "h2" );
			var span = create( "span" );
			span.innerHTML = "X";
			span.setAttribute( "onclick", "dismiss_uses( " + uses_id + " )" );
			h2.appendChild( span );

			div.appendChild( h2 );

			uses_id = uses_id + 1;
			
			var uses_div = create( "div" );
			uses_div.innerText = name_tags[ i ].innerText;
			div.appendChild( uses_div );

			if ( name_tags[ i ].hasAttribute( "class" ) )
				div.setAttribute( "class", name_tags[ i ].getAttribute( "class" ) + " uses" );
			else
				div.setAttribute( "class", "uses" );


			insert_after( name_tags[ i ], div );
			name_tags[ i ].remove();
			i = 0;
		}

		if ( name_tags[ i ].tagName.toLowerCase() == "pref" )
		{
			var div = create( "div" );
			set_atribs( name_tags[ i ], div );

			if ( name_tags[ i ].hasAttribute( "pref_color" ) )
				div.setAttribute( "pref-" + name_tags[ i ].getAttribute( "pref_color" ), "" );


			if ( name_tags[ i ].hasAttribute( "class" ) )
				div.setAttribute( "class", name_tags[ i ].getAttribute( "class" ) + " pref" );
			else
				div.setAttribute( "class", "pref" );

			div.innerHTML = name_tags[ i ].innerHTML;

			insert_after( name_tags[ i ], div );
			name_tags[ i ].remove();
			i = 0;
		}	


		if ( name_tags[ i ].tagName.toLowerCase() == "leave" )
		{
			var div = create( "div" );
			set_atribs( name_tags[ i ], div );

			div.setAttribute( "leave_id", leave_id );

			if ( name_tags[ i ].hasAttribute( "leave_color" ) )
				div.setAttribute( "leave-" + name_tags[ i ].getAttribute( "leave_color" ), "" );

			div.style.zIndex = "9300";


			var h2 = create( "h2" );
			var span = create( "span" );
			span.innerHTML = "X";
			span.setAttribute( "onclick", "dismiss_leave( " + leave_id + " )" );

			if ( name_tags[ i ].hasAttribute( "leave_title" ) )
				h2.innerText = name_tags[ i ].getAttribute( "leave_title" );
			h2.appendChild( span );


			div.appendChild( h2 );
	
			
			var leave_div = create( "div" );
			leave_div.innerText = name_tags[ i ].innerText;
			div.appendChild( leave_div );

			if ( name_tags[ i ].hasAttribute( "class" ) )
				div.setAttribute( "class", name_tags[ i ].getAttribute( "class" ) + " leave" );
			else
				div.setAttribute( "class", "leave" );


			if ( name_tags[ i ].hasAttribute( "on_leave" ) )
			{
				var htmls = document.getElementsByTagName( "html" );
				var html = htmls[0];


				var leave_title = name_tags[ i ].getAttribute( "leave_title" );
				var leave_set = name_tags[ i ].innerHTML;
				var color = name_tags[ i ].getAttribute( "leave_color" );
				html.addEventListener( "click", function(){
					if ( ! leave_shown[ leave_id ] )
					{
						set_leave( leave_title, leave_set, color );
						leave_shown[ leave_id ] = true;
					}
				});
			}
			else
				html.appendChild( leave_div );


			leave_id = leave_id + 1;

			name_tags[ i ].remove();
			i = 0;
		}

		if ( name_tags[ i ].tagName.toLowerCase() == "columns" )
		{
			var div = create( "div" );
			set_atribs( name_tags[ i ], div );

			if ( name_tags[ i ].hasAttribute( "class" ) )
				div.setAttribute( "class", name_tags[ i ].getAttribute( "class" ) + " columns" );
			else
				div.setAttribute( "class", "columns" );



			children = direct_children( name_tags[ i ] );

			for ( var l = 0; l < children.length; l = l + 1 )
			{
				if ( children[ l ].hasAttribute( "class" ) )
					children[ l ].setAttribute( "class", children[ l ].getAttribute( "class" ) + " column" );
				else
					children[ l ].setAttribute( "class", "column" );


				children[ l ].style.width = 100 / children.length + "%";
				children[ l ].style.margin = "0px";
			}
			for ( var l = 0; l < children.length; l = l + 1 )
				div.appendChild( children[ l ] );



			insert_after( name_tags[ i ], div );
			name_tags[ i ].remove();
			i = 0;
		}


		if ( name_tags[ i ].tagName.toLowerCase() == "collapse" )
		{
			var div = create( "div" );
			set_atribs( name_tags[ i ], div );

			if ( name_tags[ i ].hasAttribute( "class" ) )
				div.setAttribute( "class", name_tags[ i ].getAttribute( "class" ) + " collapse" );
			else
				div.setAttribute( "class", "collapse" );


			div.setAttribute( "id", "collapse_" + collapse_id );

			div.setAttribute( "onclick", "collapse( " + collapse_id + " )" );


			if ( name_tags[ i ].hasAttribute( "collapse_title" ) )
				div.innerHTML = name_tags[ i ].getAttribute( "collapse_title" );


			var collapsed_div = create( "div" );
			collapsed_div.setAttribute( "id", "collapsed_" + collapse_id );
			collapsed_div.style.height = "0px";
			collapsed_div.style.padding = "0px 21px";		
			collapsed_div.setAttribute( "class", "collapsed" );
			collapsed_div.innerHTML = name_tags[ i ].innerHTML;

			collapse_id = collapse_id + 1;

			insert_after( name_tags[ i ], div );
			insert_after( div, collapsed_div );
			name_tags[ i ].remove();
			i = 0;
		}

		if ( name_tags[ i ].hasAttribute( "right-num" ) )
		{
			var div = create( "div" );
			div.setAttribute( "class", "right-num" );


			if ( name_tags[ i ].getAttribute( "right-num" ) < 90 )
				div.innerHTML = name_tags[ i ].getAttribute( "right-num" );
			else
			{
				div.innerHTML = "Loads";
				div.style.borderRadius = "5px";				
			}


			name_tags[ i ].style.position = "relative";
			name_tags[ i ].appendChild( div );
			name_tags[ i ].removeAttribute( "right-num" );
		}



		if ( name_tags[ i ].tagName.toLowerCase() == "drag" )
		{
			var div = create( "div" );
			set_atribs( name_tags[ i ], div );


			if ( name_tags[ i ].hasAttribute( "class" ) )
				div.setAttribute( "class", name_tags[ i ].getAttribute( "class" ) + " drag" );
			else
				div.setAttribute( "class", "drag" );

			div.setAttribute( "id", "drag_" + drag_id );
			drag_id = drag_id +  1


			div.setAttribute( "draggable", "true" );
			div.setAttribute( "ondragstart", "drag(event)" );

			div.innerHTML = name_tags[ i ].innerHTML;

			insert_after( name_tags[ i ], div );
			name_tags[ i ].remove();
			i = 0;
		}

		if ( name_tags[ i ].tagName.toLowerCase() == "drop" )
		{
			var div = create( "div" );
			set_atribs( name_tags[ i ], div );


			if ( name_tags[ i ].hasAttribute( "class" ) )
				div.setAttribute( "class", name_tags[ i ].getAttribute( "class" ) + " drop" );
			else
				div.setAttribute( "class", "drop" );

			div.setAttribute( "id", "drop_" + drop_id );
			drop_id = drop_id +  1

			div.setAttribute( "ondrop", "drop(event)" );
			div.setAttribute( "ondragover", "allowDrop(event)" );


			insert_after( name_tags[ i ], div );
			name_tags[ i ].remove();
			i = 0;
		}



		name_tags = all();

	}
}




function set ( a ) { document.write ( a ); }

function classes ( classes ) { return document.getElementsByClassName( classes.toLowerCase() ); }




function set_dism ( dism_title, dism_set, color = null )
{
	var div = create( "div" );
	div.setAttribute( "class", "dism" );
	div.setAttribute( "dism_id", dism_id );

	if ( color != null )
	{
		var dism_color = "dism-" + color;
		div.setAttribute( dism_color, "" );
	}

	var h2 = create( "h2" );
	var span = create( "span" );
	span.innerHTML = "X";
	span.setAttribute( "onclick", "dismiss_dism( " + dism_id + " )" );
	dism_id = dism_id + 1;

	h2.innerText = dism_title;
	h2.appendChild( span );

	div.appendChild( h2 );

	var dism_div = create( "div" );
	dism_div.innerText = dism_set;
	div.appendChild( dism_div );

	var htmls = document.getElementsByTagName( "html" );
	var html = htmls[0];

	html.appendChild( div );
}


function dismiss_dism( dism_id )
{
	var disms = classes( "dism" );
	var dism;

	for ( var i = 0; i < disms.length; i = i + 1 )
		if ( disms[ i ].getAttribute( "dism_id" ) == dism_id )
		{
			dism = disms[ i ];
			break;
		}

	dism.remove();
}

function set_uses ( uses_set, color = null )
{
	var div = create( "div" );
	div.setAttribute( "class", "uses" );
	div.setAttribute( "uses_id", uses_id );

	if ( color != null )
	{
		var uses_color = "uses-" + color;
		div.setAttribute( uses_color, "" );
	}

	var h2 = create( "h2" );
	var span = create( "span" );
	span.innerHTML = "X";
	span.setAttribute( "onclick", "dismiss_uses( " + uses_id + " )" );
	uses_id = uses_id + 1;

	h2.appendChild( span );

	div.appendChild( h2 );

	var uses_div = create( "div" );
	uses_div.innerText = uses_set;
	div.appendChild( uses_div );

	var htmls = document.getElementsByTagName( "html" );
	var html = htmls[0];

	html.appendChild( div );
}


function dismiss_uses( uses_id )
{
	var uses_classes = classes( "uses" );
	var uses;
	for ( var i = 0; i < uses_classes.length; i = i + 1 )
		if ( uses_classes[ i ].getAttribute( "uses_id" ) == uses_id )
		{
			uses = uses_classes[ i ];
			break;
		}

	uses.remove();
}



function set_leave ( leave_title, leave_set, color = null )
{
	var div = create( "div" );
	div.setAttribute( "class", "leave" );
	div.setAttribute( "leave_id", leave_id );

	if ( color != null )
	{
		var leave_color = "leave-" + color;
		div.setAttribute( leave_color, "" );
	}

	var h2 = create( "h2" );
	var span = create( "span" );
	span.innerHTML = "X";
	span.setAttribute( "onclick", "dismiss_leave( " + leave_id + " )" );

	h2.innerText = leave_title;
	h2.appendChild( span );

	div.appendChild( h2 );

	var leave_div = create( "div" );
	leave_div.innerText = leave_set;
	div.appendChild( leave_div );

	var htmls = document.getElementsByTagName( "html" );
	var html = htmls[0];
	leave_id = leave_id + 1;



	// Shown

	// if ( ! shown )
	// {
	// 	div.style.zIndex = "9300";

	// 	leave_shown[ leave_id ] = false;
	// 	html.addEventListener( "click", function () {
	// 		if ( ! leave_shown[ leave_id ] )
	// 		{
	// 			set_leave( leave_title, leave_set, color, true );
	// 			leave_shown[ leave_id ] = true;
	// 		}
	// 	});
	// }
	// else


	html.appendChild( div );
}


function dismiss_leave( leave_id )
{
	var leaves = classes( "leave" );
	var leave;

	for ( var i = 0; i < leaves.length; i = i + 1 )
		if ( leaves[ i ].getAttribute( "leave_id" ) == leave_id )
		{
			leave = leaves[ i ];
			break;
		}

	leave.remove();
}


function collapse ( collapsed_id )
{
	var collapsed = id( "collapsed_" + collapsed_id );
	var clps = id( "collapse_" + collapsed_id );

	if ( collapsed.style.height == "0px" )
	{
		collapsed.style.height = "21px";
		collapsed.style.padding = "5px 21px";
		clps.style.backgroundColor = "rgb( 204, 204, 204 )";
	}
	else
	{
		collapsed.style.height = "0px"
		collapsed.style.padding = "0px 21px";		
		clps.style.backgroundColor = "rgb( 234, 234, 234 )";
	}
}



