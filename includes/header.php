<?php

	$dayStart		= $set['weekStart'];		
	$calLanguage	= $set['calLocalization'];	
	$avatarDir		= $set['avatarFolder'];		
	$enableSched	= $set['enableSchedule'];	

	$dashNav = $calNav = $taskNav = $msgNav = $acctNav = $userNav = $rptNav = $mngNav = '';
	if (isset($dashPage)) { 	$dashNav = 'active'; 	} else { $dashNav = ''; }
	if (isset($calPage)) { 		$calNav = 'active'; 	} else { $calNav = ''; }
	if (isset($taskPage)) { 	$taskNav = 'active'; 	} else { $taskNav = ''; }
	if (isset($msgPage)) { 		$msgNav = 'active'; 	} else { $msgNav = ''; }
	if (isset($acctPage)) { 	$acctNav = 'active'; 	} else { $acctNav = ''; }
	if (isset($userPage)) { 	$userNav = 'active';	} else { $userNav = ''; }
	if (isset($rptPage)) { 		$rptNav = 'active';		} else { $rptNav = ''; }
	if (isset($mngPage)) { 		$mngNav = 'active'; 	} else { $mngNav = ''; }

	$currentYear = date('Y');
	$thisMonth = date('F');

	$theDate 		= date('Y-m-d');
	$currentMonth	= date('m');
	$weekNo 		= date('W', strtotime($theDate) + 60 * 60 * 24 );
	if ($currentMonth == '12' && $weekNo == '01') { $weekNum = '52'; } else { $weekNum = $weekNo; }

	$pm = "SELECT 'X' FROM privatemessages WHERE toId = ".$tz_userId." AND toRead = 0 AND toArchived = 0 AND toDeleted = 0";
	$pmres = mysqli_query($mysqli, $pm) or die('-101' . mysqli_error());
	$pmcount = mysqli_num_rows($pmres);
	if ($pmcount == 1) { $pmcountText = $unrdMsgText; } else { $pmcountText = $unrdMsgsText; }

	$date_array = getFirstLastDates(strtotime(date('Y-m-d')));
	if ($set['weekStart'] == '0') {
		$wkStartDay = $date_array['sdoflw'];
		$wkEndDay = $date_array['edoflw'];
	} else {
		$wkStartDay = $date_array['sdoflw'];
		$wkEndDay = $date_array['edoflw'];
		
		$date1 = str_replace('-', '/', $wkStartDay);
		$wkStartDay = date('Y-m-d',strtotime($date1 . "+1 days"));
		
		$date2 = str_replace('-', '/', $wkEndDay);
		$wkEndDay = date('Y-m-d',strtotime($date2 . "+1 days"));
	}


	$usertotal = "SELECT
				users.*,
				CONCAT(users.userId) AS totalcount
			FROM
				users 
			ORDER BY userId DESC";
	$usercn = mysqli_query($mysqli, $usertotal) or die('-2' . mysqli_error());

	$usercnt=mysqli_num_rows($usercn); 


	$check = $mysqli->query("SELECT 'X' FROM timelogs WHERE userId = ".$tz_userId." AND (entryDate BETWEEN '".$wkStartDay."' AND '".$wkEndDay."') LIMIT 1");
	if ($check->num_rows) {
		$qry1 = "SELECT
					TIMEDIFF(endTime,startTime) AS diff
				FROM
					timelogs
				WHERE
					userId = ".$tz_userId." AND
					(entryDate BETWEEN '".$wkStartDay."' AND '".$wkEndDay."') AND
					endTime != '00:00:00'";
		$res1 = mysqli_query($mysqli, $qry1) or die('-102'.mysqli_error());
		$times = array();
		while ($u = mysqli_fetch_assoc($res1)) {
			$times[] = $u['diff'];
		}
		$totalTime = sumHours($times);
	} else {
		$totalTime = '00:00';
	}

	$check2 = "SELECT timeId FROM timelogs WHERE userId = ".$tz_userId." AND weekNo = '".$weekNum."' AND clockYear = '".$currentYear."' AND endTime = '0000-00-00 00:00:00' LIMIT 1";
	$check2res = mysqli_query($mysqli, $check2) or die('-103' . mysqli_error());
	$timeRunning = mysqli_num_rows($check2res);
	$cols = mysqli_fetch_assoc($check2res);

	if ($timeRunning > 0) {
		$timeId = $cols['timeId'];
		$running = '1';
	} else {
		$timeId = '';
		$running = '0';
	}

	$ot = "SELECT 'X' FROM tasks WHERE assignedTo = ".$tz_userId." AND isClosed = 0";
	$otres = mysqli_query($mysqli, $ot) or die('-104' . mysqli_error());
	$otcount = mysqli_num_rows($otres);
	if ($otcount == 1) { $otcountText = $openTaskText; } else { $otcountText = $openTasksText; }

	if ($tz_isAdmin == '1') {
		$auths = getAuth($tz_userId);
	} else {
		$auths = '';
	}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta http-equiv="content-type" content="text/html; charset=UTF-8">
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="description" content="<?php echo $metaDesc; ?>">
	<meta name="author" content="<?php echo $metaAuthor; ?>">

	<title><?php echo $set['siteName']; ?> &middot; <?php echo $pageTitle; ?></title>

	<link href="css/font-awesome.css" rel="stylesheet" type="text/css" />
	<link rel="stylesheet" href="css/bootstrap.css" type="text/css" />
	<?php if (isset($addCss)) { echo $addCss; } ?>
	<link rel="stylesheet" href="css/style.css" type="text/css" />

	<!--[if lt IE 9]>
		<script src="js/html5shiv.min.js"></script>
		<script src="js/respond.min.js"></script>
	<![endif]-->
