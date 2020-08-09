"use strict";
// Launch server with:  ./node  in this folder ( could have to remove "killall"" )

var http = require( 'http' );

var url = require('url');

var fs = require('fs');
var MongoClient = require('mongodb').MongoClient;


function set_style ( style ) { return "<style>" + style + "</style>";}


function get_person_id ()
{
	MongoClient.connect("mongodb://127.0.0.1:27017/mng", { useUnifiedTopology: true  }, function(err, db) {	
	
		var dbo = db.db("mng");		

		dbo.collection( "persons" ).find({}).toArray( function( err, doc ) 
		{
			if (err) throw err;

			fs.open('persons_db', 'w', function (err, file) {
			  if (err) throw err;
			});

			fs.writeFile('persons_db', JSON.stringify( doc ), function (err) {
			  if (err) throw err;
			});

		});

	});


	var persons_file = fs.readFileSync( "persons_db", 'utf8' );
	if ( persons_file == "" )
		persons_file = "{}"
	var persons = JSON.parse( persons_file );

	var id = 0;
	var ids = []
	for ( var i = 0; i < persons.length; i = i + 1 )
		ids.push( persons[ i ].id )

	ids.sort()

	for ( var i = 0; i < ids.length; i = i + 1 )
		if ( ids[ i ] == id )
			id = id + 1;
		else
			break;

	return id;
}


function get_person_through_id ( person_arr, person_id )
{
	for ( var i = 0; i < person_arr.length; i = i + 1 )
		if ( person_arr[i].id == person_id )
			return person_arr[i];
}

