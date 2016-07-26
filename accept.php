<?php
//error_reporting(E_ALL);
//ini_set("display_errors", 1);
/**
 * User has accessed this file because they have accepted the copyright 
 * notice in D2L.  Mark them as having read it.
 */
require_once('lib/db.php');
require_once('d2llibs/D2LAppContextFactory.php');
require('exceptions.php');
require('lib/d2lfunctions.php');
require_once('config.php');

/*
 * Check the referrer of the request, and reject traffic if not from D2L.
 */
#if(!isset($_SERVER['HTTP_REFERER'])){
#	/*
#	 * Don't know where we are.
#	 */
#	header('HTTP/1.0 403 Forbidden');
#	exit();	
#}

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
	//Assume we are on prod
	header('Access-Control-Allow-Origin: ' . PROD_HTTP_REFERER);
#	header('HTTP/1.0 403 Forbidden');
#	exit();
}

$ie = false;
if(isset($_GET['b'])){
	if($_GET['b'] == 'ie'){
		$ie = true;
	}
}

$username = $_GET['uname'];
$type = $_GET['type'];
$uid = $_GET['uid'];

if(userRead($username,$type,$uid)){
	/**
	 * Redirect them back to the previous page.
	 */
	if($ie){
		header('Location: ' . $_SERVER['HTTP_REFERER']);
		exit();
	}
	else{
		exit();
	}
}
else{
	/**
	 * Send them back to where ever.
	 */
//	header('Location: ' . $_SERVER['HTTP_REFERER']);
	exit();
}
?>
