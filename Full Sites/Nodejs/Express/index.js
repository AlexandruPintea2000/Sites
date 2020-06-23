const application = require( 'express' )();
const port = 3000;

var url = require('url');

var a = " <a href=\"/?path=user_controller/index\">All Users</a> ";
a = a + "<a href=\"/?path=user_controller/view/9\">View User 9</a> ";
a = "<p>" + a + "</p>";

var html = "<!DOCTYPE html> <html> <head> ";
html = html + "<style> html { font-family: arial; } </style>";
html = html + "<title> index </title> </head> ";

function load_view ( req, res )
{
	var realUrl = (req.connection.encrypted ? 'https': 'http') + '://' + req.headers.host + req.url;
	var q = url.parse( realUrl, true );

	var path = "";

	if ( q.query.path == null )
		path = "user_controller/index";
	else
		path = q.query.path;

	var params = path.split( '/' );
	var controller = params[ 0 ];
	var subprogram = params[ 1 ];
	if ( subprogram == null )
		subprogram = "index";
	var parameter = params[ 2 ];
	if ( parameter == null )
		parameter = "";
	var b = '<p>Controller: ' + controller + ' </p>';
	b = b + '<p>Subprogram: ' + subprogram + ' </p>';
	b = b + '<p>Parameter: ' + parameter + ' </p>';

	var l = "";
	if ( subprogram == "index" )
		l = "<h2> All Users are shown! </h2>";

	if ( subprogram == "view" )
		l = "<h2> User " + parameter + " is shown! </h2>";

	if ( subprogram == "view" && parameter != 9 )
		l = "<h2> User " + parameter + " is shown ( and invalid )! </h2>";

	if ( subprogram != "index" && subprogram != "view" )
		l = "<h2> Subprogram: '" + subprogram + "' invalid. </h2>";

	if ( controller != "user_controller" )
		l = "<h2> Controller: '" + controller + "' invalid. </h2>";

	return res.send( html + l + b + a ); 	
}

application.get('/', (req, res) => {

	load_view( req, res );

} );

// application.get('/user', (req, res) => res.send( html + '<h1>User!</h1>' + a ) );
application.listen(port, () => console.log (`\nServer launched at http://http://127.0.0.1:${port}\n`));