function is_in ( arr, a )
{

	for ( var i = 0; i < arr.length; i = i + 1 )
		if ( arr[ i ].firstname == a.firstname && arr[ i ].lastname == a.lastname )
			return i;

	return -1
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




		res.write( "<head> <title> Persons </title> </head>" );		
		








		if ( subprogram == "delete" )
		// delete
		{
			MongoClient.connect( "mongodb://127.0.0.1:27017/mng", { useUnifiedTopology: true  }, function(err, db) {	
		
				var dbo = db.db("mng");	

				dbo.collection( "persons" ).deleteOne( { id: Number( parameter ) } );
			});


			res.write( "<script> location.replace( \"/index.js\" ); </script>" );
		}


		if ( q.query.firstname != null && q.query.id == null )
		// add
		{
				MongoClient.connect( "mongodb://127.0.0.1:27017/mng", { useUnifiedTopology: true  }, function(err, db) 
				{	
					var dbo = db.db("mng");	

					var persons_file = fs.readFileSync( "persons_db", 'utf8' );
					if ( persons_file == "" )
						persons_file = "{}"
					var persons = JSON.parse( persons_file );

					var have_person = false;
					var l = -1;
					for ( var i = 0; i < persons.length; i = i + 1 )
					{
						if ( persons[ i ].firstname == String( q.query.firstname ) &&
							 persons[ i ].lastname == String( q.query.lastname ) )
						{
							have_person = true;
							l = i;
							break;
						}
					}

					if ( ! have_person )
						dbo.collection( "persons" ).insertOne( { id: get_person_id(), firstname: String( q.query.firstname ), lastname: String( q.query.lastname ), contacts: String( q.query.contact ) } );
					else
						dbo.collection( "persons" ).updateOne( { id: parseInt( persons[ l ].id ) }, { $set: { contacts: persons[ l ].contacts + '\n' + String( q.query.contact ) } });
				});

				res.write( "<script> location.replace( \"/index.js\" ); </script>" );
		}

		if ( q.query.id != null )
		// update
		{
				MongoClient.connect( "mongodb://127.0.0.1:27017/mng", { useUnifiedTopology: true  }, function(err, db)
				{	
					var dbo = db.db("mng");	

					dbo.collection( "persons" ).updateOne( { id: parseInt( q.query.id ) }, { $set: { firstname: String( q.query.firstname ), lastname: String( q.query.lastname ), contacts: String( q.query.contact ) } }, function(err, res) {
						if (err) throw err;
						console.log("1 document updated");
					});
				});


				res.write( "<script> location.replace( \"/index.js\" ); </script>" );
		}

		if ( q.query.database != null )
		{
			var loaded_database = q.query.database;
			var loaded = JSON.parse( loaded_database );

			MongoClient.connect("mongodb://127.0.0.1:27017/mng", { useUnifiedTopology: true  }, function(err, db) {	
	
				var dbo = db.db("mng");		

				dbo.collection( "persons" ).find({}).toArray( function( err, doc ) 
				{
					if (err) throw err;

					fs.open('persons_db', 'w', function (err, file) {
					  if (err) throw err;
					});

					fs.writeFile('persons_db', JSON.stringify( doc ), function (err) {
					  if (err) throw err;
					});

				});

			});

			var persons_file = fs.readFileSync( "persons_db", 'utf8' );
			if ( persons_file == "" )
				persons_file = "{}"
			var persons = JSON.parse( persons_file );

			for ( var l = 0; l < loaded.length; l = l + 1 )
			{
				var person_is_in = is_in( persons, loaded[ l ] );
				if ( person_is_in != -1 )
					persons[ person_is_in ].contacts = persons[ person_is_in ].contacts + "\n" + loaded[ l ].contacts;
				else
					persons.push( loaded[ l ] );
			}

			for ( var l = 0; l < persons.length; l = l + 1 )
				persons[ l ].id = l;
			
			MongoClient.connect( "mongodb://127.0.0.1:27017/mng", { useUnifiedTopology: true  }, function(err, db)
			{	
				var dbo = db.db("mng");	

				dbo.collection("persons").drop();
				dbo.createCollection("persons");

				dbo.collection("persons").insertMany(persons);
			});		

			res.write( "<script> location.replace( \"/index.js\" ); </script>" );
		}







		MongoClient.connect("mongodb://127.0.0.1:27017/mng", { useUnifiedTopology: true  }, function(err, db) {	
		
			var dbo = db.db("mng");		

			dbo.collection( "persons" ).find({}).toArray( function( err, doc ) 
			{
				if (err) throw err;
	
				fs.open('persons_db', 'w', function (err, file) {
				  if (err) throw err;
				});

				fs.writeFile('persons_db', JSON.stringify( doc ), function (err) {
				  if (err) throw err;
				});

			});

		});


		var persons_file = fs.readFileSync( "persons_db", 'utf8' );
		if ( persons_file == "" )
			persons_file = "{}"
		var persons = JSON.parse( persons_file );











		res.write( "<meta name=\"viewport\" content=\"width=device-width, initial-scale=1\"> </head>" );

		if ( persons.length != 0 )
		{
			res.write( "<h2> Persons </h2>" );
			if ( subprogram == "show_database" )
			{
				res.write( "<p> Save database if you want to: </p>" );						
				res.write( "<p class=\"save_database\" > <textarea rows=9  placeholder=\"Persons\" >" + persons_file + "</textarea> </p>" );			
				res.write( "<p> <a class=\"options\" href=\"/\"> Close Database </a> </p>" );				
				res.write( "<style> table { display: none !important; } </style>" );				
			}
			else
			{
				if ( subprogram == "load_database" )
				{
					res.write( "<p> Load database: </p>" );			
					res.write( "<form>" );						
					res.write( "<p class=\"save_database\" > <textarea rows=9 name=\"database\" placeholder=\"Load database\" ></textarea> </p>" );			
					res.write( "<p class=\"save_database\"> <input type=\"submit\" value=\"Load Database\" ></input> </p>" );				
					res.write( "</form>" );						
					res.write( "<p> <a class=\"options\" href=\"/\"> Return to Persons </a> </p>" );				
					res.write( "<style> table { display: none !important; } </style>" );				
				}
				else
				{
					res.write( "<a href=\"/index.js?path=show_database\" class=\"options\" > Show Database </a>" );
					res.write( "<a href=\"/index.js?path=load_database\" class=\"options\" > Load Database </a>" );
				}
			}
			res.write( "<div class=\"div_center\"> <table>" );
			res.write( "<tr> <th> Persons </th> <th> Contacts </th> <th class=\"options\"> Options </th> </tr>" );
			for ( var i = 0; i < persons.length; i = i + 1 )
			{
				res.write( "<tr> <td> <span> " + persons[i].firstname + ' ' + persons[i].lastname + " </span> </td>" );

				res.write( "<td class=\"contacts\"><pre>" + String( persons[i].contacts ) + "</pre></td>" );

				res.write( "<td class=\"options\"> <a href=\"/index.js?path=delete/" + persons[i].id + "\"> Delete </a> / " );

				res.write( "<a href=\"/index.js?path=edit/" + persons[i].id + "\"> Edit </a> </td>" );
				res.write( "</tr>" );
			}

			res.write( "</table> </div>" );
		}
		else
		{
			res.write( "<h2> Welcome to Persons! </h2>" );
			res.write( "<p> Make sure you add persons. </p>" );
		}

		res.write( "<script>" );
		res.write( "	function show_person_form()" );
		res.write( "	{" );
		res.write( "		var person_form = document.getElementById( \"person_form\" ); " );
		res.write( "		var person_form_a = document.getElementById( \"person_form_a\" ); " );
		res.write( "		if ( person_form.style.right == \"-10px\" ) " );
		res.write( "		{" );
		res.write( "			person_form.style.right = \"-490px\"; " );
		res.write( "			person_form_a.innerHTML = \"Add Person\"; " );
		res.write( "		}" );
		res.write( "		else " );
		res.write( "		{" );
		res.write( "			person_form.style.right = \"-10px\"; " );
		res.write( "			person_form_a.innerHTML = \"X\"; " );
		res.write( "		}" );
		res.write( "	}" );
		res.write( "</script>" );

		res.write( "<div id=\"person_form_a\" onclick=\"show_person_form()\"> Add Person </div>" );

		
		
		res.write( "<div id=\"person_form\">" );
		if ( subprogram == "edit" )
		{
			var person = get_person_through_id( persons, parameter );
			res.write( "<h2> Edit " + person.firstname + ' ' + person.lastname + " <div style=\"height: 10px;\"></div>" );
//			res.write( "<a href=\"/\" class=\"options\"> ( or Cancel Editing ) </a>  </h2>" );
			res.write( "<form>" );
			res.write( "	<p> <input type=\"number\" name=\"id\" placeholder=\"id\" value=\"" + person.id + "\" hidden required></input> </p>" );
			res.write( "	<p> <input type=\"text\" name=\"firstname\" placeholder=\"Firstname\" value=\"" + person.firstname + "\" required></input> </p>" );
			res.write( "	<p> <input type=\"text\" name=\"lastname\" placeholder=\"Lastname\" value=\"" + person.lastname + "\" required></input> </p>" );
			res.write( "	<p> <textarea  name=\"contact\" rows=9  placeholder=\"Contact\" required>" + person.contacts + "</textarea> </p>" );
			res.write( "	<p> <input type=\"submit\"></input> </p>" );
			res.write( "</form>" );

			res.write( "<script>" );
			res.write( "	var person_form = document.getElementById( \"person_form\" ); " );
			res.write( "	var person_form_a = document.getElementById( \"person_form_a\" ); " );
			res.write( "	person_form.style.right = \"-10px\"; " );
			res.write( "	person_form_a.innerHTML = \"X\"; " );
			res.write( "	person_form_a.setAttribute( \"onclick\", \"location.replace( '/' )\" ); " );
			res.write( "</script>" );

		}
		else
		{
			res.write( "<h2> Add Person </h2>" );
			res.write( "<form>" );
			res.write( "	<p> <input type=\"text\" name=\"firstname\" placeholder=\"Firstname\" required></input> </p>" );
			res.write( "	<p> <input type=\"text\" name=\"lastname\" placeholder=\"Lastname\" required></input> </p>" );
			res.write( "	<p> <textarea  name=\"contact\" rows=9  placeholder=\"Contact\" required></textarea> </p>" );
			res.write( "	<p> <input type=\"submit\"></input> </p>" );
			res.write( "</form>" );
		}
		res.write( "</div>" );
		

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
