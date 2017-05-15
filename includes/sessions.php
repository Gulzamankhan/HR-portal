<?php
	ini_set('session.cookie_httponly', TRUE); 			
	ini_set('session.session.use_only_cookies', TRUE);	
	ini_set('session.cookie_lifetime', FALSE);			
	ini_set('session.cookie_secure', TRUE);				

	if(!isset($_SESSION))session_start();				

	$local = $set['localization'];
	switch ($local) {
		case 'english':	include ('language/english.php');	break;
	}

	if ((isset($_SESSION['tz']['userId'])) && ($_SESSION['tz']['userId'] != '')) {
	
		$tz_userId 		= $_SESSION['tz']['userId'];
		$tz_userEmail 	= $_SESSION['tz']['userEmail'];
		$tz_userFirst 	= $_SESSION['tz']['userFirst'];
		$tz_userLast 	= $_SESSION['tz']['userLast'];
		$tz_userFull 	= $_SESSION['tz']['userFirst'].' '.$_SESSION['tz']['userLast'];
		$tz_userLoc		= $_SESSION['tz']['location'];
		$tz_superUser	= $_SESSION['tz']['superUser'];
		$tz_isAdmin		= $_SESSION['tz']['isAdmin'];
	} else {
		$tz_userId = $tz_userEmail = $tz_userFirst = $tz_userLast = $tz_userFull = $tz_userLoc = $tz_superUser = $tz_isAdmin = '';
	}

	$msgBox = '';