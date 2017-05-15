<?php
	$userId = htmlspecialchars($_GET['userId']);

	$accountTab = 'active';
	$mngrTab = $emailTab = $passwordTab = $avatarTab = $statusTab = $termTab = '';

	
	$actIp = $_SERVER['REMOTE_ADDR'];

	
	$avatarDir 		= $set['avatarFolder'];
	$userDocsPath	= $set['userDocsPath'];

	
	if (isset($_POST['submit']) && $_POST['submit'] == 'updAcc') {
		
		if($_POST['userFirst'] == '') {
			$msgBox = alertBox($usrFisrNameReq, "<i class='fa fa-times-circle'></i>", "danger");
		} else if($_POST['userLast'] == '') {
			$msgBox = alertBox($usrLastNameReq, "<i class='fa fa-times-circle'></i>", "danger");
		} else {
			$userFirst = htmlspecialchars($_POST['userFirst']);
			$userMiddleInt = htmlspecialchars($_POST['userMiddleInt']);
			$userLast = htmlspecialchars($_POST['userLast']);
			$userPhone1 = encodeIt($_POST['userPhone1']);
			$userPhone2 = encodeIt($_POST['userPhone2']);
			$userpincode = htmlspecialchars($_POST['userpincode']);
			$userstreet = htmlspecialchars($_POST['userstreet']);
			$userstatee = htmlspecialchars($_POST['userstate']);
			$usercityy = htmlspecialchars($_POST['usercity']);
			$employeenumber = htmlspecialchars($_POST['employeenumber']);
			$userHireDate = htmlspecialchars($_POST['userHireDate']);
			$userPosition = htmlspecialchars($_POST['userPosition']);

			if ($_POST['location'] == "") {
				$location = 'Alpharetta, US';
			} else {
				$location = htmlspecialchars($_POST['location']);
			}

			$stmt = $mysqli->prepare("UPDATE
										users
									SET
										userFirst = ?,
										userMiddleInt = ?,
										userLast = ?,
										userPhone1 = ?,
										userPhone2 = ?,
										userstate = ?,
										usercity = ?,
										userpincode = ?,
										userstreet = ?,
										employeenumber = ?,
										location = ?,
										userHireDate = ?,
										userPosition = ?
									WHERE
										userId = ?"
			);
			$stmt->bind_param('ssssssssssssss',
									$userFirst,
									$userMiddleInt,
									$userLast,
									$userPhone1,
									$userPhone2,
									$userstatee,
									$usercityy,
									$userpincode,
									$userstreet,
									$employeenumber,
									$location,
									$userHireDate,
									$userPosition,
									$userId
			);
			$stmt->execute();
			$stmt->close();

			
			$activityType = '19';
			$activityTitle = $tz_userFull.' '.$usrAccUpdAct.' '.$userFirst.' '.$userLast;
			updateActivity($tz_userId,$activityType,$activityTitle);

			$msgBox = alertBox($usrAccUpdMsg." ".$userFirst." ".$userLast." ".$editEventMsg, "<i class='fa fa-check-square'></i>", "success");
		}
    }

	
	if (isset($_POST['submit']) && $_POST['submit'] == 'updManager') {
		
		if($_POST['managerId'] == '...') {
			$msgBox = alertBox($usrMngSelReq, "<i class='fa fa-times-circle'></i>", "danger");
		} else {
			$managerId = htmlspecialchars($_POST['managerId']);
			$usrsName = htmlspecialchars($_POST['usrsName']);

			$stmt = $mysqli->prepare("UPDATE
										users
									SET
										managerId = ?
									WHERE
										userId = ?"
			);
			$stmt->bind_param('ss',
									$managerId,
									$userId
			);
			$stmt->execute();
			$stmt->close();

			
			$activityType = '19';
			$activityTitle = $tz_userFull.' '.$usrMngAssignedAct.' '.$usrsName;
			updateActivity($tz_userId,$activityType,$activityTitle);

			$msgBox = alertBox($usrMngAssignedMsg." ".$usrsName, "<i class='fa fa-check-square'></i>", "success");

			$mngrTab = 'active';
			$accountTab = $emailTab = $passwordTab = $avatarTab = $statusTab = $termTab = '';
		}
    }

	
	if (isset($_POST['submit']) && $_POST['submit'] == 'updEmail') {
		
		if($_POST['userEmail'] == '') {
			$msgBox = alertBox($usrNewEmailReq, "<i class='fa fa-times-circle'></i>", "danger");
			$emailTab = 'active';
			$accountTab = $mngrTab = $passwordTab = $avatarTab = $statusTab = $termTab = '';
		} else if($_POST['userEmail_r'] == '') {
			$msgBox = alertBox($repeatUsrNewEmailReq, "<i class='fa fa-times-circle'></i>", "danger");
			$emailTab = 'active';
			$accountTab = $mngrTab = $passwordTab = $avatarTab = $statusTab = $termTab = '';
		} else if($_POST['userEmail'] != $_POST['userEmail_r']) {
			$msgBox = alertBox($emailsNoMatchMsg, "<i class='fa fa-times-circle'></i>", "danger");
			$emailTab = 'active';
			$accountTab = $mngrTab = $passwordTab = $avatarTab = $statusTab = $termTab = '';
		} else {
			$userEmail = htmlspecialchars($_POST['userEmail']);
			$usrsName = htmlspecialchars($_POST['usrsName']);

			$stmt = $mysqli->prepare("UPDATE
										users
									SET
										userEmail = ?
									WHERE
										userId = ?"
			);
			$stmt->bind_param('ss',
									$userEmail,
									$userId
			);
			$stmt->execute();
			$stmt->close();

			
			$activityType = '19';
			$activityTitle = $tz_userFull.' '.$usrNewEmailUpdAct.' '.$usrsName;
			updateActivity($tz_userId,$activityType,$activityTitle);

			$msgBox = alertBox($usrNewEmailUpdMsg." ".$usrsName." ".$editEventMsg, "<i class='fa fa-check-square'></i>", "success");

			$emailTab = 'active';
			$accountTab = $mngrTab = $passwordTab = $avatarTab = $statusTab = $termTab = '';
		}
    }

	
	if (isset($_POST['submit']) && $_POST['submit'] == 'updPassword') {
		
		if($_POST['password1'] == '') {
			$msgBox = alertBox($usrNewPassReq, "<i class='fa fa-times-circle'></i>", "danger");
			$passwordTab = 'active';
			$accountTab = $mngrTab = $emailTab = $avatarTab = $statusTab = $termTab = '';
		} else if($_POST['password2'] == '') {
			$msgBox = alertBox($repeatUserNewPassReq, "<i class='fa fa-times-circle'></i>", "danger");
			$passwordTab = 'active';
			$accountTab = $mngrTab = $emailTab = $avatarTab = $statusTab = $termTab = '';
		} else if($_POST['password1'] != $_POST['password2']) {
			$msgBox = alertBox($usrAccPassMatchErr, "<i class='fa fa-times-circle'></i>", "danger");
			$passwordTab = 'active';
			$accountTab = $mngrTab = $emailTab = $avatarTab = $statusTab = $termTab = '';
		} else {
			$usrsName = htmlspecialchars($_POST['usrsName']);
			$password = encodeIt($_POST['password1']);

			$stmt = $mysqli->prepare("UPDATE
										users
									SET
										password = ?
									WHERE
										userId = ?"
			);
			$stmt->bind_param('ss',
									$password,
									$userId
			);
			$stmt->execute();
			$stmt->close();

			
			$activityType = '19';
			$activityTitle = $tz_userFull.' '.$usrNewPassUpdAct.' '.$usrsName;
			updateActivity($tz_userId,$activityType,$activityTitle);

			$msgBox = alertBox($usrNewPassUpdMsg." ".$usrsName." ".$usrNewPassUpdMsg1, "<i class='fa fa-check-square'></i>", "success");

			$passwordTab = 'active';
			$accountTab = $mngrTab = $emailTab = $avatarTab = $statusTab = $termTab = '';
		}
    }

	
	if (isset($_POST['submit']) && $_POST['submit'] == 'deleteAvatar') {
		$userAvatar = htmlspecialchars($_POST['userAvatar']);
		$userFolder = htmlspecialchars($_POST['userFolder']);
		$usrsName = htmlspecialchars($_POST['usrsName']);

		$filePath = $avatarDir.$userFolder.'/'.$userAvatar;
		
		if (file_exists($filePath)) {
			unlink($filePath);

			
			$avatarImage = 'userAvatar.png';
			$stmt = $mysqli->prepare("
								UPDATE
									users
								SET
									userAvatar = ?
								WHERE
									userId = ?");
			$stmt->bind_param('ss',
							   $avatarImage,
							   $userId);
			$stmt->execute();
			$stmt->close();

			
			$activityType = '19';
			$activityTitle = $tz_userFull.' '.$userDelAvatarAct.' '.$usrsName;
			updateActivity($tz_userId,$activityType,$activityTitle);

			$msgBox = alertBox($userDelAvatarMsg." ".$usrsName." ".$delEventMsg2, "<i class='fa fa-check-square'></i>", "success");
		} else {
			
			$activityType = '0';
			$activityTitle = $tz_userFull.' '.$usrAvatarDelErrAct.' '.$usrsName;
			updateActivity($tz_userId,$activityType,$activityTitle);

			$msgBox = alertBox($usrAvatarDelErrMsg." ".$usrsName." ".$usrAvatarDelErrMsg1, "<i class='fa fa-check-square'></i>", "success");
		}

		$avatarTab = 'active';
		$accountTab = $mngrTab = $emailTab = $passwordTab = $statusTab = $termTab = '';
	}


	if (isset($_POST['submit']) && $_POST['submit'] == 'immigInfo') {


			$visastatus = htmlspecialchars($_POST['visastatus']);
			$visaexpiry = htmlspecialchars($_POST['visaexpiry']);
			

			if ($_POST['location'] == "") {
				$location = 'Alpharetta';
			} else {
				$location = htmlspecialchars($_POST['location']);
			}

			$stmt = $mysqli->prepare("UPDATE
										users
									SET
										visastatus = ?,
										visaexpiry = ?,
										
										location = ?
									WHERE
										userId = ?"
			);
			$stmt->bind_param('ssss',
									$visastatus,
									$visaexpiry,
									
									$location,
									$tz_userId
			);
			$stmt->execute();
			$stmt->close();

			

			$msgBox = alertBox($immigrationInfoUpdatedMsg, "<i class='fa fa-check-square'></i>", "success");

			$_SESSION['tz']['location']  = $location;
			$tz_userLoc	  = $location;

			$immigrationTab = 'active';
			$personalTab = $emailTab = $passTab = $avatarTab = '';

    }


	
	if (isset($_POST['submit']) && $_POST['submit'] == 'updStatus') {
		$isActive = htmlspecialchars($_POST['isActive']);
		$usrsName = htmlspecialchars($_POST['usrsName']);

		if ($userId != '1') {
			$stmt = $mysqli->prepare("UPDATE
										users
									SET
										isActive = ?
									WHERE
										userId = ?"
			);
			$stmt->bind_param('ss',
									$isActive,
									$userId
			);
			$stmt->execute();
			$stmt->close();

			
			$activityType = '19';
			$activityTitle = $tz_userFull.' '.$usrAccStatusUpdAct.' '.$usrsName;
			updateActivity($tz_userId,$activityType,$activityTitle);

			$msgBox = alertBox($usrAccStatusUpdMsg." ".$usrsName." ".$editEventMsg, "<i class='fa fa-check-square'></i>", "success");
		} else {
			
			$activityType = '0';
			$activityTitle = $tz_userFull.' '.$usrPrimAdmAccErrAct;
			updateActivity($tz_userId,$activityType,$activityTitle);

			$msgBox = alertBox($usrPrimAdmAccErrMsg, "<i class='fa fa-warning'></i>", "warning");
		}

		$statusTab = 'active';
		$accountTab = $mngrTab = $emailTab = $passwordTab = $avatarTab = $termTab = '';
    }

	
	if (isset($_POST['submit']) && $_POST['submit'] == 'updTerm') {
		$terminate = htmlspecialchars($_POST['terminate']);
		$usrsName = htmlspecialchars($_POST['usrsName']);

		if ($userId != '1') {
			if ($terminate == '0') {
				
				$termDate = '0000-00-00 00:00:00';
				$stmt = $mysqli->prepare("UPDATE
											users
										SET
											isActive = 1,
											terminationDate = ?,
											terminationReason = NULL
										WHERE
											userId = ?"
				);
				$stmt->bind_param('ss',
										$termDate,
										$userId
				);
				$stmt->execute();
				$stmt->close();

				
				$activityType = '19';
				$activityTitle = $tz_userFull.' '.$usrTermStatusUpdAct.' '.$usrsName;
				updateActivity($tz_userId,$activityType,$activityTitle);

				$msgBox = alertBox($usrTermStatusUpdMsg." ".$usrsName." ".$usrTermStatusUpdMsg1, "<i class='fa fa-check-square'></i>", "success");
			} else {
				
				$terminationDate = htmlspecialchars($_POST['terminationDate']);
				$terminationReason = htmlspecialchars($_POST['terminationReason']);

				$stmt = $mysqli->prepare("UPDATE
											users
										SET
											isActive = 0,
											terminationDate = ?,
											terminationReason = ?
										WHERE
											userId = ?"
				);
				$stmt->bind_param('sss',
										$terminationDate,
										$terminationReason,
										$userId
				);
				$stmt->execute();
				$stmt->close();

				
				$activityType = '19';
				$activityTitle = $tz_userFull.' '.$usrTerminatedAct.' '.$usrsName;
				updateActivity($tz_userId,$activityType,$activityTitle);

				$msgBox = alertBox($clockUserOutMsg1." ".$usrsName." ".$usrTerminatedMsg, "<i class='fa fa-check-square'></i>", "success");
			}
		} else {
			
			$activityType = '0';
			$activityTitle = $tz_userFull.' '.$usrPrimAdmAccErrAct;
			updateActivity($tz_userId,$activityType,$activityTitle);

			$msgBox = alertBox($usrPrimAdmAccErrMsg, "<i class='fa fa-warning'></i>", "warning");
		}

		$termTab = 'active';
		$accountTab = $mngrTab = $emailTab = $passwordTab = $avatarTab = $statusTab = '';
    }

	
	$sql = "SELECT
				users.*,
				(SELECT CONCAT(usr.userFirst,' ',usr.userLast) FROM users AS usr WHERE usr.userId = users.managerId) AS managerName
			FROM
				users
			WHERE users.userId = ".$userId;
	$res = mysqli_query($mysqli, $sql) or die('-1' . mysqli_error());
	$row = mysqli_fetch_assoc($res);

	if ($row['userPosition'] != '') { $userPosition = clean($row['userPosition']).'<br />'; } else { $userPosition = ''; }
	if ($row['userPhone1'] != '') { $userPhone1 = decodeIt($row['userPhone1']); } else { $userPhone1 = ''; }
	if ($row['userPhone2'] != '') { $userPhone2 = decodeIt($row['userPhone2']); } else { $userPhone2 = ''; }
	if ($row['userPhone3'] != '') { $userPhone3 = decodeIt($row['userPhone3']); } else { $userPhone3 = ''; }
	if ($row['userAddress1'] != '') { $userAddress1 = decodeIt($row['userAddress1']); } else { $userAddress1 = ''; }
	if ($row['userAddress2'] != '') { $userAddress2 = decodeIt($row['userAddress2']); } else { $userAddress2 = ''; }
	if ($row['userstate'] != '') { $userstate = clean($row['userstate']); } else { $userstate = '';  }
	if ($row['usercity'] != '') { $usercity = clean($row['usercity']); } else { $usercity = '';  }
	if ($row['userstreet'] != '') { $userstreet = clean($row['userstreet']); } else { $userstreet = '';  }
	if ($row['userpincode'] != '') { $userpincode = clean($row['userpincode']); } else { $userpincode = '';  }
	if ($row['lastVisited'] != '0000-00-00 00:00:00') { $lastVisited = '<br />'.dateTimeFormat($row['lastVisited']); } else { $lastVisited = '<span class="text-warning">'.$noneOption.'</span>'; }
	if ($row['isActive'] == '1') {
		$accStatus = '<span class="text-success">'.$activeOption.'</span>';
	} else {
		$accStatus = '<span class="text-danger">'.$inactiveOption.'</span>';
	}
	if ($row['terminationDate'] != '0000-00-00 00:00:00') {
		$terminationDate = dbDateFormat($row['terminationDate']);
		$isTerm = 'selected';
	} else {
		$terminationDate = '';
		$isTerm = '';
	}
	if ($row['isActive'] == '1') { $actSelected = 'selected'; } else { $actSelected = ''; }
	if ($row['managerId'] != '0') {
		$manager = '<br /><strong class="text-info">'.$managerText.': '.clean($row['managerName']).'</strong>';
		$origMngr = clean($row['managerName']);
	} else {
		$manager = '';
		$origMngr = $selectOption;
	}
	
	
	$getYear = date('Y');
	$thisMonth = date('F');

	
	$currDate 		= date('Y-m-d');
	$currMonth		= date('m');
	$weekNum 		= date('W', strtotime($currDate) + 60 * 60 * 24 );
	if ($currMonth == '12' && $weekNum == '01') { $getWeek = '52'; } else { $getWeek = $weekNum; }
	
	
	if ($set['weekStart'] == '0') {
		$wkDayStart = date("Y-m-d", strtotime($getYear."W".$getWeek."1"."-1 day"));
		$wkDayEnd = date("Y-m-d", strtotime($getYear."W".$getWeek."7"."-1 day"));
	} else {
		$wkDayStart = date("Y-m-d", strtotime($getYear."W".$getWeek."1"));
		$wkDayEnd = date("Y-m-d", strtotime($getYear."W".$getWeek."7"));
	}
	
	
	$wkDayStart1 = date('M j, Y',strtotime($wkDayStart));
	$wkDayEnd1 = date('M j, Y',strtotime($wkDayEnd));

	
	$totHr = "SELECT
				TIMEDIFF(endTime,startTime) AS diff
			FROM
				timelogs
			WHERE
				userId = ".$userId." AND
				(entryDate BETWEEN '".$wkDayStart."' AND '".$wkDayEnd."') AND
				endTime != '00:00:00'";
	$totHrres = mysqli_query($mysqli, $totHr) or die('-2'.mysqli_error());
	$totHrs = array();

	
	while ($thrs = mysqli_fetch_assoc($totHrres)) {
		$totHrs[] = $thrs['diff'];
	}

	
	$totalWeekHours = sumHours($totHrs);

	
	$check2 = "SELECT timeId FROM timelogs WHERE userId = ".$userId." AND weekNo = '".$getWeek."' AND clockYear = '".$getYear."' AND endTime = '0000-00-00 00:00:00' LIMIT 1";
	$check2res = mysqli_query($mysqli, $check2) or die('-3' . mysqli_error());
	$timeRunning = mysqli_num_rows($check2res);
	if ($timeRunning > 0) {
		$clockStatus = '<span class="text-success">'.$clockedInText.'</span>';
	} else {
		$clockStatus = '<span class="text-danger">'.$clockedOutText.'</span>';
	}

	$ustatename = "SELECT users.userstate AS ustatename3 FROM users WHERE userId = ".$userId." " ;
	$ustatename1 = mysqli_query($mysqli, $ustatename) or die('-31' . mysqli_error());
	$ustatename2 = mysqli_fetch_assoc($ustatename1);
	$ustatename3 = $ustatename2['ustatename3'];


	$userPage = 'true';
	$pageTitle = $viewUserPageTitle;
	$addCss = '<link rel="stylesheet" type="text/css" href="css/datetimepicker.css" />';
	$datePicker = 'true';
	$jsFile = 'viewUser';
	$jsFile = 'newUser';

	include 'includes/header.php';
?>
	<div class="container-fluid">
		<div class="container">
			<div class="row pageCont">
				<?php
					if ((checkArray('MNGUSR', $auths)) && $row['managerId'] == $tz_userId || $tz_superUser != '') {
						
						if ($userId == '1' && $tz_userId != '1') {
				?>
							<div class="col-md-12 pb-20">
								<div class="alertMsg danger">
									<div class="msgIcon pull-left">
										<i class="fa fa-ban"></i>
									</div>
									<?php echo $accessErrorMsg; ?>
								</div>
							</div>
				<?php
						} else {
				?>
							<div class="col-md-3 no-padding">
								<div class="sideBar pt-20">
									<?php if ($row['userAvatar'] == 'userAvatar.png') { ?>
										<img src="<?php echo $avatarDir.$row['userAvatar']; ?>" class="profile-img">
									<?php } else { ?>
										<img src="<?php echo $avatarDir.$row['userFolder'].'/'.$row['userAvatar']; ?>" class="profile-img">
									<?php } ?>
									<div class="profile-text">
										<h1 class="profile-name mt-0"><?php echo clean($row['userFirst']).' '.clean($row['userLast']); ?></h1>
										<span class="profile-title">
											<?php echo $userPosition; ?>
											<?php echo clean($row['userEmail']); ?><br />
											Emp Number: <?php echo SI; ?><?php echo $row['employeenumber']; ?><br>
											<?php echo $userPhone1; ?>
											<?php echo $manager; ?>
										</span>
									</div>

									<ul class="list-group">
										<li class="list-group-item task-lists"><strong><?php echo $accStatusLi; ?>:</strong> <?php echo $accStatus; ?></li>
										<li class="list-group-item task-lists"><strong><?php echo $hireDtTh; ?>:</strong> <?php echo dateFormat($row['userHireDate']); ?></li>
										<li class="list-group-item task-lists"><strong><?php echo $lastSignInTh; ?>:</strong> <?php echo $lastVisited; ?></li>
									</ul>
								</div>
							</div>
							<div class="col-md-9 no-padding">
								<div class="mainCont">
									<?php if ($msgBox) { echo $msgBox; } ?>
									<div class="tabbed-panel">
										<div class="tabbed-line">
											<ul class="nav nav-tabs ">
												<li class="<?php echo $accountTab; ?>"><a href="#account" data-toggle="tab"><?php echo $accountTabText; ?></a></li>
												<li class="<?php echo $mngrTab; ?>"><a href="#mngr" data-toggle="tab"><?php echo $managerText; ?></a></li>
												<li class="<?php echo $emailTab; ?>"><a href="#email" data-toggle="tab"><?php echo $emailVu; ?></a></li>
												<li class="<?php echo $passwordTab; ?>"><a href="#password" data-toggle="tab"><?php echo $passwordTabText; ?></a></li>
												<li class="<?php echo $avatarTab; ?>"><a href="#avatar" data-toggle="tab"><?php echo $avtrTab; ?></a></li>
												<li class="<?php echo $statusTab; ?>"><a href="#status" data-toggle="tab"><?php echo $statusText; ?></a></li>
												<li class="<?php echo $termTab; ?>"><a href="#term" data-toggle="tab"><?php echo $terminationTabText; ?></a></li>
												<li class="<?php echo $immigrationTab; ?>"><a href="#immigration" data-toggle="tab">Immigration Details</a></li>

												
											</ul>
											<div class="tab-content">


<div class="tab-pane <?php echo $accountTab; ?> pt-20" id="account">
													<h4 class="head-title mb-20"><?php echo $updUsrAccH4; ?></h4>
													<form action="" method="post" class="mb-20">
														<div class="row">

														<div class="col-md-2">
																<div class="form-group">
																	<label for="employeenumber"><?php echo $empNumField; ?></label>
																	<input type="text" class="form-control" name="employeenumber" id="employeenumber" required="required" value="<?php echo clean($row['employeenumber']); ?>" />
																</div>
															</div>


															<div class="col-md-4">
																<div class="form-group">
																	<label for="userFirst"><?php echo $firstNameField; ?></label>
																	<input type="text" class="form-control" name="userFirst" id="userFirst" required="required" value="<?php echo clean($row['userFirst']); ?>" />
																</div>
															</div>
															<div class="col-md-2">
																<div class="form-group">
																	<label for="userMiddleInt"><?php echo $miField; ?></label>
																	<input type="text" class="form-control" name="userMiddleInt" id="userMiddleInt" value="<?php echo clean($row['userMiddleInt']); ?>" />
																</div>
															</div>
															<div class="col-md-4">
																<div class="form-group">
																	<label for="userLast"><?php echo $lastNameField; ?></label>
																	<input type="text" class="form-control" name="userLast" id="userLast" required="required" value="<?php echo clean($row['userLast']); ?>" />
																</div>
															</div>
														</div>
														<div class="row">
															<div class="col-md-6">
																<div class="form-group">
																	<label for="userPhone1"><?php echo $primaryPhoneField; ?></label>
																	<input type="text" class="form-control" name="userPhone1" id="userPhone1" value="<?php echo $userPhone1; ?>" />
																</div>
															</div>
															<div class="col-md-6">
																<div class="form-group">
																	<label for="userPhone2"><?php echo $altPhoneField; ?></label>
																	<input type="text" class="form-control" name="userPhone2" id="userPhone2" value="<?php echo $userPhone2; ?>" />
																</div>
															</div>
															
														</div>
														
															<div class="row">
												<div class="hidden">
													<div class="form-group">
														<label for="userstate">State  (<?php echo $userstatename3; ?>) </label>

														<select name="userstate" class="states form-control" id="userstate">
															<option value="">Select State</option>
																	</select>

														<textarea class="form-control hidden" name="userstate1" id="userstate1" rows="1"><?php echo $userstate; ?></textarea>
													</div>
												</div>
												<div class="hidden">
													<div class="form-group ">
														<label for="usercity" style="width: 100%;">City (<?php echo $usercityname3; ?>)</label>

														<select   name="usercity" class="cities form-control" id="usercity">
															<option value="">Select City </option>
															</select>
														<textarea class="form-control hidden" name="usercity1" id="usercity1" rows="1"><?php echo $usercity; ?></textarea>
													</div>
												</div>
												<div class="col-md-12">
													<div class="form-group">
														<label for="userstreet">Home Address</label>
														<textarea class="form-control" name="userstreet" id="userstreet" rows="3"><?php echo $userstreet; ?></textarea>
													</div>
												</div>
												<div class="hidden">
													<div class="form-group">
														<label for="userpincode">Pincode</label>
														<textarea class="form-control" name="userpincode" id="userpincode" rows="1"><?php echo $userpincode; ?></textarea>
													</div>
												</div>
											</div>

														<div class="row">

														<div class="col-md-6">
										<div class="form-group">
											<label for="userPosition"><?php echo $positionTitleField; ?></label>
											<input type="text" class="form-control" name="userPosition" id="userPosition" value="<?php echo isset($row['userPosition']) ? $row['userPosition'] : ''; ?>" />
										</div>

									</div>


															<div class="col-md-6 hidden">
																<div class="form-group">
																	<label for="location"><?php echo $locationField; ?></label>
																	<input type="text" class="form-control" name="location" id="location" required="required" value="<?php echo clean($row['location']); ?>" />
																</div>
															</div>
															<div class="col-md-6">
										<div class="form-group">
											<label for="userHireDate"><?php echo $hireDateField; ?></label>
											<input type="text" class="form-control" name="userHireDate" id="userHireDate" value="<?php echo isset($row['userHireDate']) ? $row['userHireDate'] : ''; ?>" />
										</div>
									</div>
														</div>

<button type="input" name="submit" value="updAcc" class="btn btn-success btn-sm btn-icon"><i class="fa fa-check-square-o"></i> <?php echo $saveChangesBtn; ?></button>

													</form>
												</div>




												<div class="tab-pane <?php echo $mngrTab; ?> pt-20" id="mngr">
													<h4 class="head-title mb-20"><?php echo $assignUsrMngField; ?></h4>
													<form action="" method="post" class="mb-20">
														<div class="row">
															<div class="col-md-6">
																<div class="form-group">
																	<label for="managerId" class="control-label"><?php echo $managerText; ?></label>
																	<?php
																		$usrqry = "SELECT userId, userFirst, userLast FROM users WHERE isActive = 1 AND isAdmin = 1";
																		$usrres = mysqli_query($mysqli, $usrqry) or die('-5'.mysqli_error());
																	?>
																	<select class="form-control" name="managerId" id="managerId">
																		<option value="..."><?php echo $selectOption; ?></option>
																		<?php
																			while ($usr = mysqli_fetch_assoc($usrres)) {
																				echo '<option value="'.$usr['userId'].'">'.$usr['userFirst'].' '.$usr['userLast'].'</option>';
																			}
																		?>
																	</select>
																	<span class="help-block"><?php echo $assignUsrMngFieldHelp; ?></span>
																</div>
															</div>
														</div>
														<input type="hidden" id="origMgrId" value="<?php echo $origMngr; ?>" />
														<input type="hidden" name="usrsName" value="<?php echo clean($row['userFirst']).' '.clean($row['userLast']); ?>" />
														<button type="input" name="submit" value="updManager" class="btn btn-success btn-sm btn-icon"><i class="fa fa-check-square-o"></i> <?php echo $saveChangesBtn; ?></button>
													</form>
												</div>

												<div class="tab-pane <?php echo $emailTab; ?> pt-20" id="email">
													<h4 class="head-title mb-20"><?php echo $updUsrEmailH4; ?></h4>
													<form action="" method="post" class="mb-20">
														<div class="form-group">
															<label for="currentEmail"><?php echo $currEmailField; ?></label>
															<input type="text" class="form-control" disabled="" value="<?php echo clean($row['userEmail']); ?>" />
														</div>
														<div class="row">
															<div class="col-md-6">
																<div class="form-group">
																	<label for="userEmail"><?php echo $newEmailField; ?></label>
																	<input type="email" class="form-control" name="userEmail" id="userEmail" required="required" value="<?php echo isset($_POST['userEmail']) ? $_POST['userEmail'] : ''; ?>" />
																	<span class="help-block"><?php echo $newEmailFieldHelp; ?></span>
																</div>
															</div>
															<div class="col-md-6">
																<div class="form-group">
																	<label for="userEmail_r"><?php echo $repeatNewEmlField; ?></label>
																	<input type="email" class="form-control" name="userEmail_r" id="userEmail_r" required="required" value="<?php echo isset($_POST['userEmail_r']) ? $_POST['userEmail_r'] : ''; ?>" />
																	<span class="help-block"><?php echo $repeatNewEmlFieldHelp; ?></span>
																</div>
															</div>
														</div>
														<input type="hidden" name="usrsName" value="<?php echo clean($row['userFirst']).' '.clean($row['userLast']); ?>" />
														<button type="input" name="submit" value="updEmail" class="btn btn-success btn-sm btn-icon"><i class="fa fa-check-square-o"></i> <?php echo $saveChangesBtn; ?></button>
													</form>
												</div>

												<div class="tab-pane <?php echo $passwordTab; ?> pt-20" id="password">
													<h4 class="head-title mb-20"><?php echo $changeUsrPassH4; ?></h4>
													<form action="" method="post" class="mb-20">
														<div class="row">
															<div class="col-md-6">
																<div class="form-group">
																	<label for="password1"><?php echo $accountPassField; ?></label>
																	<div class="input-group">
																		<input type="password" class="form-control" required="required" name="password1" id="password1" value="" />
																		<span class="input-group-addon"><a href="" id="generate" data-toggle="tooltip" data-placement="top" title="Generate Password"><i class="fa fa-key"></i></a></span>
																	</div>
																	<span class="help-block">
																		<a href="" id="showIt" class="btn btn-warning btn-xs"><?php echo $accountPassFieldShow; ?></a>
																		<a href="" id="hideIt" class="btn btn-info btn-xs"><?php echo $accountPassFieldHide; ?></a>
																	</span>
																</div>
															</div>
															<div class="col-md-6">
																<div class="form-group">
																	<label for="password2"><?php echo $rptAccPassField; ?></label>
																	<input type="password" class="form-control" required="required" name="password2" id="password2" value="" />
																	<span class="help-block"><?php echo $rptAccPassFieldHelp; ?></span>
																</div>
															</div>
														</div>
														<input type="hidden" name="usrsName" value="<?php echo clean($row['userFirst']).' '.clean($row['userLast']); ?>" />
														<button type="input" name="submit" value="updPassword" class="btn btn-success btn-sm btn-icon"><i class="fa fa-check-square-o"></i> <?php echo $saveChangesBtn; ?></button>
													</form>
												</div>

												<div class="tab-pane <?php echo $avatarTab; ?> pt-20" id="avatar">
													<h4 class="head-title mb-20"><?php echo $mngUsrAvatarH4; ?></h4>
													<?php if ($row['userAvatar'] == 'userDefault.png') { ?>
														<p><?php echo $mngUsrAvatarQuip1; ?></p>
													<?php } else { ?>
														<p><?php echo $mngUsrAvatarQuip2; ?></p>
														<a data-toggle="modal" href="#deleteAvatar" class="btn btn-warning btn-icon mt-20" data-dismiss="modal"><i class="fa fa-ban"></i> <?php echo $remAvatarBtn; ?></a>

														<div id="deleteAvatar" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
															<div class="modal-dialog">
																<div class="modal-content">
																	<form action="" method="post">
																		<div class="modal-body">
																			<p class="lead mt-0"><?php echo $remAvatarConf; ?> <?php echo clean($row['userFirst']).' '.clean($row['userLast']); ?>?</p>
																		</div>
																		<div class="modal-footer">
																			<input type="hidden" name="userFolder" value="<?php echo clean($row['userFolder']); ?>" />
																			<input type="hidden" name="userAvatar" value="<?php echo clean($row['userAvatar']); ?>" />
																			<input type="hidden" name="usrsName" value="<?php echo clean($row['userFirst']).' '.clean($row['userLast']); ?>" />
																			<button type="input" name="submit" value="deleteAvatar" class="btn btn-success btn-icon"><i class="fa fa-check-square-o"></i> <?php echo $yesBtn; ?></button>
																			<button type="button" class="btn btn-default btn-icon" data-dismiss="modal"><i class="fa fa-times-circle-o"></i> <?php echo $cancelBtn; ?></button>
																		</div>
																	</form>
																</div>
															</div>
														</div>
													<?php } ?>
												</div>

												<div class="tab-pane <?php echo $statusTab; ?> pt-20" id="status">
													<h4 class="head-title mb-20"><?php echo $mngUsrStatusH4; ?></h4>
													<form action="" method="post" class="mb-20">
														<div class="row">
															<div class="col-md-6">
																<div class="form-group">
																	<label for="isActive" class="control-label"><?php echo $accStatusLi; ?></label>
																	<select class="form-control" name="isActive" id="isActive">
																		<option value="0"><?php echo $inactiveOption; ?></option>
																		<option value="1" <?php echo $actSelected; ?>><?php echo $activeOption; ?></option>
																	</select>
																	<span class="help-block"><?php echo $accStatusHelp; ?></span>
																</div>
															</div>
														</div>
														<input type="hidden" name="usrsName" value="<?php echo clean($row['userFirst']).' '.clean($row['userLast']); ?>" />
														<button type="input" name="submit" value="updStatus" class="btn btn-success btn-sm btn-icon"><i class="fa fa-check-square-o"></i> <?php echo $saveChangesBtn; ?></button>
													</form>
												</div>

												<div class="tab-pane <?php echo $termTab; ?> pt-20" id="term">
													<h4 class="head-title mb-20"><?php echo $termUsrH4; ?></h4>
													<p><?php echo $termUsrQuip; ?></p>
													<form action="" method="post" class="mt-20 mb-20">
														<div class="row">
															<div class="col-md-6">
																<div class="form-group">
																	<label for="terminate" class="control-label"><?php echo $termUserField; ?></label>
																	<select class="form-control" name="terminate" id="terminate">
																		<option value="0"><?php echo $noBtn; ?></option>
																		<option value="1" <?php echo $actSelected; ?>><?php echo $yesBtn; ?></option>
																	</select>
																	<span class="help-block"><?php echo $termUserFieldHelp; ?></span>
																</div>
															</div>
															<div class="col-md-6">
																<div class="form-group">
																	<label for="terminationDate"><?php echo $termDateField; ?></label>
																	<input type="text" class="form-control" name="terminationDate" id="terminationDate" value="<?php echo $terminationDate; ?>" />
																	<span class="help-block"><?php echo $termDateFieldHelp; ?></span>
																</div>
															</div>
														</div>
														<div class="form-group">
															<label for="terminationReason"><?php echo $termReasonField; ?></label>
															<input type="text" class="form-control" name="terminationReason" id="terminationReason" value="<?php echo clean($row['terminationReason']); ?>" />
															<span class="help-block"><?php echo $termReasonFieldHelp; ?></span>
														</div>
														<input type="hidden" name="usrsName" value="<?php echo clean($row['userFirst']).' '.clean($row['userLast']); ?>" />
														<button type="input" name="submit" value="updTerm" class="btn btn-success btn-sm btn-icon"><i class="fa fa-check-square-o"></i> <?php echo $saveChangesBtn; ?></button>
													</form>
												</div>

																					<div class="tab-pane <?php echo $immigrationTab; ?>" id="immigration">
										<h4 class="head-title mt-20">Update Immigration Details</h4>
										<form action="" method="post" class="mb-20">

											<div class="row">
												<div class="col-md-4">
													<div class="form-group">
														<label for="location">Work Location</label>
														<input type="text" class="form-control" name="location" id="location" value="<?php echo clean($row['location']); ?>" />


													</div>
												</div>

												<div class="col-md-4">
													<div class="form-group">
														<label for="visastatus">Visa Status</label>

														<input type="text" class="form-control" name="visastatus" id="userHireDate" value="<?php echo clean($row['visastatus']); ?>" />
													</div>
												</div>

												<div class="col-md-4">
													<div class="form-group">
														<label for="visaexpiry">Visa Expiry Date</label>

														<input type="text" class="form-control" name="visaexpiry" id="visaexpitydate" value="<?php echo clean($row['visaexpiry']); ?>" />
													</div>
												</div>
											</div>
											<button type="input" name="submit" value="immigInfo" class="btn btn-success btn-sm btn-icon hidden"><i class="fa fa-check-square-o"></i> <?php echo $saveChangesBtn; ?></button>
										</form>
									</div>
											</div>
										</div>
									</div>
								</div>
							</div>
					<?php } ?>
				<?php } else { ?>
					<div class="col-md-12 pb-20">
						<div class="alertMsg danger">
							<div class="msgIcon pull-left">
								<i class="fa fa-ban"></i>
							</div>
							<?php echo $accessErrorMsg; ?>
						</div>
					</div>
				<?php } ?>
			</div>
		</div>

	</div>