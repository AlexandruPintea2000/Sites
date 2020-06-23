




// Get







// Id-s

function ids ( id )
{
	var all_ids = all();
	var result = [];
	var counter = 0;

	for ( var i = 0; i < all_ids.length; i = i + 1 )
	{
		if ( ! all_ids[ i ].hasAttribute( "id" ) )
			continue;

		if ( all_ids[ i ].getAttribute( "id" ) == id ) // with attribute
		{
			result[ counter ] = all_ids[ i ];
			counter = counter + 1;
		}
	}

	return result;
}


function id ( id )
{
	return document.getElementById( id.toLowerCase() );
}

// Classes

function classes ( classes ) { return document.getElementsByClassName( classes.toLowerCase() ); }

// Name tags

function tags ( tags ) { return document.getElementsByTagName( tags.toLowerCase() ); }

// Attrs

function remove_attr ( a, attr ) { a.removeAttribute( attr ); }
function set_attr ( a, attr, val ) { a.setAttribute( attr, val ); }
function add_attr ( a, attr ) { a.setAttribute( attr, "" ); }
function has_attr ( a, attr ) { return a.hasAttribute( attr ); }

function attr ( attr ) // returns all with attr_name or attr_val = attr
{
	var all_attr = all();
	var result = [];
	var counter = 0;

	for ( var i = 0; i < all_attr.length; i = i + 1 )
	{
		if ( all_attr[ i ].hasAttribute( attr ) ) // with attribute
		{
			result[ counter ] = all_attr[ i ];
			counter = counter + 1;
			continue;
		}

		var atts = all_attr[ i ].attributes; // and with attribute values
		var attributes = [];

		for ( var l = 0; l < atts.length; l = l + 1 )
		    attributes.push(atts[l].nodeName);

		for ( var l = 0; l < attributes.length; l = l + 1 )
		    if ( all_attr[ i ].getAttribute( attributes[ l ] ) == attr )
		    {
				result[ counter ] = all_attr[ i ];
				counter = counter + 1;
				break;				    	
		    }
	}

	return result;
}

function name_attr ( attr ) // returns all with attr_name = attr
{
	var all_attr = all();
	var result = [];
	var counter = 0;

	for ( var i = 0; i < all_attr.length; i = i + 1 )
	{
		if ( all_attr[ i ].hasAttribute( attr ) ) // with attribute
		{
			result[ counter ] = all_attr[ i ];
			counter = counter + 1;
			continue;
		}
	}

	return result;	
}

function val_attr ( attr ) // returns all with attr_val = attr
{
	var all_attr = all();
	var result = [];
	var counter = 0;

	for ( var i = 0; i < all_attr.length; i = i + 1 )
	{
		var atts = all_attr[ i ].attributes; // and with attribute values
		var attributes = [];

		for ( var l = 0; l < atts.length; l = l + 1 )
		    attributes.push(atts[l].nodeName);

		for ( var l = 0; l < attributes.length; l = l + 1 )
		    if ( all_attr[ i ].getAttribute( attributes[ l ] ) == attr )
		    {
				result[ counter ] = all_attr[ i ];
				counter = counter + 1;
				break;				    	
		    }
	}

	return result;	
}

function attr_val ( attribute, val ) // returns first with attr_name and attr_valr
{
	var attrs = attr( attribute );

	for ( var i = 0; i < attrs.length; i = i + 1 )
		if ( attrs[ i ].getAttribute( attribute ) == val )
			return attrs[ i ];
}

function attrs_val ( attribute, val ) // returns all with attr_name and attr_val
{
	var all_attrs = attr( attribute );
	var attrs = [];
	var l = 0;
	for ( var i = 0; i < all_attrs.length; i = i + 1 )
		if ( all_attrs[ i ].getAttribute( attribute ) === val )
		{
			attrs[ l ] = all_attrs[ i ];
			l = l + 1;
		}

	return attrs;
}

