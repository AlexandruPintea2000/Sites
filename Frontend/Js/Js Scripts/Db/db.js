var db = [ 3, "firstname", "lastname", "age",
"firstname1", "lastname1", "age1",
"firstname2", "lastname2", "age2",
"firstname3", "lastname3", "age3",
"firstname4", "lastname4", "age4",
"firstname5", "lastname5", "age5"
 ];

function convert ()
{
	var row_count = db[0];
	var rows = [];
	for ( var i = 1; i <= row_count; i = i + 1 )
		rows.push( db[i] );

	var result = [];

	for ( var i = row_count + 1; i + 2 < db.length; i = i + row_count )
	{
		var row = "{ ";
		for ( var l = i; l < i + row_count; l = l + 1 )
		{
			row = row + "\"" + rows[ ( l - 1 ) % row_count ] + "\":\"" + db[l] + "\"";
			if ( l != i + row_count - 1 )
				row = row + ", ";
		}
		row = row + " }";

		row = JSON.parse( row );

		result.push( row );
	}

	return result;
}


console.log( convert() );