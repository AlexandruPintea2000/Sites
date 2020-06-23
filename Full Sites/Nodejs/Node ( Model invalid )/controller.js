"use strict";

var view = require( './view.js' );

exports.Controller = class Controller
{
	load_view( controller, path, data )
	{
		return new view.View( controller, path, data ).send_data();	
	}
};
