<?php
	$actIp = $_SERVER['REMOTE_ADDR'];

	$avatarTypes = $set['avatarTypesAllowed'];
	$avatarTypesAllowed = preg_replace('/,/', ', ', $avatarTypes);

	$personalTab = 'active';
	$emailTab = $passTab = $avatarTab = '';

	if (isset($_POST['submit']) && $_POST['submit'] == 'persInfo') {

		if($_POST['userFirst'] == '') {
			$msgBox = alertBox($firstNameReq, "<i class='fa fa-times-circle'></i>", "danger");
		}else {
			$userFirst = htmlspecialchars($_POST['userFirst']);
			$userMiddleInt = htmlspecialchars($_POST['userMiddleInt']);
			$userLast = htmlspecialchars($_POST['userLast']);
			$userPhone1 = encodeIt($_POST['userPhone1']);
			$userPhone2 = encodeIt($_POST['userPhone2']);
			$userPhone3 = encodeIt($_POST['userPhone3']);
			$userAddress1 = encodeIt($_POST['userAddress1']);
			$userAddress2 = encodeIt($_POST['userAddress2']);
			$userpincode = htmlspecialchars($_POST['userpincode']);
			$userstreet = htmlspecialchars($_POST['userstreet']);
			$userstatee = htmlspecialchars($_POST['userstate']);
			$usercityy = htmlspecialchars($_POST['usercity']);




			if ($_POST['location'] == "") {
				$location = 'Alpharetta, GA';
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
										userPhone3 = ?,
										userAddress1 = ?,
										userAddress2 = ?,
										userstate = ?,
										usercity = ?,
										userpincode = ?,
										userstreet = ?,
										location = ?
									WHERE
										userId = ?"
			);
			$stmt->bind_param('ssssssssssssss',
									$userFirst,
									$userMiddleInt,
									$userLast,
									$userPhone1,
									$userPhone2,
									$userPhone3,
									$userAddress1,
									$userAddress2,
									$userstatee,
									$usercityy,
									$userpincode,
									$userstreet,
									$location,
									$tz_userId
			);
			$stmt->execute();
			$stmt->close();

			$activityType = '8';
			$activityTitle = $tz_userFull.' '.$persInfoUpdatedAct;
			updateActivity($tz_userId,$activityType,$activityTitle);

			$msgBox = alertBox($persInfoUpdatedMsg, "<i class='fa fa-check-square'></i>", "success");

			$_SESSION['tz']['userFirst'] = $userFirst;
			$_SESSION['tz']['userLast']  = $userLast;
			$_SESSION['tz']['employeenumber']  = $employeenumber;
			$_SESSION['tz']['location']  = $location;

			$tz_userFirst = $userFirst;
			$tz_userLast  = $userLast;
			$tz_employeenumber  = $employeenumber;
			$tz_userFull  = $userFirst.' '.$userLast;
			$tz_userLoc	  = $location;
		}
    }


    if (isset($_POST['submit']) && $_POST['submit'] == 'immigInfo') {


			$visastatus = htmlspecialchars($_POST['visastatus']);
			$visaexpiry = htmlspecialchars($_POST['visaexpiry']);
			

			if ($_POST['location'] == "") {
				$location = 'Alpharetta, GA';
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


	if (isset($_POST['submit']) && $_POST['submit'] == 'updEmail') {

		$currentPass = encodeIt($_POST['currentpass']);
		 if($_POST['userEmail'] != $_POST['userEmail_r']) {
			$msgBox = alertBox($emailsNoMatchMsg, "<i class='fa fa-times-circle'></i>", "danger");
			$emailTab = 'active';
			$personalTab = $passTab = $avatarTab = '';
		} else if($_POST['password'] != $_POST['password_r']) {
			$msgBox = alertBox($newPassNoMatchErr, "<i class='fa fa-times-circle'></i>", "danger");
			$emailTab = 'active';
			$personalTab = $passTab = $avatarTab = '';
		} else {

			if(isset($_POST['userEmail']) && $_POST['userEmail'] != "") {
				$userEmail = htmlspecialchars($_POST['userEmail']);
			} else {
				$userEmail = $tz_userEmail;
			}

			
			if(isset($_POST['password']) && $_POST['password'] != "") {
				$password = encodeIt($_POST['password']);
			} else {
				$password = $_POST['passwordOld'];
			}

			$stmt = $mysqli->prepare("UPDATE
										users
									SET
										userEmail = ?,
										password = ?
									WHERE
										userId = ?"
			);
			$stmt->bind_param('sss',
									$userEmail,
									$password,
									$tz_userId
			);
			$stmt->execute();
			$stmt->close();

			$activityType = '8';
			$activityTitle = $tz_userFull.' '.$emailAddyUpdatedAct;
			updateActivity($tz_userId,$activityType,$activityTitle);

			 if(isset($_POST['userEmail']) && $_POST['userEmail'] != "") {
				$msgBox = alertBox($emailAddyUpdatedMsg, "<i class='fa fa-check-square'></i>", "success");
			} else if(isset($_POST['password']) && $_POST['password'] != "") {
					$msgBox = alertBox($updNewPassMsg, "<i class='fa fa-check-square'></i>", "success");
			} else{
				$msgBox = alertBox($updNewEmailPassMsg, "<i class='fa fa-check-square'></i>", "success");
			}


			$_SESSION['tz']['userEmail'] = $userEmail;
			$tz_userEmail = $userEmail;

			$emailTab = 'active';
			$personalTab = $passTab = $avatarTab = '';
		}
    }

	if (isset($_POST['submit']) && $_POST['submit'] == 'updPassword') {
		$currentPass = encodeIt($_POST['currentpass']);
	
		if($_POST['currentpass'] == '') {
			$msgBox = alertBox($currAccPassReq, "<i class='fa fa-times-circle'></i>", "danger");
			$passTab = 'active';
			$personalTab = $emailTab = $avatarTab = '';
		} else if ($currentPass != $_POST['passwordOld']) {
			$msgBox = alertBox($currAccPasswordErr, "<i class='fa fa-warning'></i>", "warning");
			$passTab = 'active';
			$personalTab = $emailTab = $avatarTab = '';
		} else if($_POST['password'] == '') {
			$msgBox = alertBox($newPassReq, "<i class='fa fa-times-circle'></i>", "danger");
			$passTab = 'active';
			$personalTab = $emailTab = $avatarTab = '';
		} else if($_POST['password_r'] == '') {
			$msgBox = alertBox($repNewPassRe, "<i class='fa fa-times-circle'></i>", "danger");
			$passTab = 'active';
			$personalTab = $emailTab = $avatarTab = '';
		} else if($_POST['password'] != $_POST['password_r']) {
			$msgBox = alertBox($newPassNoMatchErr, "<i class='fa fa-times-circle'></i>", "danger");
			$passTab = 'active';
			$personalTab = $emailTab = $avatarTab = '';
		} else {
			if(isset($_POST['password']) && $_POST['password'] != "") {
				$password = encodeIt($_POST['password']);
			} else {
				$password = $_POST['passwordOld'];
			}

			$stmt = $mysqli->prepare("UPDATE
										users
									SET
										password = ?
									WHERE
										userId = ?"
			);
			$stmt->bind_param('ss',
									$password,
									$tz_userId
			);
			$stmt->execute();
			$stmt->close();

			$activityType = '8';
			$activityTitle = $tz_userFull.' '.$updNewPassAct;
			updateActivity($tz_userId,$activityType,$activityTitle);

			$msgBox = alertBox($updNewPassMsg, "<i class='fa fa-check-square'></i>", "success");

			$passTab = 'active';
			$personalTab = $emailTab = $avatarTab = '';
		}
    }

	if (isset($_POST['submit']) && $_POST['submit'] == 'newAvatar') {
	
		$fileExt = $set['avatarTypesAllowed'];
		$allowed = preg_replace('/,/', ', ', $fileExt);
		$ftypes = array($fileExt);
		$ftypes_data = explode( ',', $fileExt );

		$ext = substr(strrchr(basename($_FILES['file']['name']), '.'), 1);
		if (!in_array($ext, $ftypes_data)) {
			$msgBox = alertBox($avatarImgErr, "<i class='fa fa-times-circle'></i>", "danger");
		} else {
			$avatarDir = $set['avatarFolder'];
			$userFolder = htmlspecialchars($_POST['userFolder']);


			$avatarName = clean($tz_userFull);

			$newName = str_replace(' ', '-', $avatarName);
			$fileName = strtolower($newName);

			$randomHash = uniqid(rand());
			$randHash = substr($randomHash, 0, 8);

			$fullName = $fileName.'-'.$randHash;

			$avatarUrl = basename($_FILES['file']['name']);

			$extension = explode(".", $avatarUrl);
			$extension = end($extension);

			$newAvatarName = $fullName.'.'.$extension;
			$movePath = $avatarDir.$userFolder.'/'.$newAvatarName;

			$stmt = $mysqli->prepare("UPDATE
										users
									SET
										userAvatar = ?
									WHERE
										userId = ?"
			);
			$stmt->bind_param('ss',
							   $newAvatarName,
							   $tz_userId
			);

			if (move_uploaded_file($_FILES['file']['tmp_name'], $movePath)) {
				$stmt->execute();
				$stmt->close();

				$activityType = '8';
				$activityTitle = $tz_userFull.' '.$uplAvatarAct;
				updateActivity($tz_userId,$activityType,$activityTitle);

				$msgBox = alertBox($uplAvatarMsg, "<i class='fa fa-check-square'></i>", "success");
			} else {
				$activityType = '0';
				$activityTitle = $tz_userFull.' '.$uplAvatarErrAct;
				updateActivity($tz_userId,$activityType,$activityTitle);

				$msgBox = alertBox($uplAvatarErrMsg, "<i class='fa fa-check-square'></i>", "success");
			}
		}

		$avatarTab = 'active';
		$personalTab = $emailTab = $passTab = '';
	}

	if (isset($_POST['submit']) && $_POST['submit'] == 'deleteAvatar') {
		$sql = "SELECT userAvatar FROM users WHERE userId = ".$tz_userId;
		$result = mysqli_query($mysqli, $sql) or die('-1'.mysqli_error());
		$r = mysqli_fetch_assoc($result);
		$avatarName = $r['userAvatar'];

		$avatarDir = $set['avatarFolder'];
		$userFolder = htmlspecialchars($_POST['userFolder']);
		$filePath = $avatarDir.$userFolder.'/'.$avatarName;

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
							   $tz_userId);
			$stmt->execute();
			$stmt->close();

			$activityType = '8';
			$activityTitle = $tz_userFull.' '.$delAvatarAct;
			updateActivity($tz_userId,$activityType,$activityTitle);

			$msgBox = alertBox($delAvatarMsg, "<i class='fa fa-check-square'></i>", "success");
		} else {
			$activityType = '0';
			$activityTitle = $tz_userFull.' '.$delAvatarErrAct;
			updateActivity($tz_userId,$activityType,$activityTitle);

			$msgBox = alertBox($delAvatarErrMsg, "<i class='fa fa-check-square'></i>", "success");
		}

		$avatarTab = 'active';
		$personalTab = $emailTab = $passTab = '';
	}

	$qry = "SELECT * FROM users WHERE userId = ".$tz_userId;
	$res = mysqli_query($mysqli, $qry) or die('-2' . mysqli_error());
	$row = mysqli_fetch_assoc($res);


	if ($row['userPosition'] != '') { $userPosition = clean($row['userPosition']).'<br />'; } else { $userPosition = '';  }
	if ($row['userPhone1'] != '') { $userPhone1 = decodeIt($row['userPhone1']); } else { $userPhone1 = '';  }
	if ($row['userPhone2'] != '') { $userPhone2 = decodeIt($row['userPhone2']); } else { $userPhone2 = '';  }
	if ($row['userPhone3'] != '') { $userPhone3 = decodeIt($row['userPhone3']); } else { $userPhone3 = '';  }
	if ($row['userAddress1'] != '') { $userAddress1 = decodeIt($row['userAddress1']); } else { $userAddress1 = '';  }
	if ($row['userAddress2'] != '') { $userAddress2 = decodeIt($row['userAddress2']); } else { $userAddress2 = '';  }