</head>

<body>
	<nav class="navbar navbar-inverse navbar-static-top">
		<div class="container-fluid">
			<div class="container">
				<div class="navbar-header">
					<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
						<span class="sr-only"><?php echo $toggleNavText; ?></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>
				</div>

				<div id="navbar" class="navbar-collapse collapse">
					<ul class="nav navbar-nav">
						<li class="<?php echo $dashNav; ?>"><a href="index.php">Welcome <?php echo  $tz_userFirst; ?></a></li>
						<li class="dropdown <?php echo $msgNav; ?>">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><?php echo $footerNav4; ?> <i class="fa fa-angle-down"></i></a>
							<ul class="dropdown-menu" role="menu">
								<li><a href="index.php?page=messages"><?php echo $inboxNav; ?></a></li>
								<li><a href="index.php?page=sent"><?php echo $sentNav; ?></a></li>
								<li><a href="index.php?page=archived"><?php echo $archivedNav; ?></a></li>
								<li><a href="index.php?page=compose"><?php echo $composeNav; ?></a></li>
							</ul>
						</li>
						<li class="dropdown <?php echo $acctNav; ?>">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><?php echo $myAccNav; ?> <i class="fa fa-angle-down"></i></a>
							<ul class="dropdown-menu" role="menu">
								<?php if ($enableSched == '1') { ?>
									<li><a class="hide" href="index.php?page=mySchedule"><?php echo $mySchedNav; ?></a></li>
								<?php } ?>
								<?php if ((checkArray('USRRPT', $auths)) || $tz_userId > '1') { ?>
								<li><a href="index.php?page=myDocuments"><?php echo $myDocsNav; ?></a></li>
								<?php } ?>
								<li><a href="index.php?page=myProfile"><?php echo $footerNav5; ?></a></li>
								<li><a data-toggle="modal" href="#signOut"><?php echo $signOutNav; ?></a></li>
							</ul>
						</li>
					</ul>
					<?php if ($tz_isAdmin == '1') { ?>
						<ul class="nav navbar-nav navbar-right">
							<?php if ((checkArray('MNGUSR', $auths)) || (checkArray('APPAUTH', $auths)) || $tz_superUser != '') { ?>
								<li class="dropdown <?php echo $userNav; ?>">
									<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Users <i class="fa fa-angle-down"></i></a>
									<ul class="dropdown-menu" role="menu">
										<li><a href="index.php?page=newUser"><?php echo $newUserNav; ?></a></li>
										<?php if ((checkArray('APPAUTH', $auths)) || $tz_superUser != '') { ?>
											<li><a href="index.php?page=userAuths"><?php echo $userAuthNav; ?></a></li>
											<li><a href="index.php?page=activeUsers"><?php echo $activeUserNav; ?></a></li>
										<?php } ?>
									</ul>
								</li>
								<?php if ((checkArray('USRRPT', $auths)) || $tz_superUser != '') { ?>
											<li><a href="index.php?page=attReports" style="display:none;">Consultants reports</a></li>
										<?php } ?>
							<?php } ?>
							<?php
								if (
								(checkArray('MNGSCHED', $auths)) ||
								(checkArray('MNGTASKS', $auths)) ||
								(checkArray('MNGTIMECAR', $auths)) ||
								(checkArray('SITENOTES', $auths)) ||
								(checkArray('MNGUSRDOCS', $auths)) ||
								(checkArray('SITESET', $auths)) ||
								$tz_superUser != '') {
							?>
								<li class="dropdown <?php echo $mngNav; ?>">
									<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><?php echo $manageNav; ?> <i class="fa fa-angle-down"></i></a>
									<ul class="dropdown-menu" role="menu">
										<?php if ((checkArray('SITENOTES', $auths)) || $tz_superUser != '') { ?>
											<li class="hide"><a href="index.php?page=mngNotices"><?php echo $mngNoticesNav; ?></a></li>
										<?php } ?>
										<?php if ((checkArray('MNGUSRDOCS', $auths)) || $tz_superUser != '') { ?>
											<li><a href="index.php?page=mngDocuments"><?php echo $mngUsrDocsNav; ?></a></li>
										<?php } ?>
										<?php if ((checkArray('SITESET', $auths)) || $tz_superUser != '') { ?>
											<li><a href="index.php?page=mngSettings"><?php echo $siteSetNav; ?></a></li>
										<?php } ?>
									</ul>
								</li>
							<?php } ?>
						</ul>
					<?php } ?>
				</div>
			</div>
		</div>
	</nav>

	<div class="modal fade" id="signOut" tabindex="-1" role="dialog" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-body">
					<p class="lead"><?php echo $tz_userFull.$logoutConfirmationMsg; ?></p>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default btn-sm btn-icon" data-dismiss="modal"><i class="fa fa-times"></i> <?php echo $closeBtn; ?></button>
					<a href="index.php?action=logout" class="btn btn-success btn-sm btn-icon-alt"><?php echo $signOutNav; ?> <i class="fa fa-sign-out"></i></a>
				</div>
			</div>
		</div>
	</div>

	<div class="container-fluid headerBar">
		<div class="container">
			<div class="row">
				<div class="col-md-4"  style="margin-top: -16px;">
					<div class="site-title"><a href="index.php" rel="home"><img src="images/logo.png" alt="Syrainfotek"></a></div>
				</div>
				<div class="col-md-8">
					<div class="tzBlocks">
						<div class="row clearfix">
							<div class="col-md-4 blockCont">
								
								<span class="titleCont"><a href="index.php?page=messages">My Messages</a></span>
								<span class="txtCont"><a href="index.php?page=messages"><?php echo $pmcount; ?></a></span>
							</div>

							

							<div class="col-md-4 blockCont">

													<form>
							<input type="hidden" id="timeId" value="<?php echo $timeId; ?>" />
							<input type="hidden" id="weekNo" value="<?php echo $weekNum; ?>" />
							<input type="hidden" id="clockYear" value="<?php echo $currentYear; ?>" />
							<input type="hidden" id="running" value="<?php echo $running; ?>" />
						</form>

						
								
							</div>
							<div class="col-md-4 blockCont">

							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="container-fluid titleBar">
		<div class="container">
			<div class="row">
				
				<div class="col-md-12 no-padding">
					<div class="titleRight">
						<div class="title">
							<h2><?php echo $pageTitle; ?></h2>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<input type="hidden" id="dayStart" value="<?php echo $dayStart; ?>" />