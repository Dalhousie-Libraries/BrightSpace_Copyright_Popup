<?php

$errorArray = array(
    D2LUserContext::RESULT_OKAY => "Success",
    D2LUserContext::RESULT_INVALID_SIG => "Invalid signature.",
    D2LUserContext::RESULT_INVALID_TIMESTAMP => "There is a time skew between server and local machine.  Try again.",
    D2LUserContext::RESULT_NO_PERMISSION => "Not authorized to perform this operation.",
    D2LUserContext::RESULT_UNKNOWN => "Unknown error occured"
    );


function d2l_exception_handler(Exception $ex){
	//Mail the error
	mail(EMAIL_ERROR,'Copyright Notice Exception',$ex);
	//Set something in syslog to flag the error.
	syslog(LOG_ERR,"Copyright Notice Exception" . $ex);
	//Return to the client a message saying that an exception occured and to contact DELTS.
	errorHandler('1','An Exception Occurred.  Please contact DELTS for investigation.');
}

function errorHandler($code,$messagetosend){
	header('Content-type: application/json');
	header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
	header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // Date in the past
	$scriptresult = array(
		'code' => $code,
		'message' => $messagetosend
	);
}

set_exception_handler('d2l_exception_handler');


function isFaculty($userid){
	$enrollments = getEnrollmentsForUser($userid);

	$faculty_groups = unserialize(FACULTY_GROUPS);

	foreach($enrollments['Items'] as $orgunit){
		if($orgunit['OrgUnit']['Type']['Id'] == 3){
			//Check if faculty
			foreach($faculty_groups as $facgrpId){
				if($orgunit['Role']['Id'] == $facgrpId){ //You will need to match up your target role IDs to faculty roles.
					return true;
				}
			}
		}
	}
	return false;
}

function getEnrollmentsForUser($userid){
	$authContextFactory = new D2LAppContextFactory();
	$authContext = $authContextFactory->createSecurityContext(APP_ID, APP_KEY);
	$hostSpec = new D2LHostSpec(HOST, PORT, SCHEME);
	$opContext = $authContext->createUserContextFromHostSpec($hostSpec, USER_ID, USER_KEY);
	$request = '/d2l/api/lp/1.0/enrollments/users/' . $userid . '/orgUnits/';
	$method = 'GET';
	try{
		$result = doRequest($opContext,$method,$request);
	}
	catch (d2lException $e) {
		if($e->getHttpcode() == '404'){
			//This username does not exist.
			return 0;
		}
		else{
			throw $e;
		}
	}
	return $result;
}

function getUserInfo($userid){
	$authContextFactory = new D2LAppContextFactory();
	$authContext = $authContextFactory->createSecurityContext(APP_ID, APP_KEY);
	$hostSpec = new D2LHostSpec(HOST, PORT, SCHEME);
	$opContext = $authContext->createUserContextFromHostSpec($hostSpec, USER_ID, USER_KEY);
	$request = '/d2l/api/lp/1.0/users/' . $userid;
	$method = 'GET';
	try{
		$result = doRequest($opContext,$method,$request);
	}
	catch (d2lException $e) {
		if($e->getHttpcode() == '404'){
			//This username does not exist.
			return 0;
		}
		else{
			throw $e;
		}
	}
	return $result; 
}

/**
 * Does the request to the D2L server with CURL.
 * Expects:
 * $opContext
 * $method for the call - GET/POST/PUT/DELETE
 * $request - The route to the call.
 * $data - optional json data to pass to the server.
 */
function doRequest($opContext,$method,$request,$data = null){
	global $errorArray;
	$ch = curl_init();
	$options = array(
	    CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYPEER => false
	    );

	curl_setopt_array($ch, $options);

	$tryAgain = true;
	$numAttempts = 1;
	while ($tryAgain && $numAttempts < 5) {
	    $uri = $opContext->createAuthenticatedUri($request, $method);
	    curl_setopt($ch, CURLOPT_URL, $uri);
	    switch($method) {
	        case 'POST':
	            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
	            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
	                'Content-Type: application/json',
	                'Content-Length: ' . strlen($data))
	            );
	            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
	            break;
	        case 'PUT':
	            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
	            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
	                'Content-Type: application/json',
	                'Content-Length: ' . strlen($data))
	            );
	            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
	            break;
	        case 'DELETE':
	            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
	            break;
	        case 'GET':
	            break;
	    }
	    $response = curl_exec($ch);

	    $httpCode  = curl_getinfo($ch, CURLINFO_HTTP_CODE);
	    $contentType = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
	    $responseCode = $opContext->handleResult($response, $httpCode, $contentType);

	    if ($responseCode == D2LUserContext::RESULT_OKAY) {
	        $ret = "$response";
	        $tryAgain = false;
	    } elseif ($responseCode == D2LUserContext::RESULT_INVALID_TIMESTAMP) {
	        // Try again since time skew should now be fixed.
	        $tryAgain = true;
	    } else {
	        if($httpCode == 302) {
	            // This usually happens when a call is made non-anonymously prior to logging in.
	            // The D2L server will send a redirect to the log in page.
	            throw new d2lException('Redirect encountered (need to log in for this API call?) (HTTP status 302)',$httpCode,$method . ': ' . $request,$response);
	        } else {
	            throw new d2lException($errorArray[$responseCode],$httpCode,$method . ': ' . $request,$response);
	        }
	        $tryAgain = false;
	        $protocol = (isset($_SERVER['SERVER_PROTOCOL']) ? $_SERVER['SERVER_PROTOCOL'] : 'HTTP/1.0');
	        throw new d2lException($protocol . ' ' . '400 Bad Request',$httpCode,$method . ': ' . $request,$response);
	        //$GLOBALS['http_response_code'] = $httpCode;
	    }
	    $numAttempts++;
	}
	curl_close($ch);
	//Parse result into object from JSON data
	$json_result = json_decode($ret,true);
	if($json_result == null){
		throw new d2lException('Error decoding JSON results: ' . json_last_error(),$httpCode,$method . ': ' . $request,$response);
	}
	return $json_result;
}
?>