if ($row['userstate'] != '') { $userstate = clean($row['userstate']); } else { $userstate = '';  }
	if ($row['usercity'] != '') { $usercity = clean($row['usercity']); } else { $usercity = '';  }
	if ($row['userstreet'] != '') { $userstreet = clean($row['userstreet']); } else { $userstreet = '';  }
	if ($row['userpincode'] != '') { $userpincode = clean($row['userpincode']); } else { $userpincode = '';  }

	

	$userstatename = "SELECT states.name AS userstatename3 FROM states WHERE id = '".$userstate."' " ;
	$userstatename1 = mysqli_query($mysqli, $userstatename) or die('-31' . mysqli_error());
	$userstatename2 = mysqli_fetch_assoc($userstatename1);
	$userstatename3 = $userstatename2['userstatename3'];

	$usercityname = "SELECT cities.name AS usercityname3 FROM cities WHERE id = '".$usercity."' " ;
	$usercityname1 = mysqli_query($mysqli, $usercityname) or die('-31' . mysqli_error());
	$usercityname2 = mysqli_fetch_assoc($usercityname1);
	$usercityname3 = $usercityname2['usercityname3'];




	$qry = "SELECT * FROM users WHERE userId = ".$tz_userId;
	$res = mysqli_query($mysqli, $qry) or die('-2' . mysqli_error());
	$row = mysqli_fetch_assoc($res);

						if ($row['visastatus'] == 'H1B') {
								$h1bhidden = 'selected/';
					}
					else if ($row['visastatus'] == 'GC') {
						$gchidden = 'selected/';
					}
					else if ($row['visastatus'] == 'EAD') {
						$eadhidden = 'selected/';
					}
					else if ($row['visastatus'] == 'L2 EAD') {
						$l2eadhidden = 'selected/';
					}
					else if ($row['visastatus'] == 'Citizen') {
						$citizenhidden = 'selected/';
					}		


	$sql = "SELECT
				users.managerId,
				(SELECT CONCAT(usr.userFirst,' ',usr.userLast) FROM users AS usr WHERE usr.userId = users.managerId) AS managerName
			FROM
				users
			WHERE
				users.userId = ".$tz_userId;
	$result = mysqli_query($mysqli, $sql) or die('-3' . mysqli_error());
	$rows = mysqli_fetch_assoc($result);

	if ($rows['managerId'] != '0') { $myManager = '<br /><strong class="text-info">'.$managerText.': '.clean($rows['managerName']).'</strong>'; } else { $myManager = ''; }


		

	$datePicker = 'true';
	$jsFile = 'newUser';
	$acctPage = 'true';
	$pageTitle = $myProfilePageTitle;

	include 'includes/header.php';
