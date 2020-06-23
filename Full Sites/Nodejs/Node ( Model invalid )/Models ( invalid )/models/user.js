var mongo = require( './../model.js' ).MongoClient;
var url = "mongodb://127.0.0.1:27017/mng"; 

var users = [];

mongo.connect(url, { useUnifiedTopology: true  }, async function(err, db) 
{
	if (err) throw err;
	
	var mng = db.db("mng");
 	var persons = mng.collection("persons")


	users = persons.find();

	users = await users.toArray();
});



exports.get_user_through_id = function( id )
{
	mongo.connect(url, { useUnifiedTopology: true  }, function(err, db) {
		var mng = db.db("mng");

		mng.collection("persons").findOne({ _id:id });
		db.close();
	});
}

console.log( users );

//console.log( exports.get_user_through_id( exports.users[0]._id ) );
