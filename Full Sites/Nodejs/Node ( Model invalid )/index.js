"use strict";
// Launch server with:  ./node  in this folder ( could have to remove "killall"" )

var http = require( 'http' );

var url = require('url');

var fs = require('fs');

// var mongo = require( './model.js' );

function get_date () 
{
	var date = new Date();
	return date.getDate() + ' ' + date.getMonth() + ' ' + date.getFullYear();
}

function set_style ( style ) { return "<style>" + style + "</style>";}

function replace_at (a, index, replacement) 
{
    return a.substr(0, index) + replacement + a.substr(index + replacement.length);
}


var server = http.createServer( function ( req, res )
{
	res.writeHead( 200, {'Content-Type': 'text/html'} );

	

    var realUrl = (req.connection.encrypted ? 'https': 'http') + '://' + req.headers.host + req.url;
	var q = url.parse( realUrl, true );

	if ( req.url != "/favicon.ico" )
	{

		// For: http://127.0.0.1:8000/index.js?path=user_controller/delete_user/0

		// var host = q.host; 
		// var index = q.pathname; // = /index.js
		// var params = q.search; // = ?path=user_controller/delete_user/0
		var path = "";

		if ( q.query.path == null )
			path = "user_controller/index";
		else
			path = q.query.path;

		// access it with q.query.[name_of_thing] where [name_of_thing] is path=

		var params = path.split( '/' );
		var controller = params[ 0 ];
		var subprogram = params[ 1 ];
		if ( subprogram == null )
			subprogram = "index";
		var parameter = params[ 2 ];
		if ( parameter == null )
			parameter = "";
		

		if ( controller.charAt( 0 ) >= 'a' && controller.charAt( 0 ) <= 'z'  ) // Controller to Uppercase
			controller = replace_at( controller, 0, controller.charAt( 0 ).toUpperCase() );

		console.log( "Controller: " + controller + " / Subprogram: " + subprogram + " / Parameter: " + parameter );


		var style_file = fs.readFileSync( "./style/style.css", 'utf8' );


		res.write( set_style( style_file ) );
		// res.write( '<p>Hello!</p> ' );
		// res.write( '<p>Date is: ' + get_date() + '</p>' );	
		// res.write( '<p>And time is: ' + get_time() + '</p>' );	



		// obj = new controller(); obj.subprogram( parameter );
		var command = "var cntrl = require( './controllers/" + controller + ".js' );";
		command = command + "var obj = new cntrl." + controller + "();\n";
		command = command + "var prm = \"" + parameter + "\";\n";
		command = command + "res.end ( obj." + subprogram + "( '' + prm ) );";
		eval( command );
	}

});





// Server Verif.
var server_number = 3000;

server.on( 'listening', function() {
    console.log('\nServer is at: http://127.0.0.1:' + server_number + "\n" );
});

server.listen( server_number );
