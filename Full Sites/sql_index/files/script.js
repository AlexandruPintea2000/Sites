function alert_text ()
{
	var text_alert = document.getElementById("alert_text").value;

	if ( text_alert == "" )
	{
		alert( "Please enter text." );
		return;
	}

	alert( text_alert );
}