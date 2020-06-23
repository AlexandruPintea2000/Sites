"use strict";

MongoClient = require('../mongodb').MongoClient;
var url = "mongodb://127.0.0.1:27017/mng"; 

exports.get_users = function()
{
	MongoClient.connect(url, { useUnifiedTopology: true  }, function(err, db) {
		var mng = db.db("mng");

		mng.collection("users").find({}).toArray(function(err, result) {
		if (err) throw err;

		console.log(result);
		db.close();
	});
  });
}

MongoClient.connect(url, { useUnifiedTopology: true  }, function(err, db) {
	var dbo = db.db("mng");
	dbo.createCollection("contacts", function(err, res) {
		if (err) throw err;
		console.log("Collection 'contacts' created!");
	});



	var contact = { id:0, user_id:0, contact:"contact" };
	dbo.collection( "contacts" ).insertOne( contact, function(err, res) {
		if (err) throw err;
		console.log("1 contact inserted"); 
	});


	dbo.createCollection("persons", function(err, res) {
		if (err) throw err;
		console.log("Collection 'persons' created!");
	});

	var person = { id:0, firstname:"fname", lastname:"lname" };
	dbo.collection( "persons" ).insertOne( contact, function(err, res) {
		if (err) throw err;
		console.log("1 person inserted"); 
		db.close();
	});

});


