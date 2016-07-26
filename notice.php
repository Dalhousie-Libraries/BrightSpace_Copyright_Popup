<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


/**
 * This scripts checks the users username to see if it exists in the DB 
 * and if so, shows them the copyright notice which they must accept.
 */
require_once('config.php');
require_once('lib/db.php');
require_once('d2llibs/D2LAppContextFactory.php');
require('exceptions.php');
require('lib/d2lfunctions.php');


/*
 * Check the referrer of the request, and reject traffic if not from D2L.
*/
if(stristr($_SERVER['HTTP_REFERER'],PROD_HTTP_REFERER)){
	/*
	 * We are on prod....continue.
	 */
	header('Access-Control-Allow-Origin: ' . PROD_HTTP_REFERER);
}
elseif(stristr($_SERVER['HTTP_REFERER'],DEV_HTTP_REFERER)){
	/*
	 * Maybe we are on dev....continue.
	 */
	header('Access-Control-Allow-Origin: ' . DEV_HTTP_REFERER);
}
else{
	/*
	 * Don't know where we are.
	 */
	header('Access-Control-Allow-Origin: ' . PROD_HTTP_REFERER);
}
//Note, the http/http or https/https must match the two servers in order for CORS to work in IE and for the ACAO headers to work.


$username = $_GET['uname'];
$uid = $_GET['uid'];

/*
 * Check to see if the user has accepted the notice.
 * lookupUsername will return:
 * - 0 if already accepted
 * - 1 if student
 * - 2 if faculty
 * 
 */
echo lookupUsername($username,$uid);
exit();
?>
