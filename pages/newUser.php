<?php
	$actIp = $_SERVER['REMOTE_ADDR'];

	$avatarDir 		= $set['avatarFolder'];
	$userDocsPath	= $set['userDocsPath'];

	if (isset($_POST['submit']) && $_POST['submit'] == 'newUser') {

		$usercount = $_POST['totalus'];
		if($_POST['totalus'] == '5') {
			$msgBox = alertBox("$totaluserCnt $usercount $totaluserCntlast", "<i class='fa fa-times-circle'></i>", "danger");
		} else if($_POST['userFirst'] == '') {
			$msgBox = alertBox($usrFisrNameReq, "<i class='fa fa-times-circle'></i>", "danger");
		} else if($_POST['password1'] == '') {
			$msgBox = alertBox($usrPassReq, "<i class='fa fa-times-circle'></i>", "danger");
		} else if($_POST['password2'] == '') {
			$msgBox = alertBox($usrRepeatPassReq, "<i class='fa fa-times-circle'></i>", "danger");
		} else if($_POST['password1'] != $_POST['password2']) {
			$msgBox = alertBox($usrAccPassMatchErr, "<i class='fa fa-times-circle'></i>", "danger");
		} else {
			$dupEmail = '';
			$userFirst = htmlspecialchars($_POST['userFirst']);
			$userMiddleInt = htmlspecialchars($_POST['userMiddleInt']);
			$userLast = htmlspecialchars($_POST['userLast']);
			$userHireDate = htmlspecialchars($_POST['userHireDate']);
			$userEmail = htmlspecialchars($_POST['userEmail']);
			$password1 = encodeIt($_POST['password1']);
			$userPhone1 = encodeIt($_POST['userPhone1']);
			$userPhone2 = encodeIt($_POST['userPhone2']);
			$userAddress1 = encodeIt($_POST['userAddress1']);
			$userAddress2 = encodeIt($_POST['userAddress2']);
			$userPosition = htmlspecialchars($_POST['userPosition']);
			$employeenumber = htmlspecialchars($_POST['employeenumber']);
			$managerId = htmlspecialchars($_POST['managerId']);
			$userpincode = htmlspecialchars($_POST['userpincode']);
			$userstreet = htmlspecialchars($_POST['userstreet']);

			$check = $mysqli->query("SELECT 'X' FROM users WHERE userEmail = '".$userEmail."'");
			if ($check->num_rows) {
				$dupEmail = 'true';
			}

			if ($dupEmail != '') {
				$msgBox = alertBox($dupAccFoundMsg, "<i class='fa fa-warning'></i>", "warning");

				$_POST['userEmail'] = '';
			} else {
				$randomHash = uniqid(rand());
				$randHash = substr($randomHash, 0, 8);
				$hash = md5(rand(0,1000));

				$docFolderName = $userFirst.'_'.$userLast;
				$userFldr = str_replace(' ', '_', $docFolderName);

				$usrDocsFolder = strtolower($userFldr).'_'.$randHash;

				if (mkdir($avatarDir.$usrDocsFolder, 0755, true)) {
					$newDir = $avatarDir.$usrDocsFolder;
				}

				if (mkdir($userDocsPath.$usrDocsFolder, 0755, true)) {
					$newDir = $userDocsPath.$usrDocsFolder;
				}

				$stmt = $mysqli->prepare("
									INSERT INTO
										users(
											userEmail,
											password,
											userFirst,
											userMiddleInt,
											userLast,
											userFolder,
											userPhone1,
											userPhone2,
											userPhone3,
											userAddress1,
											userAddress2,
											userPosition,
											employeenumber,
											userHireDate,
											createDate,
											managerId,
											userpincode,
											userstreet,
											hash,
											isActive
										) VALUES (
											?,
											?,
											?,
											?,
											?,
											?,
											?,
											?,
											?,
											?,
											?,
											?,
											?,
											?,
											NOW(),
											?,
											?,
											?,
											?,
											1
										)
				");
				$stmt->bind_param('ssssssssssssssssss',
									$userEmail,
									$password1,
									$userFirst,
									$userMiddleInt,
									$userLast,
									$usrDocsFolder,
									$userPhone1,
									$userPhone2,
									$userPhone3,
									$userAddress1,
									$userAddress2,
									$userPosition,
									$employeenumber,
									$userHireDate,
									$managerId,
									$userpincode,
									$userstreet,
									$hash
				);
				$stmt->execute();
				$stmt->close();

				$activityType = '19';
				$activityTitle = $tz_userFull.' '.$newUserCreatedAct.' '.$userFirst.' '.$userLast;
				updateActivity($tz_userId,$activityType,$activityTitle);

				$msgBox = alertBox($newUserCreatedMsg1." ".$userFirst.' '.$userLast." ".$newUserCreatedMsg2, "<i class='fa fa-check-square'></i>", "success");

				$_POST['userFirst'] = $_POST['userMiddleInt'] = $_POST['userLast'] = $_POST['userHireDate'] = $_POST['userEmail'] = $_POST['userPhone1'] = '';
				$_POST['userPhone2'] = $_POST['userAddress1'] = $_POST['userAddress2'] = $_POST['userPosition'] = $_POST['employeenumber'] = $_POST['managerId'] = '';
			}
		}
	}

	$userPage = 'true';
	$pageTitle = $newUserPageTitle;
	$addCss = '<link rel="stylesheet" type="text/css" href="css/datetimepicker.css" />';
	$datePicker = 'true';
	$jsFile = 'newUser';

	include 'includes/header.php';
?>
	<div class="container-fluid">
		<div class="container">
			<div class="row pageCont">
				<div class="col-md-12 pb-20">
					<?php
						if ((checkArray('MNGUSR', $auths)) || $tz_superUser != '') {
							if ($msgBox) { echo $msgBox; }
					?>
							<h4 class="head-title mt-20">Create a New User Account</h4>

							<form action="" method="post" class="mt-20">
								<div class="row">
								<div class="col-md-1" style=" width: 12%;">
										<div class="form-group">
										<input class="hidden" name="totalus" id="totalus" value="<?php echo $usercnt ?>" />
											<label for="employeenumber"><?php echo $empNumField; ?></label>
											<input type="text" class="form-control" name="employeenumber" id="employeenumber" value="<?php echo isset($_POST['employeenumber']) ? $_POST['employeenumber'] : ''; ?>" />
										</div>
										
									</div>
									<div class="col-md-5" style="width: 38%;">
										<div class="form-group">
											<label for="userFirst"><?php echo $firstNameField; ?></label>
											<input type="text" class="form-control" name="userFirst" id="userFirst" required="required" value="<?php echo isset($_POST['userFirst']) ? $_POST['userFirst'] : ''; ?>" />
										</div>
									</div>
									<div class="col-md-1" style=" width: 12%;">
										<div class="form-group">
											<label for="userMiddleInt">Initial </label>
											<input type="text" class="form-control" name="userMiddleInt" id="userMiddleInt" maxlength="3" value="<?php echo isset($_POST['userMiddleInt']) ? $_POST['userMiddleInt'] : ''; ?>" />
										</div>
									</div>
									<div class="col-md-5" style="width: 38%;">
										<div class="form-group">
											<label for="userLast"><?php echo $lastNameField; ?></label>
											<input type="text" class="form-control" name="userLast" id="userLast" required="required" value="<?php echo isset($_POST['userLast']) ? $_POST['userLast'] : ''; ?>" />
										</div>
									</div>
									
								</div>
								<div class="row">
									
									<div class="col-md-6">
										<div class="form-group">
											<label for="userEmail"><?php echo $emailAddyField; ?></label>
											<input type="email" class="form-control" name="userEmail" id="userEmail" r value="<?php echo isset($_POST['userEmail']) ? $_POST['userEmail'] : ''; ?>" />
											<span class="help-block"><?php echo $emailAddyFieldHelp; ?></span>
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<label for="userEmail_r">Confirm Email Address</label>
											<input type="email" class="form-control" name="userEmail_r" id="userEmail_r"  value="" />
											<span class="help-block"><?php echo $rptEmailAddyFieldHelp; ?></span>
										</div>
									</div>
								</div>
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
											<label for="password2">Confirm Account Password</label>
											<input type="password" class="form-control" required="required" name="password2" id="password2" value="" />
											<span class="help-block"><?php echo $rptAccPassFieldHelp; ?></span>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-md-6">
										<div class="form-group">
											<label for="userPhone1"><?php echo $priPhoneField; ?></label>
											<input type="text" class="form-control" name="userPhone1" id="userPhone1" value="<?php echo isset($_POST['userPhone1']) ? $_POST['userPhone1'] : ''; ?>" />
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<label for="userPhone2"><?php echo $altPhoneField; ?></label>
											<input type="text" class="form-control" name="userPhone2" id="userPhone2" value="<?php echo isset($_POST['userPhone2']) ? $_POST['userPhone2'] : ''; ?>" />
										</div>
									</div>
								</div>
								<div class="row hidden">
									<div class="col-md-6">
										<div class="form-group">
											<label for="userAddress1"><?php echo $mailAddyField; ?></label>
											<textarea class="form-control" name="userAddress1" id="userAddress1" rows="3"><?php echo isset($_POST['userAddress1']) ? $_POST['userAddress1'] : ''; ?></textarea>
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<label for="userAddress2"><?php echo $altAddyField; ?></label>
											<textarea class="form-control" name="userAddress2" id="userAddress2" rows="3"><?php echo isset($_POST['userAddress2']) ? $_POST['userAddress2'] : ''; ?></textarea>
										</div>
									</div>
								</div>

								

								<div class="row ">
												
												
								<div class="col-md-12">
													<div class="form-group">
														<label for="userstreet">Home Address</label>
														<textarea class="form-control" name="userstreet" id="userstreet" rows="3"><?php echo $userstreet; ?></textarea>
													</div>
												</div>
												<div class="col-md-3 hidden">
													<div class="form-group">
														<label for="userpincode">Pincode</label>
														<textarea class="form-control" name="userpincode" id="userpincode" rows="1"><?php echo $userpincode; ?></textarea>
													</div>
												</div>
											</div>

								<div class="row">
									<div class="col-md-4">
										<div class="form-group">
											<label for="userPosition"><?php echo $positionTitleField; ?></label>
											<input type="text" class="form-control" name="userPosition" id="userPosition" value="<?php echo isset($_POST['userPosition']) ? $_POST['userPosition'] : ''; ?>" />
										</div>

									</div>
									

									<div class="col-md-4">
										<div class="form-group">
											<label for="userHireDate"><?php echo $hireDateField; ?></label>
											<input type="text" class="form-control" name="userHireDate" id="userHireDate" value="<?php echo isset($_POST['userHireDate']) ? $_POST['userHireDate'] : ''; ?>" />
											<span class="help-block"><?php echo $hireDateFieldHelp; ?></span>
										</div>
									</div>
									<div class="col-md-4 ">
										<div class="form-group hide">
											<label for="managerId"><?php echo $managerText; ?></label>
											<?php
												$usrqry = "SELECT userId, userFirst, userLast FROM users WHERE isActive = 1 AND isAdmin = 1";
												$usrres = mysqli_query($mysqli, $usrqry) or die('-1'.mysqli_error());
											?>
											<select class="form-control" name="managerId" id="managerId">
												<option value="..."><?php echo $selectOption; ?></option>
												<?php
													while ($usr = mysqli_fetch_assoc($usrres)) {
														echo '<option value="'.$usr['userId'].'">'.$usr['userFirst'].' '.$usr['userLast'].'</option>';
													}
												?>
											</select>
											<span class="help-block hide"><?php echo $newUserManagerFieldHelp; ?></span>
										</div>
									</div>
								</div>

								<button type="input" name="submit" value="newUser" class="btn btn-success btn-sm btn-icon"><i class="fa fa-check-square-o"></i> <?php echo $saveNewUsrBtn; ?></button>
							</form>
					<?php } else { ?>
						<div class="alertMsg danger">
							<div class="msgIcon pull-left">
								<i class="fa fa-ban"></i>
							</div>
							<?php echo $accessErrorMsg; ?>
						</div>
					<?php } ?>
				</div>
			</div>
		</div>
	</div>