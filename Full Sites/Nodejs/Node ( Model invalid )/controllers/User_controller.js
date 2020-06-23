"use strict";

var cntrl = require( './../controller.js' );
var contacts = require( './Contact_controller.js' );

exports.users = [

	{ id: 0, firstname: "fname", lastname: "lname" },
	{ id: 1, firstname: "fname1", lastname: "lname1" },
	{ id: 2, firstname: "fname2", lastname: "lname2" },
	{ id: 3, firstname: "fname3", lastname: "lname3" },
	{ id: 4, firstname: "fname4", lastname: "lname4" }

];

// var contacts = [

//	{ id: 0, user_id: 0, contact: "contact_0" }, 
//	{ id: 1, user_id: 0, contact: "contact_1" }, 
//	{ id: 2, user_id: 0, contact: "contact_2" }, 
//	{ id: 3, user_id: 4, contact: "contact_3" }, 
//	{ id: 4, user_id: 2, contact: "contact_4" }

// ];

exports.get_user_through_id = function get_user_through_id( id )
{
	for ( var i = 0; i < exports.users.length; i = i + 1 )
		if ( exports.users[i].id == id )
			return exports.users[i];
}

// function get_contacts_through_user_id( id )
// {
//	var user_contacts = null;
//	user_contacts = [];
//	var l = 0;

//	for ( var i = 0; i < contacts.length; i = i + 1 )
//		if ( contacts[i].user_id == id )
//		{
//			user_contacts[l] = contacts[i];
//			l = l + 1;
//		}

//	return user_contacts;
// }


exports.User_controller = class User_controller extends cntrl.Controller
{

	index ()
	{
		var data = JSON.stringify( exports.users );

		data = data + "; var contacts = " + JSON.stringify( contacts.contacts );

		return this.load_view( "user", "index", data );
	}

	view ( id )
	{
		var data = exports.get_user_through_id( id );

		var user_contacts = contacts.get_contacts_through_user_id( id );

		data.contacts = [];
		for ( var i = 0; i < user_contacts.length; i = i + 1 )
			data.contacts.push( user_contacts[i] );



		data = JSON.stringify( data );

		return this.load_view( "user", "view", data );
	}

	
	add ()
	{
		var data = "{ id: " + exports.users.length + " }";

		return this.load_view( "user", "add", data );		
	}

	edit ( id )
	{
		var data = exports.get_user_through_id( id );

		var data = JSON.stringify( data );

		return this.load_view( "user", "edit", data );		
	}


	dlte ( id )
	{
		var data = exports.get_user_through_id( id );

		var data = JSON.stringify( data );

		return this.load_view( "user", "delete", data );		
	}


	

}
