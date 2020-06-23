"use strict";
// Launch server with:  ./node  in this folder ( could have to remove "killall"" )

var http = require( 'http' );

var url = require('url');

var fs = require('fs');
var MongoClient = require('mongodb').MongoClient;


function set_style ( style ) { return "<style>" + style + "</style>";}



function get_person_through_id ( person_arr, person_id )
{
	for ( var i = 0; i < person_arr.length; i = i + 1 )
		if ( person_arr[i]._id == person_id )
			return person_arr[i];
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
			path = "/";
		else
			path = q.query.path;

		// access it with q.query.[name_of_thing] where [name_of_thing] is path=

		var params = path.split( '/' );
		// var controller = params[ 0 ];
		var subprogram = params[ 0 ];
		if ( subprogram == null )
			subprogram = "index";
		var parameter = params[ 1 ];
		if ( parameter == null )
			parameter = "";
		
		console.log( "Subprogram: " + subprogram + " / Parameter: " + parameter );




		
		
		if ( subprogram == "delete" )
		{
			MongoClient.connect( "mongodb://127.0.0.1:27017/mng", { useUnifiedTopology: true  }, function(err, db) {	
		
				var dbo = db.db("mng");	

				dbo.collection( "persons" ).deleteOne( { id: Number( parameter ) } );
			});


			res.write( "<script> location.replace( \"/index.js\" ); </script>" );
		}


		if ( q.query.firstname != null )
		{
				MongoClient.connect( "mongodb://127.0.0.1:27017/mng", { useUnifiedTopology: true  }, function(err, db) {	

					var dbo = db.db("mng");	

					dbo.collection( "persons" ).insertOne( { id: 9, firstname: String( q.query.firstname ), lastname: String( q.query.lastname ), contacts: String( q.query.contact ) } );
				});


				res.write( "<script> location.replace( \"/index.js\" ); </script>" );
		}









		MongoClient.connect("mongodb://127.0.0.1:27017/mng", { useUnifiedTopology: true  }, function(err, db) {	
		
			var dbo = db.db("mng");		

			dbo.collection( "persons" ).find({}).toArray( function( err, doc ) 
			{
				if (err) throw err;
	
				fs.open('nodejs', 'w', function (err, file) {
				  if (err) throw err;
				});

				fs.writeFile('nodejs', JSON.stringify( doc ), function (err) {
				  if (err) throw err;
				});

			});

		});


		var persons_file = fs.readFileSync( "nodejs", 'utf8' );;
		var persons = JSON.parse( persons_file );


		if ( persons.length != 0 )
		{
			res.write( "<h2> Persons </h2>" );
			res.write( "<div class=\"div_center\"> <table>" );
			res.write( "<tr> <th> Persons </th> <th> Contacts </th> <th class=\"options\"> Options </th> </tr>" );
			for ( var i = 0; i < persons.length; i = i + 1 )
			{
				res.write( "<tr> <td>" + persons[i].firstname + ' ' + persons[i].lastname + "</td>" );

				res.write( "<td><pre>" + String( persons[i].contacts ) + "</pre></td>" );

				res.write( "<td class=\"options\"> <a href=\"/index.js?path=delete/" + persons[i].id + "\"> Delete </a> </td>" );
				res.write( "</tr>" );
			}

			res.write( "</table> </div>" );
		}



		res.write( "<h2> Add Person </h2>" );
		res.write( "<form>" );
		res.write( "	<p> <input type=\"text\" name=\"firstname\" placeholder=\"Firstname\" required></input> </p>" );
		res.write( "	<p> <input type=\"text\" name=\"lastname\" placeholder=\"Lastname\" required></input> </p>" );
		res.write( "	<p> <textarea  name=\"contact\" rows=9  placeholder=\"Contact\" required></textarea> </p>" );
		res.write( "	<p> <input type=\"submit\"></input> </p>" );
		res.write( "</form>" );

		

		var style_file = fs.readFileSync( "./style/style.css", 'utf8' );
		res.write( set_style( style_file ) );



		res.end();
	}

});





// Server Verif.
var server_number = 3000;

server.on( 'listening', function() {
    console.log('\nServer is at: http://127.0.0.1:' + server_number + "\n" );
});

server.listen( server_number );