?>
	<div class="container-fluid">
		<div class="container">
			<div class="row pageCont">
				<div class="col-md-3 no-padding">



					<div class="sideBar pt-20">
						<?php if ($row['userAvatar'] == 'userAvatar.png') { ?>
							<img src="<?php echo $avatarDir.$row['userAvatar']; ?>" class="profile-img">
						<?php } else { ?>
							<img src="<?php echo $avatarDir.$row['userFolder'].'/'.$row['userAvatar']; ?>" class="profile-img">
						<?php } ?>
						<div class="profile-text">
							<h1 class="profile-name mt-0" style=" border-bottom: 0px;"><?php echo $tz_userFirst.' '.$tz_userLast; ?></h1>
							<span class="profile-title">
								<?php echo $userPosition; ?>
								<?php echo clean($row['userEmail']); ?><br />
								Emp Number: <?php echo SI; ?><?php echo $row['employeenumber']; ?><br>
								<?php echo $userPhone1; ?>
								<?php echo $myManager; ?>
							</span>
						</div>
					</div>
				</div>
				<div class="col-md-9 no-padding">
					<div class="mainCont pt-20">
						<?php if ($msgBox) { echo $msgBox; } ?>

						<input type="hidden" id="langCode" value="<?php echo $calLanguage; ?>" />
						<input type="hidden" id="theUser" value="<?php echo $tz_userId; ?>" />

						<div class="tabbed-panel mt-0">
							<div class="tabbed-line">
								<ul class="nav nav-tabs ">
									<li class="<?php echo $personalTab; ?>"><a href="#personal" data-toggle="tab"><?php echo $persInfoTab; ?></a></li>
									<li class="<?php echo $emailTab; ?>"><a href="#accEmail" data-toggle="tab"><?php echo $emailTh; ?></a></li>
									<li class="hidden"><a href="#accPass" data-toggle="tab"><?php echo $passwordTabText; ?></a></li>
									<li class="<?php echo $avatarTab; ?>"><a href="#accAvatar" data-toggle="tab"><?php echo $avtrTab; ?></a></li>
									<li class="<?php echo $immigrationTab; ?>"><a href="#immigration" data-toggle="tab">Immigration</a></li>

									
								</ul>
								<div class="tab-content">
									<div class="tab-pane <?php echo $personalTab; ?>" id="personal">
										<h4 class="head-title mt-20"><?php echo $updatePersInfoH4; ?></h4>
										<form action="" method="post" class="mb-20">
											<div class="row">
											<div class="hide">
													<div class="form-group">
														<label for="employeenumber">Emp Number</label>
														<input type="text" class="form-control" name="employeenumber" id="employeenumber" value="<?php echo SI; ?><?php echo clean($row['employeenumber']); ?>" readonly />
													</div>
												</div>
												<div class="col-md-5">
													<div class="form-group">
														<label for="userFirst"><?php echo $firstNameField; ?></label>
														<input type="text" class="form-control" name="userFirst" id="userFirst"  value="<?php echo clean($row['userFirst']); ?>" />
													</div>
												</div>
												<div class="col-md-2">
													<div class="form-group">
														<label for="userMiddleInt">Initial</label>
														<input type="text" class="form-control" name="userMiddleInt" id="userMiddleInt" value="<?php echo clean($row['userMiddleInt']); ?>" />
													</div>
												</div>
												<div class="col-md-5" >
													<div class="form-group">
														<label for="userLast"><?php echo $lastNameField; ?></label>
														<input type="text" class="form-control" name="userLast" id="userLast"  value="<?php echo clean($row['userLast']); ?>" />
													</div>
												</div>
												
											</div>
											<div class="row">
												<div class="col-md-4">
													<div class="form-group">
														<label for="userPhone1"><?php echo $primaryPhoneField; ?></label>
														<input type="text" class="form-control" name="userPhone1" id="userPhone1"  value="<?php echo $userPhone1; ?>" />
													</div>
												</div>
												<div class="col-md-4">
													<div class="form-group">
														<label for="userPhone2"><?php echo $altPhoneField; ?></label>
														<input type="text" class="form-control" name="userPhone2" id="userPhone2" value="<?php echo $userPhone2; ?>" />
													</div>
												</div>
												<div class="col-md-4">
													
												</div>
											</div>
											<div class="row">
												<div class="col-md-6 hidden">
													<div class="form-group">
														<label for="userAddress1"><?php echo $mailAddyField; ?></label>
														<textarea class="form-control" name="userAddress1" id="userAddress1" rows="3"><?php echo $userAddress1; ?></textarea>
													</div>
												</div>
												<div class="col-md-6 hidden">
													<div class="form-group">
														<label for="userAddress2"><?php echo $altAddyField; ?></label>
														<textarea class="form-control" name="userAddress2" id="userAddress2" rows="3"><?php echo $userAddress2; ?></textarea>
													</div>
												</div>
											</div>

											<div class="row">
												<div class="hidden">
													<div class="form-group">
														<label for="userstate">State (<?php echo $userstatename3; ?>) </label>

														<select name="userstate" class="states form-control" id="userstate">
															<option value="<?php echo $userstate; ?>">Select State</option>
																	</select>

														<textarea class="form-control hidden" name="userstate1" id="userstate1" rows="1"><?php echo $userstate; ?></textarea>
													</div>
												</div>
												<div class="hidden">
													<div class="form-group ">
														<label for="usercity" style="width: 100%;">City (<?php echo $usercityname3; ?>)</label>

														<select   name="usercity" class="cities form-control" id="usercity">
															<option value="<?php echo $usercity; ?>">Select City </option>
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
														<textarea class="form-control" name="userpincode" id="userpincode" rows="2"><?php echo $userpincode; ?></textarea>
													</div>
												</div>
											</div>




											<div class="row hidden">
												<div class="col-md-6">
													<div class="form-group">
														<label for="location">Work Location</label>
														<input type="text" class="form-control" name="location" id="location" value="<?php echo clean($row['location']); ?>" />
													</div>
												</div>
											</div>
											<button type="input" name="submit" value="persInfo" class="btn btn-success btn-sm btn-icon"><i class="fa fa-check-square-o"></i> <?php echo $saveChangesBtn; ?></button>
										</form>
									</div>
									<div class="tab-pane <?php echo $emailTab; ?>" id="accEmail">
										<h4 class="head-title mt-20"><?php echo $updAccEmailH4; ?></h4>
										<form action="" method="post" class="mb-20">
											<div class="form-group">
												<label for="currentEmail"><?php echo $currEmailField; ?></label>
												<input type="text" class="form-control" disabled="" value="<?php echo $tz_userEmail; ?>" />
											</div>
											<div class="row">
												<div class="col-md-6">
													<div class="form-group">
														<label for="userEmail"><?php echo $newEmailField; ?></label>
														<input type="email" class="form-control" name="userEmail" id="userEmail" value="<?php echo isset($_POST['userEmail']) ? $_POST['userEmail'] : ''; ?>" />
														<span class="help-block"><?php echo $newEmailFieldHelp; ?></span>
													</div>
												</div>
												<div class="col-md-6">
													<div class="form-group">
														<label for="userEmail_r"><?php echo $repeatNewEmlField; ?></label>
														<input type="email" class="form-control" name="userEmail_r" id="userEmail_r" value="<?php echo isset($_POST['userEmail_r']) ? $_POST['userEmail_r'] : ''; ?>" />
														<span class="help-block"><?php echo $repeatNewEmlFieldHelp; ?></span>
													</div>
												</div>
											</div>

											<div class="row">
												<div class="col-md-6">
													<div class="form-group">
														<label for="password"><?php echo $newPassField; ?></label>
														<input type="password" class="form-control" name="password" id="password" value="" />
														<span class="help-block"><?php echo $newPassFieldHelp; ?></span>
													</div>
												</div>
												<div class="col-md-6">
													<div class="form-group">
														<label for="password_r"><?php echo $repNewPassField; ?></label>
														<input type="password" class="form-control" name="password_r" id="password_r" value="" />
														<span class="help-block"><?php echo $repNewPassFieldHelp; ?></span>
													</div>
												</div>
											</div>
											<input type="hidden" name="passwordOld" value="<?php echo $row['password']; ?>" />
											<button type="input" name="submit" value="updEmail" class="btn btn-success btn-sm btn-icon"><i class="fa fa-check-square-o"></i> <?php echo $saveChangesBtn; ?></button>
										</form>
									</div>
									<div class="hidden" id="accPass">
										<h4 class="head-title mt-20"><?php echo $changeAccPassH4; ?></h4>
										<form action="" method="post" class="mb-20">
											<div class="form-group">
												<label for="currentpass"><?php echo $currPassField; ?></label>
												<input type="password" class="form-control" name="currentpass" id="currentpass" required="required" value="" />
												<span class="help-block"><?php echo $currPassFieldHelp; ?></span>
											</div>
											<div class="row">
												<div class="col-md-6">
													<div class="form-group">
														<label for="password"><?php echo $newPassField; ?></label>
														<input type="password" class="form-control" name="password" id="password" required="required" value="" />
														<span class="help-block"><?php echo $newPassFieldHelp; ?></span>
													</div>
												</div>
												<div class="col-md-6">
													<div class="form-group">
														<label for="password_r"><?php echo $repNewPassField; ?></label>
														<input type="password" class="form-control" name="password_r" id="password_r" required="required" value="" />
														<span class="help-block"><?php echo $repNewPassFieldHelp; ?></span>
													</div>
												</div>
											</div>
											<input type="hidden" name="passwordOld" value="<?php echo $row['password']; ?>" />
											<button type="input" name="submit" value="updPassword" class="btn btn-success btn-sm btn-icon"><i class="fa fa-check-square-o"></i> <?php echo $saveChangesBtn; ?></button>
										</form>
									</div>
									<div class="tab-pane <?php echo $avatarTab; ?>" id="accAvatar">
										<h4 class="head-title mt-20"><?php echo $changeAccAvatarH4; ?></h4>
										<p>
											<?php echo $changeAccAvatarQuip1; ?><br />
											<?php echo $changeAccAvatarQuip2; ?> <?php echo $avatarTypesAllowed; ?>
										</p>
										<hr />
										<?php if ($row['userAvatar'] == 'userAvatar.png') { ?>
											<form enctype="multipart/form-data" action="" method="post" class="mb-20">
												<div class="form-group">
													<label for="file"><?php echo $selAvatarField; ?></label>
													<input type="file" id="file" name="file" required="required" />
												</div>
												<input type="hidden" name="userFolder" value="<?php echo $row['userFolder']; ?>" />
												<button type="input" name="submit" value="newAvatar" class="btn btn-success btn-sm btn-icon"><i class="fa fa-check-square-o"></i> <?php echo $uplAvatarBtn; ?></button>
											</form>
										<?php } else { ?>
											<p class="mb-20">
												<?php echo $remAvatarQuip1; ?><br />
												<?php echo $remAvatarQuip2; ?>
											</p>
											<a data-toggle="modal" href="#deleteAvatar" class="btn btn-success btn-sm btn-icon mb-20" data-dismiss="modal"><i class="fa fa-ban"></i> <?php echo $remAvatarLink; ?></a>

											<div id="deleteAvatar" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
												<div class="modal-dialog">
													<div class="modal-content">
														<form action="" method="post">
															<div class="modal-body">
																<p class="lead mt-0"><?php echo $remAvatarConf; ?></p>
															</div>
															<div class="modal-footer">
																<input type="hidden" name="userFolder" value="<?php echo $row['userFolder']; ?>" />
																<button type="button" class="btn btn-default btn-sm btn-icon" data-dismiss="modal"><i class="fa fa-times-circle-o"></i> <?php echo $cancelBtn; ?></button>
																<button type="input" name="submit" value="deleteAvatar" class="btn btn-success btn-sm btn-icon"><i class="fa fa-check-square-o"></i> <?php echo $yesBtn; ?></button>
															</div>
														</form>
													</div>
												</div>
											</div>
										<?php } ?>
									</div>
									<div class="tab-pane <?php echo $immigrationTab; ?>" id="immigration">
										<h4 class="head-title mt-20">Update Immigration Details</h4>
										<form action="" method="post" class="mb-20">

											<div class="row">

											<div class="col-md-12">
													<div class="form-group">
														<label for="location">Work Location</label>
														<input type="text" class="form-control" name="location" id="location" value="<?php echo clean($row['location']); ?>" />


													</div>
												</div>

												
												<div class="col-md-6">
													<div class="form-group">



													<label for="visastatus">Visa Status</label>
													<select class="form-control" name="visastatus">											
														<option value="H1B"  <?php echo $h1bhidden; ?> >H1B</option>
														<option value="GC" <?php echo $gchidden; ?> >GC</option>
														<option value="EAD" <?php echo $eadhidden; ?> >EAD</option>
														<option value="L2 EAD" <?php echo $l2eadhidden; ?> >L2 EAD</option>
														<option value="Citizen" <?php echo $citizenhidden; ?> >Citizen</option>
													</select>
													</div>
												</div>

												<div class="col-md-6">
													<div class="form-group">
														<label for="visaexpiry">Visa Expiry Date</label>

														<input type="text" class="form-control" name="visaexpiry" id="userHireDate" value="<?php echo clean($row['visaexpiry']); ?>" />
													</div>
												</div>
											</div>
											<button type="input" name="submit" value="immigInfo" class="btn btn-success btn-sm btn-icon"><i class="fa fa-check-square-o"></i> <?php echo $saveChangesBtn; ?></button>
										</form>
									</div>
								</div>
							</div>
						</div>

					</div>
				</div>
			</div>
		</div>



	</div>