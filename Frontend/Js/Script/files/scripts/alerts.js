document.write( "<div id=\"div_alert\"> " );
document.write( "	<div title=\"Click to Dismiss\" id=\"alert\" onclick=\"alert_hidden()\"> " );
document.write( "	</div> " );
document.write( "</div> " );

function alert ( alert_content )
{
	var alert = document.getElementById( "alert" );
	alert.style.top = "10px";
	alert.innerHTML = alert_content;
}


function alert_hidden ()
{
	var alert = document.getElementById( "alert" );
	alert.style.top = "-140px";
}
