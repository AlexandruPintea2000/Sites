console.log ( "contrst" );

var central_div = document.createElement( "div" );
central_div.style.position = "fixed";
central_div.style.width = "96%";
central_div.style.height = "250px";
central_div.style.backgroundColor = "rgb( 123, 123, 123 )";
central_div.style.bottom = "2%";
central_div.style.right = "2%";
central_div.style.zIndex = "900000";
central_div.style.borderRadius = "5px";

var textarea = document.createElement( "textarea" );
textarea.style.width = "95%";
textarea.style.height = "34%";
textarea.style.position = "absolute";
textarea.style.left = "2%";
textarea.style.right = "2%";
textarea.style.padding = "10px 5px";
textarea.style.outline = "none";
textarea.style.borderRadius = "15px";
textarea.style.top = "5%";
textarea.setAttribute( "id", "script" );

var result = document.createElement( "textarea" );
result.style.width = "95%";
result.style.height = "34%";
result.style.position = "absolute";
result.style.left = "2%";
result.style.right = "2%";
result.style.padding = "10px 5px";
result.style.outline = "none";
result.style.borderRadius = "15px";
result.style.bottom = "5%";
textarea.setAttribute( "id", "result" );

var compile = document.createElement( "p" );
compile.style.width = "75px";
compile.style.height = "25px";
compile.style.padding = "5px 15px 5px 15px";
compile.style.backgroundColor = "rgb( 214, 214, 214 )";
compile.style.textAlign = "center";
compile.style.zIndex = "9000001";
compile.style.position = "absolute";
compile.style.top = "38%";
compile.style.borderRadius = "5px";
compile.style.left = "5%";
compile.innerHTML = "Compile";
compile.setAttribute( "id", "compile" );

central_div.appendChild( result );
central_div.appendChild( compile );
central_div.appendChild( textarea );

compile.addEventListener( "click", evaluate );

document.body.appendChild( central_div );

// var script = document.getElementById( "script" );
// var result = document.getElementById( "result" );
// var compile = document.getElementById( "compile" );

function evaluate ()
{
	result.value = eval( textarea.value );
}

