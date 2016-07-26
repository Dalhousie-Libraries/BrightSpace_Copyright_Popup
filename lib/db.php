<?php

//error_reporting(E_ALL);
//ini_set("display_errors", 1);
/*******************
 * Database Functions
 */
/*******************
 * DB Schema (mysql Create):
 * 

CREATE TABLE
    notifications
    (
        d2lusername VARCHAR(50) NOT NULL,
        dateapproved DATETIME NOT NULL,
        noticetype INT(2) NOT NULL,
        id bigint NOT NULL AUTO_INCREMENT,
        PRIMARY KEY (id)
    )
    ENGINE=MyISAM DEFAULT CHARSET=latin1;

 */

/**
 * Connect to the database.
 * @return type mysql link
 */
function connectToDatabase(){
	$dbconnection = mysqli_connect(DB_SERVER,DB_USERNAME,DB_PASSWORD);
	if (!$dbconnection){
		die('Could not connect: ' . mysqli_error());
	}
	mysqli_select_db($dbconnection, DB_DATABASE);

	return $dbconnection;
}

/**
 * Closes the DB connection.
 */
function closeDatabase($link){
	mysqli_close($link);
	return true;
}

/**
 * Looks up the username of the user in the DB to see if they have submitted.
 * @param type $username - Username of the D2L user
 */
function lookupUsername($username,$uid){
	$con = connectToDatabase();
	$query = "SELECT COUNT(*) AS number, id, dateapproved FROM notifications WHERE d2lusername = '{$username}' AND expire = 0";
	$result = mysqli_query($con,$query);
	$row = mysqli_fetch_array($result);
	$query = "SELECT expiretime FROM ExpireTime WHERE id = 1";
	$newresult = mysqli_query($con,$query);
	$time = mysqli_fetch_array($newresult)['expiretime'];
	if($row['number'] > 0){
		/*
		 * Check whether the record is still valid
		 */
		$now = new DateTime();
		$old = new DateTime($row['dateapproved']);
		$datepassed = $old->diff($now);
		/*
		 * If it's expired, update the database record
		 */
		if($datepassed->days > $time){
			$query = "UPDATE notifications SET expire = 1 WHERE id = ".$row['id'];
			mysqli_query($con,$query);
			/**
			 * Check to see what type of user they are.
			 */
			if(isFaculty($uid)){
				closeDatabase($con);
				return 2;
			}
			else{
				closeDatabase($con);
				return 1;
			}
		}
		/*
		 * User has already accepted a notice.
		 */
		closeDatabase($con);
		return 0;
	}
	else{
		/**
		 * Check to see what type of user they are.
		 */
		if(isFaculty($uid)){
			closeDatabase($con);
			return 2;
		}
		else{
			closeDatabase($con);
			return 1;
		}
	}
}

/**
 * Marks the username as have read and accepted the notice.
 * @param type $username - D2L Username
 */
function userRead($username,$noticetype,$uid){
	$con = connectToDatabase();
	$dateapproved = date("Y-m-d H:i:s");
	$query = "SELECT 1 FROM notifications WHERE d2lusername = '{$username}' AND noticetype = '{$noticetype}' AND expire = 0";
	$result = mysqli_query($con,$query);

	/**
	 * Check whether the data is alreay in the database
	 */ 
	if(mysqli_num_rows($result) == 0){

		$query = "INSERT INTO notifications (d2lusername,dateapproved,noticetype) VALUES ('{$username}','{$dateapproved}','{$noticetype}');";
		mysqli_query($con,$query);

		if(mysqli_affected_rows($con) == 1){
			/*
			 * Check for the notification type. 
			 * If it's a information requesting(i.e. $noticitype = 3), we'd like to send an email to the copyright office.
			 */

			if($noticetype == 3){

				//Get User Contact Information
				$userInfo = getUserInfo($uid);

				if($userInfo != 0){

					$clientName = FROM_NAME;
					$clientEmail = FROM_EMAIL;
					$toEmail = TO_EMAIL;
					$subject = SUBJECT;
					$message_body = "<html>
							<body>
								<p>The following user is requesting for more information:</p>
								<p>Name: ".$userInfo['DisplayName']."</p>
								<p>Username: ".$username."</p>
								<p>Email: ". $userInfo['ExternalEmail'] . "</p>" . MESSAGE_BODY_PART_2;

					$headers = 'MIME-Version: 1.0' . "\r\n";
					$headers .= 'Content-type: text/html; charset=UTF-8' . "\r\n";
					$headers .= "From: ". $clientName . " <" . $clientEmail . ">\r\n"."CC: ".$userInfo['ExternalEmail'];

					$success = mail($toEmail, $subject, $message_body, $headers);
				}
			}
		closeDatabase($con);
		return true;
		}
		else{
			/**
			 * Error
			 */
			/**
			 * This shouldn't happen, so log it to syslog.
			 */
		    openlog('PHP - D2L Copyright Notice',LOG_PID,LOG_USER);
		    syslog(LOG_ERR,'User couldn\'t be marked as having read the Copyright notice.  Script is broken: mysql error: ' . mysqli_error($con));
		    closelog();
			
			closeDatabase($con);
			return false;
		}
	}
	else{
		/**
		 * Data is already in the database, just return.
		 */
		closeDatabase($con);
		return true;
	}
}

/*******************
 * End Database Functions
 */
?>