function atribs ( a ) // returns all attr_name="attr_val" with attr_name = a as Attr
{
	var atrs = a.attributes;
	var attributes = [];

	for ( var i = 0; i < atrs.length; i = i + 1 )
	    attributes.push( atrs.item( i ) );

	return attributes;
}
function atribs_name ( a ) // returns all attr_name with attr_name = a as string
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
function atribs_val ( a ) // returns all attr_val with attr_val = a as string
{
	var attributes = atribs_name( a );
	var val = [];

	for ( var i = 0; i < attributes.length; i = i + 1 )
	    val.push( a.getAttribute( attributes[ i ] ) );

	return val;	
}

var a;
function tag (){}

function tag_name ( a ) { return a.nodeName.toLowerCase(); }

function all ( tag = null ) // in for-s / while-s recall all()
{
	if ( tag != null )
		return tags( tag.toLowerCase() );

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

function sep () { for ( var i = 0; i < 10000005; i = i + 1 ) var l = i; }

// function online () { return navigator.onLine }



// Children





function direct_children ( a, only_tag_name = null )
{
	var child;
 	child = a.children;
 	var children = [];

	for ( var i = 0; i < child.length; i = i + 1 )
	{
		if ( only_tag_name != null )
			if ( child.item( i ).tagName.toLowerCase() != only_tag_name.toLowerCase() )
				continue;

	    children.push( child.item( i ) );
	}

	return children;
}

function direct_children_before ( a, only_tag_name = null )
{
	if ( only_tag_name == null )
		return direct_children( a );

	var chldrn = direct_children( a );
	var chldrn_remaining = [];
	var i = 0;
	while ( chldrn[ i ].nodeName.toLowerCase() != only_tag_name.toLowerCase() )
		chldrn_remaining.push( chldrn[ i ] );

	return chldrn_remaining;
}

function direct_children_after ( a, only_tag_name = null )
{
	if ( only_tag_name == null )
		return direct_children( a );

	var chldrn = direct_children( a );
	var chldrn_remaining = [];
	var i = 0;
	while ( chldrn[ i ].nodeName.toLowerCase() != only_tag_name.toLowerCase() )
		i = i + 1;
	i = i + 1;

	for ( var l = i; l < chldrn.length; l = l + 1 )
		chldrn_remaining.push( chldrn[ l ] );

	return chldrn_remaining;
}

function tag_names ( a )
{
	var result = [];
	for ( var l = 0; l < a.length; l = l + 1 )
		result.push( a[ l ].nodeName.toLowerCase() );

	return result;
}

function direct_children_names ( a, only_tag_name = null )
{
	var chldrn = direct_children( a, only_tag_name );

	return tag_names( chldrn );
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

function all_children_before ( a, only_tag_name = null )
{
	if ( only_tag_name == null )
		return all_children( a );

	var chldrn = all_children( a );
	var chldrn_remaining = [];
	var i = 0;
	while ( chldrn[ i ].nodeName.toLowerCase() != only_tag_name.toLowerCase() )
		chldrn_remaining.push( chldrn[ i ] );

	return chldrn_remaining;
}

function all_children_after ( a, only_tag_name = null )
{
	if ( only_tag_name == null )
		return all_children( a );

	var chldrn = all_children( a );
	var chldrn_remaining = [];
	var i = 0;
	while ( chldrn[ i ].nodeName.toLowerCase() != only_tag_name.toLowerCase() )
		i = i + 1;
	i = i + 1;

	for ( var l = i; l < chldrn.length; l = l + 1 )
		chldrn_remaining.push( chldrn[ l ] );

	return chldrn_remaining;
}

function all_children_names ( a, only_tag_name = null )
{
	var chldrn = all_children( a, only_tag_name );

	return tag_names( chldrn );
}

// Parents

function parent ( a ) { return a.parentElement;}
function parents ( a, only_tag_name = null ) 
{
	var parnts = [];
	a = a.parentElement;
	while ( a.nodeName.toLowerCase() != "body" )
	{
		if ( a.nodeName.toLowerCase() == "html" )
			break;

		if ( only_tag_name != null )
			if ( a.nodeName.toLowerCase() != only_tag_name.toLowerCase() )
			{
				a = a.parentElement;
				continue;
			}

		parnts.push( a );
		a = a.parentElement;
	}

	return parnts;
}
function parents_before ( a, b_tag_name = null )
{
	if ( b_tag_name ==  null )
		return parents( a );

	var parnts = [];

	a = a.parentElement;
	while ( a.nodeName.toLowerCase() != b_tag_name.toLowerCase() )
		a = a.parentElement;
	a = a.parentElement;

	while ( a.nodeName.toLowerCase() != "body" )
	{
		if ( a.nodeName.toLowerCase() == "html" )
			break;

		if ( only_tag_name != null )
			if ( a.nodeName.toLowerCase() != only_tag_name.toLowerCase() )
			{
				a = a.parentElement;
				continue;
			}

		parnts.push( a );
		a = a.parentElement;
	}	

	return parnts;	
}
function parents_after ( a, b_tag_name = null )
{
	if ( b_tag_name ==  null )
		return parents( a );

	var parnts = [];
	a = a.parentElement;
	while ( a.nodeName.toLowerCase() != b_tag_name.toLowerCase() )
	{
		parnts.push( a );
		a = a.parentElement;
	}

	return parnts;	
}
function parent_name ( a ) { return a.parentElement.nodeName.toLowerCase(); }
function parents_names ( a, only_tag_name = null ) { return tag_names( parents( a, only_tag_name ) ); }
function parents_before_names ( a, b_tag_name = null ) { return tag_names( parents_before( a, b_tag_name ) ); }
function parents_after_names ( a, b_tag_name = null ) { return tag_names( parents_after( a, b_tag_name ) ); }



// Siblings



function siblings ( a, only_tag_name = null )
{
	var p = parent( a );
	var chldrn = direct_children( p );

	var result = [];
	for ( var i = 0; i < chldrn.length; i = i + 1 )
	{
		if ( only_tag_name != null )
		{
			if ( chldrn[ i ] != a && chldrn[ i ].nodeName.toLowerCase() == only_tag_name.toLowerCase() )
				result.push( chldrn[ i ] );
		}
		else
		{
			if ( chldrn[ i ] != a )
				result.push( chldrn[ i ] );
		}
	}

	return result;
}

function siblings_before ( a, only_tag_name = null )
{
	var p = parent( a );
	var chldrn = direct_children( p );

	var result = [];
	for ( var i = l; i < chldrn.length; i = i + 1 )
	{
		if ( chldrn[ i ] == a )
			break;

		if ( only_tag_name != null )
		{
			if ( chldrn[ i ].nodeName.toLowerCase() == only_tag_name.toLowerCase() )
				result.push( chldrn[ i ] );
		}
		else
			result.push( chldrn[ i ] );
	}

	return result;
}

function siblings_after ( a, only_tag_name = null )
{
	var p = parent( a );
	var chldrn = direct_children( p );

	var l = 0;
	while ( chldrn[ l ] != a )
		l = l + 1;
	l = l + 1;

	var result = [];
	for ( var i = l; i < chldrn.length; i = i + 1 )
	{
		if ( only_tag_name != null )
		{
			if ( chldrn[ i ].nodeName.toLowerCase() == only_tag_name.toLowerCase() )
				result.push( chldrn[ i ] );
		}
		else
			result.push( chldrn[ i ] );
	}

	return result;
}

function siblings_before_tag ( a, only_tag_name = null )
{
	if ( only_tag_name == null )
		return siblings_before( a );

	var p = parent( a );
	var chldrn = direct_children( p );

	var result = [];
	for ( var i = l; i < chldrn.length; i = i + 1 )
	{
		if ( chldrn[ i ].nodeName.toLowerCase() == only_tag_name.toLowerCase() )
			break;

		result.push( chldrn[ i ] );
	}

	return result;
}

function siblings_after_tag ( a, only_tag_name = null )
{
	if ( only_tag_name == null )
		return siblings_after( a );

	var p = parent( a );
	var chldrn = direct_children( p );

	var l = 0;
	while ( chldrn[ l ].nodeName.toLowerase() != only_tag_name.toLowerCase() )
		l = l + 1;
	l = l + 1;

	var result = [];
	for ( var i = l; i < chldrn.length; i = i + 1 )
		result.push( chldrn[ i ] );

	return result;
}

function siblings_names ( a, only_tag_name = null ) { return tag_names( siblings( a, only_tag_name ) ); }
function siblings_before_names ( a, only_tag_name = null ) { return tag_names( siblings_before( a, only_tag_name ) ); }
function siblings_after_names ( a, only_tag_name = null ) { return tag_names( siblings_after( a, only_tag_name ) ); }
function siblings_before_tag_names ( a, only_tag_name = null ) { return tag_names( siblings_before_tag( a, only_tag_name ) ); }
function siblings_after_tag_names ( a, only_tag_name = null ) { return tag_names( siblings_before_tag( a, only_tag_name ) ); }


function append_child ( a, b ) { a.appendChild( b ); }
function append_before( a, b, id ) { chld = direct_children( a ); a.insertBefore( b, chld[ id ] ); }
function append_after( a, b, id ) { chld = direct_children( a ); a.insertBefore( b, chld[ id + 1 ] ); }
function append_first_child ( a, b ) { chld = direct_children( a ); append_before( a, b, 0 ); }

function insert_html_after( a, b ) { a.insertAdjacentHTML( "afterend", b ); }
function insert_text_after( a, b ) { a.insertAdjacentText( "afterend", b ); }
function insert_after( a, b ) { a.insertAdjacentElement( "afterend", b ); }

function insert_html_before( a, b ) 
{
	var siblings = siblings_before( a );

	if ( siblings.length == 0 )
	{
		var p = parent( a );
		var paragraph = create( "p" );
		set_inner_html( paragraph, "paragraphs" );
		append_first_child( p, paragraph );
		insert_html_after( paragraph, b );
		paragraph.remove();
	}
	else
		siblings[ siblings.length - 1 ].insertAdjacentHTML( "afterend", b );	
}
function insert_text_before( a, b ) 
{
	var siblings = siblings_before( a );

	if ( siblings.length == 0 )
	{
		var p = parent( a );
		var paragraph = create( "p" );
		set_inner_html( paragraph, "paragraphs" );
		append_first_child( p, paragraph );
		insert_text_after( paragraph, b );
		paragraph.remove();
	}
	else
		siblings[ siblings.length - 1 ].insertAdjacentText( "afterend", b );	
}
function insert_before( a, b ) 
{
	var siblings = siblings_before( a );

	if ( siblings.length == 0 )
	{
		var p = parent( a );
		var paragraph = create( "p" );
		set_inner_html( paragraph, "paragraphs" );
		append_first_child( p, paragraph );
		insert_after( paragraph, b );
		paragraph.remove();
	}
	else
		siblings[ siblings.length - 1 ].insertAdjacentElement( "afterend", b );
}





// Set





function set ( a ) { document.write ( a ); }

function inner_text ( a ) { return a.innerText; }
function inner_html ( a ) { return a.innerHTML; }
function set_inner_text ( a, a_text ) { a.innerText = a_text; }
function set_inner_html ( a, a_html ) { a.innerHTML = a_html; }
function add_inner_text ( a, a_text ) { a.innerText = a.innerText + a_text; }
function add_inner_html ( a, a_html ) { a.innerHTML = a.innerHTML + a_html; }

function add_class ( a, b ) 
{
	if ( a.hasAttribute( "class" ) )
		a.setAttribute( "class", a.getAttribute( "class" ) + ' ' + b );
	else
		a.setAttribute( "class", b );
}
function has_class ( a, b )
{
	var all_classes = classes( b );

	for ( var l = 0; l < all_classes.length; l = l + 1 )
		if ( all_classes[ l ] == a )
			return true;

	return false;
}
function remove_class ( a, b )
{
	if ( ! has_class( a, b ) )
		return;

	var str = get_attr( a, "class" );

	str = str + ' ';
	var words = [];

	var l = 0;
	var temp = "";
	for ( var i = 0; i < str.length; i = i + 1 )
	{
		if ( str[ i ] == ' ' )
		{
			words[ l ] = temp;
			temp = "";
			l = l + 1;
		}
		else
			temp = temp + str[ i ];
	}

	var result = "";
	for ( var i = 0; i < words.length; i = i + 1 )
		if ( words[ i ] != b )
			result = result + words[ i ];

	set_attr( a, result );
}

function toggle_class ( a, b )
{
	if ( ! has_class( a, b ) )
		add_class( a, b );
	else	
		remove_class( a, b );
}



function create ( tag_name ) 
{
	var a = document.createElement( tag_name );
	var textnode = document.createTextNode( "" );
	a.appendChild( textnode );                              

	return a;
}

function add_class ( a, b )
{
	if ( a.hasAttribute( "class" ) )
		a.setAttribute( "class", a.getAttribute( "class" ) + ' ' + b );
	else
		a.setAttribute( "class", b );     
}

function add_script_file ( filepath )
{
	set ( "<script src=\"" + filepath + "\"></script>" );
}

function add_style_file ( filepath )
{
	set ( "<link rel=\"stylesheet\" href=\"" + filepath + "\" />" );
}




// Add trim




var counter_trim = 0;

function add_trim ( a, how_much )
{
	text = a.innerText;

	trimmed = false;

	if ( text.length > how_much )
	{
		initial_text = text;

		text = "";

		for ( var k = 0; k <= how_much; k = k + 1 )
			text = text + initial_text[ k ];

		text = text + "...";

		trimmed = true;
	}

	var id = false;
	if ( a.hasAttribute( "trim_id" ) )
		id = true;

	if ( trimmed )
	{
		if ( ! id )
		{
			a.setAttribute( "trim_id", counter_trim );
			a.setAttribute( "trim_count", how_much );

			a.setAttribute( "trim", a.innerText );

			a.setAttribute( "onmouseover", "hide_trim( " + counter_trim + " )" );
			a.setAttribute( "onmouseleave", "show_trim( " + counter_trim + " )" );

			counter_trim = counter_trim + 1;
		}

		a.innerText = text;
	}

}

function hide_trim ( trim_id )
{
	var trim = attr_val( "trim_id", trim_id );

	trim.innerText = trim.getAttribute( "trim" );
}

function show_trim ( trim_id )
{
	var trim_of_id = attr_val( "trim_id", trim_id );

	add_trim( trim_of_id, trim_of_id.getAttribute( "trim_count" ) );
}

function trim_tags ( tag_name = null )
{
	var all_tags = [];

	if ( tag_name == null )
		all_tags = all();
	else
		all_tags = tags( tag_name.toLowerCase() );

	for ( var i = 0; i < all_tags.length; i = i + 1 )
	{
		set_inner_html( all_tags[ i ], inner_html( all_tags[ i ] ).trim() );

		if ( tag_name == null )
			all_tags = all();
		else
			all_tags = tags( tag_name.toLowerCase() );
	}
}





// Forms





function form ()
{
	add_style_file( "scripts/inputs.css" );
	var radio = attrs_val( "type", "radio" );
	var range = attrs_val( "type", "range" );
	var check = attrs_val( "type", "checkbox" );
	var password = attrs_val( "type", "password" );


	for ( var i = 0; i < radio.length; i = i + 1 )
	{
		var div = create( "div" );
		div.setAttribute( "class", "container" );
		div.setAttribute( "type", "radio" );


		var span = create( "span" );
		span.setAttribute( "class", "checkmark" );

		var input = create( "input" );
		if ( radio[ i ].hasAttribute( "name" ) )
			input.setAttribute( "name", radio[ i ].getAttribute( "name" ) );
		input.setAttribute( "type", "radio" );

		div.appendChild( input );
		div.appendChild( span );

		insert_after( radio[ i ], div );

		radio[ i ].style.display = "none";
		radio[ i ].removeAttribute( "name" );
	}

	for ( var i = 0; i < range.length; i = i + 1 )
	{
		var count = create( "span" );
		count.setAttribute( "class", "range_count" );
		count.innerHTML = range[ i ].value;
		range[ i ].addEventListener( "click", function(){
			count.innerHTML = this.value;
		});

		insert_after( range[ i ], count );
	}


	for ( var i = 0; i < check.length; i = i + 1 )
	{
		var div = create( "div" );
		div.setAttribute( "class", "container" );
		div.setAttribute( "type", "check" );


		var span = create( "span" );
		span.setAttribute( "class", "checkmark" );

		var input = create( "input" );
		if ( check[ i ].hasAttribute( "name" ) )
			input.setAttribute( "name", check[ i ].getAttribute( "name" ) );
		input.setAttribute( "type", "checkbox" );

		div.appendChild( input );
		div.appendChild( span );

		insert_after( check[ i ], div );

		check[ i ].style.display = "none";
		check[ i ].removeAttribute( "name" );
	}





	var select = tags( "select" );
	for ( var i = 0; i < select.length; i = i + 1 )
	{
		var select_div = create( "div" );
		select_div.setAttribute( "class", "custom-select" );

		var select_remove = create( "select" );
		set_atribs( select[ i ], select_remove );
		select_remove.innerHTML = select[ i ].innerHTML;

		select_div.appendChild( select_remove );
		insert_after( select[ i ], select_div );
		select[ i ].remove();
	}

	var x, i, j, l, ll, selElmnt, a, b, c;
	// Look for any elements with the class "custom-select": 
	x = document.getElementsByClassName("custom-select");
	l = x.length;
	for (i = 0; i < l; i++) {
	  selElmnt = x[i].getElementsByTagName("select")[0];
	  ll = selElmnt.length;
	  // For each element, create a new DIV that will act as the selected item:
	  a = document.createElement("DIV");
	  a.setAttribute("class", "select-selected");
	  a.innerHTML = selElmnt.options[selElmnt.selectedIndex].innerHTML;
	  x[i].appendChild(a);
	  // For each element, create a new DIV that will contain the option list:
	  b = document.createElement("DIV");
	  b.setAttribute("class", "select-items select-hide");
	  for (j = 1; j < ll; j++) {
	    // For each option in the original select element,
	    // create a new DIV that will act as an option item:
	    c = document.createElement("DIV");
	    c.innerHTML = selElmnt.options[j].innerHTML;
	    c.addEventListener("click", function(e) {
	        // When an item is clicked, update the original select box,
	        // and the selected item:
	        var y, i, k, s, h, sl, yl;
	        s = this.parentNode.parentNode.getElementsByTagName("select")[0];
	        sl = s.length;
	        h = this.parentNode.previousSibling;
	        for (i = 0; i < sl; i++) {
	          if (s.options[i].innerHTML == this.innerHTML) {
	            s.selectedIndex = i;
	            h.innerHTML = this.innerHTML;
	            y = this.parentNode.getElementsByClassName("same-as-selected");
	            yl = y.length;
	            for (k = 0; k < yl; k++) {
	              y[k].removeAttribute("class");
	            }
	            this.setAttribute("class", "same-as-selected");
	            break;
	          }
	        }
	        h.click();
	    });
	    b.appendChild(c);
	  }
	  x[i].appendChild(b);
	  a.addEventListener("click", function(e) {
	    // When the select box is clicked, close any other select boxes,
	    // and open/close the current select box:
	    e.stopPropagation();
	    closeAllSelect(this);
	    this.nextSibling.classList.toggle("select-hide");
	    this.classList.toggle("select-arrow-active");
	  });
	}

	function closeAllSelect(elmnt) {
	  // A function that will close all select boxes in the document,
	  // except the current select box:
	  var x, y, i, xl, yl, array_num = [];
	  x = document.getElementsByClassName("select-items");
	  y = document.getElementsByClassName("select-selected");
	  xl = x.length;
	  yl = y.length;
	  for (i = 0; i < yl; i++) {
	    if (elmnt == y[i]) {
	      array_num.push(i)
	    } else {
	      y[i].classList.remove("select-arrow-active");
	    }
	  }
	  for (i = 0; i < xl; i++) {
	    if (array_num.indexOf(i)) {
	      x[i].classList.add("select-hide");
	    }
	  }
	}

	// If the user clicks anywhere outside the select box,
	// then close all selects
	document.addEventListener("click", closeAllSelect);


	for ( var i = 0; i < password.length; i = i + 1 )
	{
		password[ i ].setAttribute( "password_id", i );

		var p = create( "p" );

		var checkbox = create( "input" );
		checkbox.setAttribute( "type", "checkbox" );

		var div = create( "div" );
		div.setAttribute( "class", "container" );
		div.setAttribute( "type", "check" );

		var span = create( "span" );
		span.setAttribute( "class", "checkmark" );

		checkbox.setAttribute( "onclick", "toggle_password( " + i + " )" );

		div.appendChild( checkbox );
		div.appendChild( span );


		p.innerHTML = "<span style=\"font-weight: bold; position: relative; top: -5px; color: rgb( 123, 123, 123 );\">Password Visible</span>";
		p.appendChild( div );

		insert_after( password[ i ], p );
	}
}


function toggle_password ( password_id )
{
	var passwords = all();
	var password;

	for ( var i = 0; i < passwords.length; i = i + 1 )
	{
		if ( passwords[ i ].tagName.toLowerCase() != "input" )
			continue;

		if ( passwords[ i ].getAttribute( "password_id" ) == password_id )
		{
			password = passwords[ i ];
			break;
		}
	}

	if ( password.type == "password" )
		password.type = "text";
	else
		password.type = "password";
}


// Screen


function sr_width () { return screen.width; }
function sr_height () { return screen.height; }




// Atr





function atr ( a, atr ) { if( a.hasAttribute( atr ) ) return a.getAttribute( atr ); }
function get_atr ( a, atr ) { return atr( a, atr ); }
function set_atr ( a, atr, val = null ) { a.setAttribute( atr, val ); }
function has_atr ( a, atr ) { return a.hasAttribute( atr ); }
function set_atribs( a, b )
{
	var attributes = atribs_name( a );
	var val = atribs_val( a );

	for ( var i = 0; i < attributes.length; i = i + 1 )
		b.setAttribute( attributes[ i ], val[ i ] );
}


// Date


function date_all() { return Date(); }
function year() { return new Date().getFullYear(); }
function month() { return new Date().getMonth() + 1; }
function date() { return new Date().getDate(); }

function date_month_year() { return date() + '-' + month() + '-' + year(); };

function day() { return new Date().getDay(); }
function hour() { return new Date().getHours(); }
function minute() { return new Date().getMinutes(); }





// String





function first_in ( a, b ) { return a.indexOf( b ); }
function last_in ( a, b ) { return a.lastIndexOf( b ); }

function slice_sep ( a, sep )
{
	var str = a;
	while ( first_in( str, sep ) != -1 )
		str = str.replace( sep, ' ' );

	str = str + ' ';
	var words = [];

	var l = 0;
	var temp = "";
	for ( var i = 0; i < str.length; i = i + 1 )
	{
		if ( str[ i ] == ' ' )
		{
			words[ l ] = temp;
			temp = "";
			l = l + 1;
		}
		else
			temp = temp + str[ i ];
	}

	return words;
}

function upr ( a ) { inner_text( a, inner_text( a ).toUpperCase() ); return a; }
function lwr ( a ) { inner_text( a, inner_text( a ).toLowerCase() ); return a; }




// Arr




function add ( a, b ) { a.push( a, b ); }
function is_in ( a, b ) 
{
	for ( var l = 0; l < a.length; l = l + 1 )
		if ( a[ l ] == b )
			return true;

	return false;
}
function remove ( a, b ) 
{
	var result = [];
	for ( var l = 0; l < a.length; l = l + 1 )
		if ( a[ l ] != b )
			add( result, a[ l ] );

	a = result;
}
function sort ( a ) { a.sort(); }
function revr ( a ) { a.reverse(); }




// Style




function style_property ( a, property )
{
	var style = document.defaultView.getComputedStyle( a );
	return style.getPropertyValue( property );
}





// Num clicked





var num_clicked = 0;
var num_dblclicked = 0;

var p = tags( "html" )[0];
p.addEventListener( "click", function(){
	num_clicked = num_clicked + 1;
});

p.addEventListener( "dblclick", function(){
	num_dblclicked = num_dblclicked + 1;
});

