"use strict";


var fs = require('fs');


exports.View = class View 
{


 	replace_at (a, index, replacement) 
	{
		return a.substr(0, index) + replacement + a.substr(index + replacement.length);
	}

	constructor( controller, path, data ) 
	{
		this.controller = controller;
		this.data = data;
		this.path = path;
	}
	
	send_data ()
	{
		
		var html = "<!DOCTYPE html><html><head><script> var data = " + this.data;

		html = html + "; function ech( a ){ document.write( a ); } </script> ";

		var all_html = html + fs.readFileSync( "./views/" + this.controller + '/' + this.path + ".html", 'utf8' ) + "</body> </html>";

		return all_html;
	}

};
