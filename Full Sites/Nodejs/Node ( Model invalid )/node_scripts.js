"use strict";

exports.get_date = function () 
{
	var date = new Date();
	return date.getDate() + ' ' + date.getMonth() + ' ' + date.getFullYear();
};

exports.get_time = function () 
{
	var date = new Date();
	return date.getHours() + " : " + date.getMinutes() ;
};

exports.set_style = function ( style ) 
{
  return "<style>" + style + "</style>";
};

exports.replace_at = function(a, index, replacement) {
    return a.substr(0, index) + replacement + a.substr(index + replacement.length);
}


