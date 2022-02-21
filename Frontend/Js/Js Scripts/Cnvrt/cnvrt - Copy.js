var v = { tag:"p", attr:{ id:"paragraph", attr1:"val1", attr2:"val2" }, inner:"Paragraph 1-.-Paragraph 2-.-Paragraph 3",  chldn:[ 
		{ tag:"span", attr:{ attr1:"val1", attr2:"val2" }, inner:"Span" }, 
		{ tag:"span", attr:{ attr1:"val1", attr2:"val2" }, inner:"Span" },         
 ]};

function length ( v ) // length for Json 'array' object
{
	var count = 0;
	for ( var i in v )
		count = count + 1;
	return count;
}

function cnvrt ( v )
{
	var e = document.createElement( v.tag );
	var inner_texts = v.inner.split( "-.-" ); // for text that is before / after chldn
	e.innerText = inner_texts[0];
	for ( var i in v.attr )
		e.setAttribute( i, v.attr[i] );
	if ( v.chldn != undefined )
		for ( var i = 0; i < v.chldn.length; i = i + 1 )
        {
			e.appendChild( cnvrt( v.chldn[ i ] ) );
            if ( inner_texts[i+1] != undefined )
                e.innerHTML = e.innerHTML + inner_texts[i+1];            
        }
	return e;
}

document.body.appendChild( cnvrt( v ) );


var p = document.getElementById( "paragraph" );

function decnvrt ( p )
{
	var tag = p.tagName;
    
    // inner text that is before / after chldn 
    var inner_html = p.innerHTML;
    var inner = "";
    var in_chld = 0;
    var chld_count = 0;
    for ( var i = 0; i < inner_html.length; i = i + 1 )
    {
        if ( in_chld == 1 && inner_html[i] != '>' )
        	continue;
        if ( in_chld == 1 && inner_html[i] == '>' )
		{
        	if ( chld_count == 0 )
            {
            	chld_count = 1;
            }
            else
            {
            	in_chld = 0;
                chld_count = 0;
                inner = inner + "-.-";
                continue;
            }
        }
		if ( in_chld == 0 && inner_html[i] == '<' )
        	in_chld = 1;
        if ( in_chld == 0 )
        	inner = inner + inner_html[i];
    }
    var l = inner.length - 1;
	while ( ! ( inner[l] != '-' && inner[l] != '.' ) )
    {
    	inner = inner.slice( 0, l );
        l = l - 1; 
    }

    var attr = {};
    var l = 0;
    while ( p.attributes.item( l ) != null )
    {
        attr[ p.attributes.item( l ).name ] = p.attributes.item( l ).value;
        l = l + 1;
    }
    
    var m = {}; // Json result
    m.tag = tag;
    m.attr = attr;
    m.inner = inner;

	if ( p.children.length != 0 )
    {
      var chldn = [];
      m.chldn = [];
      for ( var i = 0; i < p.children.length; i = i + 1 )
			m.chldn.push( decnvrt( p.children[i] ) );
	}
    
    return m;
}

var v_remade = decnvrt( p );

console.log( v );
console.log( v_remade );