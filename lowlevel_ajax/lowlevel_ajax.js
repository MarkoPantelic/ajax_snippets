//      AJAX     //
// lowlevel ajax
//
// 1. Create function which will be invoked on server ajax responseText,
//    That function must include getAjaxResponse() as source of data.
// 2. Call callAjax() with (1.) function as callback function parameter
//---------------//


function callAjax(target_script, query_str, query_type, ajax_func, af_arg, asynchronus) {
	/*
	Ajax connection function:
	Transmits 'query_str', 'query_type' to PHP script.
	It processes server response with `ajax_func` function which is being
    called with arguments `af_arg`

    NOTE: Internet Explorer <= 8 doesn't accept `asynchronus=false`
	*/

    // creates global HTTP_REQUEST varible;


	try{
		HTTP_REQUEST = new XMLHttpRequest;
	}
	catch(e){

		try{
			HTTP_REQUEST = new ActiveXObject("Msxml2.XMLHTTP");
		}
		catch(e){
			try{
				HTTP_REQUEST = new ActiveXObject("Microsoft.XMLHTTP");
			}
			catch(e){
                // cannot create XMLHttpRequest object
			}

		}
	}
	if (!HTTP_REQUEST) {
        // log error
		console.log("'Browser' doesn't support XMLHttpRequest object creation");
		return 1;
	}
	else{
		//console.log("'Browser' sucessfully created XMLHttpRequest object");
		HTTP_REQUEST.onreadystatechange = function() { ajax_func(af_arg); };
		HTTP_REQUEST.open("POST", target_script, asynchronus);
		HTTP_REQUEST.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
		HTTP_REQUEST.send('query_str='+encodeURIComponent(query_str) +
                          '&query_type='+encodeURIComponent(query_type));
	}
}


function getAjaxResponse() {
	/*
	Checks Ajax response validity, converts response from JSON-a to JS object.
	Returns that final object.
	*/

    // expects global HTTP_REQUEST variable;
	var response = 0;

	try {
        // Explorer 8 doesn't recognize XMLHttpRequest.DONE. Thereby == 4 check is used.
		if (HTTP_REQUEST.readyState === 4) {

			if(HTTP_REQUEST.status === 200) {
				response = JSON.parse(HTTP_REQUEST.responseText);
			}
			else {
				window.alert("XMLHttpRequest.status invalid!");
				logJsErr("ERROR: HTTP_REQUEST.status = " + HTTP_REQUEST.status);
			}
		}
	}
	catch(e) {
		window.alert("Error in 'AJAX' connection! \nerr_str:"+e);
		logJsErr("Error: " + e + " " + HTTP_REQUEST.responseText)
	}
	return response;
}


// Example ajax action callback function
function ajaxDataStore(data_container, store_key) {
	/*
    Get raw data from AJAX response.
	Convert response JSON object into JS array and put converted data into
    data_container object
    */

    // expects global HTTP_REQUEST variable
	var response = getAjaxResponse();

	if (response !== 0) {

		//var response = JSON.parse(HTTP_REQUEST.responseText);

		if (response.length > 0) {
			data_container[store_key] = response;
		}
		else {
			data_container[store_key] = false;
		}
	}

}


function logJsErr() {
    console.log(arguments);
}
