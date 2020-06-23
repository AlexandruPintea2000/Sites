"use strict";

var cntrl = require( './../controller.js' );
var users = require( './User_controller.js' );

exports.contacts = [

	{ id: 0, user_id: 0, contact: "contact_0" }, 
	{ id: 1, user_id: 0, contact: "contact_1" }, 
	{ id: 2, user_id: 0, contact: "contact_2" }, 
	{ id: 3, user_id: 4, contact: "contact_3" }, 
	{ id: 4, user_id: 2, contact: "contact_4" }

];
exports.get_contacts_through_user_id = function get_contacts_through_user_id( id )
{
	var user_contacts = [];
	var l = 0;

	for ( var i = 0; i < exports.contacts.length; i = i + 1 )
		if ( exports.contacts[i].user_id == id )
		{
			user_contacts[l] = exports.contacts[i];
			l = l + 1;
		}

	return user_contacts;
}


exports.get_contact_through_id = function get_contact_through_id( id )
{
	for ( var i = 0; i < exports.contacts.length; i = i + 1 )
		if ( exports.contacts[i].id == id )
			return exports.contacts[i];
}

exports.Contact_controller = class Contact_controller extends cntrl.Controller
{
	add ( id ) // id of user
	{
		var data = users.get_user_through_id( id );

		data = JSON.stringify( data );

		return this.load_view( "contact", "add", data );
	}


	edit ( id ) // id of contact
	{
		var data = exports.get_contact_through_id( id );
		var user = users.get_user_through_id( data.user_id );

		data.firstname = user.firstname;
		data.lastname = user.lastname;

		data = JSON.stringify( data );

		return this.load_view( "contact", "edit", data );
	}


	dlte ( id ) // id of contact
	{
		var data = exports.get_contact_through_id( id );
		var user = users.get_user_through_id( data.user_id );

		data.firstname = user.firstname;
		data.lastname = user.lastname;

		data = JSON.stringify( data );

		return this.load_view( "contact", "delete", data );
	}

}
