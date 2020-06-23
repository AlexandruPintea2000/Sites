exports.MongoClient = require('mongodb').MongoClient;

var url = "mongodb://127.0.0.1:27017/mng"; 



exports.add_to_collection = function ( collection_to_add, add )
{
	exports.MongoClient.connect(url, { useUnifiedTopology: true  }, function(err, db) {	
		
		var dbo = db.db("mng");		


		dbo.collection( collection_to_add ).insertOne( add, function(err, res) {
			if (err) throw err;
			console.log( JSON.stringify( add ) + " inserted into " + collection_to_add ); 
		});
	
	});
}

exports.get_collection = function ( collection_to_get )
{
	exports.MongoClient.connect(url, { useUnifiedTopology: true  }, function(err, db) {	
		
		var dbo = db.db("mng");		

		dbo.collection( collection_to_get ).find({}).toArray( function( err, doc ) 
		{
			
		});

		console.log( doc );	
	});	

}


// exports.add_to_collection( "persons", { firstname: "fname9", lastname: "lname" } );

exports.get_collection( "persons" );

exports.MongoClient.connect(url, { useUnifiedTopology: true  }, function(err, db) {
	var dbo = db.db("mng");


	dbo.listCollections().toArray(function(err, items){
		if (err) throw err;


		var have_contacts = false;
		var have_persons = false;

		for ( var i = 0; i < items.length; i = i + 1 )
		{
			if ( items[i].name == "contacts" )
				have_contacts = true;
			if ( items[i].name == "persons" )
				have_persons = true;
		}

		if ( have_contacts && have_persons )
		{
			db.close();
			return;
		}


		if ( ! have_contacts )
		{

			dbo.createCollection("contacts", function(err, res) {
				console.log("Collection 'contacts' created!");
			});


			var contact = { user_id:0, contact:"contact" };
			dbo.collection( "contacts" ).insertOne( contact, function(err, res) {
				if (err) throw err;
				console.log("1 contact inserted"); 
			});

		}


		if ( ! have_persons )
		{
			dbo.createCollection("persons", function(err, res) {
				console.log("Collection 'persons' created!");
			});

			var person = { firstname:"fname", lastname:"lname" };
			dbo.collection( "persons" ).insertOne( person, function(err, res) {
				if (err) throw err;
				console.log("1 person inserted"); 
				db.close();
			});
		}
	
	});


});


