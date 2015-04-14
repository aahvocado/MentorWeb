$(document).ready(function(){
	console.log("link check");
	var data = "";
	$.ajax({
    'async': false,
    'global': false,
    'url': "api/welcome",
    'dataType': "json",
    'success': function (dataReq) {
        data = dataReq;
        console.log(dataReq);
    }
  });

	console.log(data);

	if (data['username'] == null) {
		window.location.replace("index.html#/welcome");
	} else if (data['userType'] == "None") {
		window.location.replace("index.html#/register");
	} else {
		window.location.replace("index.html#/homescreen");
	}
});


function ajaxError(jqXHR, textStatus, errorThrown){
	console.log('ajaxError '+textStatus+' '+errorThrown);
	$('#error_message').remove();
	// $("#error_message_template").tmpl( {errorName: textStatus, errorDescription: errorThrown} ).appendTo( "#error_dialog_content" );
	//$.mobile.changePage($('#error_dialog'), {
		//transition: "pop",
		//reverse: false,
		//changeHash: false
	//});
}