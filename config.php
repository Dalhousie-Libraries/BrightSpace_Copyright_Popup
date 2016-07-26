<?php
/***************
 * there is also 1 variables that you need to change in widgetcode.js
 * var server = "LOCATION_OF_PHP_APPLICATION"; // This is your server name where the php script resides. //eg. "util.library.dal.ca/brightspace-copyright-app"
 */

// Database settings
define('DB_SERVER','YOUR_DB_SERVER');
define('DB_USERNAME','YOUR_DB_USERNAME');
define('DB_PASSWORD','YOUR_DB_PASSWORD');
define('DB_DATABASE','YOUR_DB_NAME');

// D2L Settings
define('HOST','YOUR_BRIGHGTSPACE_HOST_NAME'); //eg. dal.brightspace.com
define('PORT','443');
define('SCHEME','https');
define('APP_ID','YOUR_APP_ID'); // See README
define('APP_KEY','YOUR_APP_KEY'); // See README
//You will want to create an admin account that has access make the calls you need.  Then use it to generate the ID/Key pair.
//You will also probably want to set these keys to unlimited otherwise you will have to regenerate them and update this script every few months

define('USER_ID','YOUR_USER_ID'); // See README
define('USER_KEY','YOUR_USER_KEY'); // See README


//{YOUR_EMAIL_ADDRESS_IF_YOU_WANT_EMAILS_ON_ERROR}
define('EMAIL_ERROR','YOUR_EMAIL_ADDRESS');

define('PROD_HTTP_REFERER','YOUR_PROD_BRIGHTSPACE_URL'); //eg. https://dal.brightspace.com
define('DEV_HTTP_REFERER', 'YOUR_DEV_BRIGHTSPACE_URL'); //eg. https://daltest.brightspace.com

// These are the role id's that should be considered as Faculty Roles - The users in these groups will be prompted with the copyright notice
$faculty_groups = array();
$faculty_groups[]=119; // REFERENCE LIBRARIAN
$faculty_groups[]=125; // COPYRIGHT APP
$faculty_groups[]=102; // INSTRUCTOR
define('FACULTY_GROUPS', serialize($faculty_groups));

// Information to send email if more information is requested by user
define('FROM_NAME', 'YOUR_FROM_NAME'); //eg. Dalhousie Copyright Office - BrightSpace
define('FROM_EMAIL', 'YOUR_FROM_EMAIL'); //eg. Copyright.Office@dal.ca
define('TO_EMAIL', 'YOUR_TO_EMAIL'); //eg. Copyright.Office@dal.ca
define('SUBJECT', 'YOUR_SUBJECT'); //eg. Copyright Information Request
define('MESSAGE_BODY_PART_2', '
		<p>This request has been received by the Copyright Office (<a href=\'mailto:copyright.office@dal.ca\'>Copyright.Office@Dal.ca</a>). A representative from the office will contact you shortly for more information about your request.</p>
		<p>In the meantime, you can find additional copyright resources under the "For Faculty" section of our website: <a href=\'http://www.dal.ca/dept/copyrightoffice/for-faculty.html\'><span style=\'color:#0563C1\'>http://www.dal.ca/dept/copyrightoffice/for-faculty.html</span></a>. As well, we have an instructional video on Brightspace and Copyright, which can be viewed here: <a href=\'http://libcasts.library.dal.ca/Copyright_and_Brightspace/\'><span style=\'color:#0563C1\'>http://libcasts.library.dal.ca/Copyright_and_Brightspace/</span></a></p>
		<p>We look forward to talking with you soon!</p>
		<p>Sincerely,</p>
		<p>Dalhousie Libraries\' Copyright Office</p>
	</body>
	</html>\'');
?>
