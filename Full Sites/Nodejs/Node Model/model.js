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
			console.log( collection_to_get + ": " );

			if ( collection_to_get == "persons" )
				for ( var i = 0; i < doc.length; i = i + 1 )
					console.log( "id: " + doc[i].id + ' ' + doc[i].firstname + ' ' + doc[i].lastname );	
			else
				for ( var i = 0; i < doc.length; i = i + 1 )
					console.log( "user_id: " + doc[i].user_id + ' ' + doc[i].contact );	

		});
	});	

}






exports.MongoClient.connect(url, { useUnifiedTopology: true  }, function(err, db) { // Initialze
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
			});

			var contact = { user_id:0, contact:"contact1" };
			dbo.collection( "contacts" ).insertOne( contact, function(err, res) {
				if (err) throw err;
			});

			var contact = { user_id:0, contact:"contact2" };
			dbo.collection( "contacts" ).insertOne( contact, function(err, res) {
				if (err) throw err;
			});

			var contact = { user_id:2, contact:"contact3" };
			dbo.collection( "contacts" ).insertOne( contact, function(err, res) {
				if (err) throw err;
			});

			var contact = { user_id:5, contact:"contact4" };
			dbo.collection( "contacts" ).insertOne( contact, function(err, res) {
				if (err) throw err;
//				console.log("5 contacts inserted"); 
			});

		}


		if ( ! have_persons )
		{
			dbo.createCollection("persons", function(err, res) {
				console.log("Collection 'persons' created!");
			});

			var person = { id: 0, firstname:"fname", lastname:"lname" };
			dbo.collection( "persons" ).insertOne( person, function(err, res) {
				if (err) throw err;
			});

			var person = { id: 1, firstname:"fname1", lastname:"lname1" };
			dbo.collection( "persons" ).insertOne( person, function(err, res) {
				if (err) throw err;
			});

			var person = { id: 2, firstname:"fname2", lastname:"lname2" };
			dbo.collection( "persons" ).insertOne( person, function(err, res) {
				if (err) throw err;
			});

			var person = { id: 3, firstname:"fname3", lastname:"lname3" };
			dbo.collection( "persons" ).insertOne( person, function(err, res) {
				if (err) throw err;
			});

			var person = { id: 4, firstname:"fname4", lastname:"lname4" };
			dbo.collection( "persons" ).insertOne( person, function(err, res) {
				if (err) throw err;
//				console.log("5 persons inserted"); 
				db.close();
			});

		}


		console.log( "Data was added. Please reset!" );
	
	});


});


