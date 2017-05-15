<?php

	if (isset($_POST['submit']) && $_POST['submit'] == 'updateFlags') {
		if($_POST['theId'] == '') {
			$msgBox = alertBox($selUserFirstReq, "<i class='fa fa-times-circle'></i>", "danger");
		} else {
			$theId = htmlspecialchars($_POST['theId']);
			$usrName = htmlspecialchars($_POST['usrName']);

			$stmt = $mysqli->prepare("DELETE FROM appauths WHERE userId = ?");
			$stmt->bind_param('s', $theId);
			$stmt->execute();
			$stmt->close();

			if (isset($_POST['authFlags'])) {
				foreach($_POST['authFlags'] as $v) {
					$stmt = $mysqli->prepare("
										INSERT INTO
											appauths(
												userId,
												authFlag,
												authDate
											) VALUES (
												?,
												?,
												NOW()
											)");
					$stmt->bind_param('ss',
						$theId,
						$v
					);
					$stmt->execute();
					$stmt->close();
				}
			}

			$activityType = '16';
			$activityTitle = $tz_userFull.' '.$authsUpdAct.' "'.$usrName.'"';
			updateActivity($tz_userId,$activityType,$activityTitle);

			$msgBox = alertBox($authsUpdMsg." \"".$usrName."\" ".$authsUpdMsg1, "<i class='fa fa-check-square'></i>", "success");
		}
	}

	if (isset($_POST['submit']) && $_POST['submit'] == 'updateAdmin') {
		$isAdmin = htmlspecialchars($_POST['isAdmin']);
		$usrId = htmlspecialchars($_POST['usrId']);
		$usrName = htmlspecialchars($_POST['usrName']);

		$stmt = $mysqli->prepare("UPDATE users SET isAdmin = ? WHERE userId = ?");
		$stmt->bind_param('ss',$isAdmin,$usrId);
		$stmt->execute();
		$stmt->close();

		if ($isAdmin == '0') {
			$stmt = $mysqli->prepare("DELETE FROM appauths WHERE userId = ?");
			$stmt->bind_param('s', $usrId);
			$stmt->execute();
			$stmt->close();

			$stmt = $mysqli->prepare("UPDATE users SET superUser = 0 WHERE userId = ?");
			$stmt->bind_param('s',$usrId);
			$stmt->execute();
			$stmt->close();
		}

		$activityType = '16';
		$activityTitle = $tz_userFull.' '.$admStatUpdAct.' "'.$usrName.'"';
		updateActivity($tz_userId,$activityType,$activityTitle);

		$msgBox = alertBox($admStatUpdMsg." \"".$usrName."\" ".$editEventMsg, "<i class='fa fa-check-square'></i>", "success");
	}

	if (isset($_POST['submit']) && $_POST['submit'] == 'updateSuperuser') {
		$isSuperuser = htmlspecialchars($_POST['isSuperuser']);
		$usrId = htmlspecialchars($_POST['usrId']);
		$usrName = htmlspecialchars($_POST['usrName']);

		$stmt = $mysqli->prepare("UPDATE users SET superUser = ? WHERE userId = ?");
		$stmt->bind_param('ss',$isSuperuser,$usrId);
		$stmt->execute();
		$stmt->close();

		$activityType = '16';
		$activityTitle = $tz_userFull.' '.$superUserStatUpdAct.' "'.$usrName.'"';
		updateActivity($tz_userId,$activityType,$activityTitle);

		$msgBox = alertBox($superUserStatUpdMsg." \"".$usrName."\" ".$editEventMsg, "<i class='fa fa-check-square'></i>", "success");
	}

	$qry = "SELECT * FROM users WHERE isActive = 1";
	$res = mysqli_query($mysqli, $qry) or die('-1'.mysqli_error());

	$sql = "SELECT * FROM appauthdesc";
	$result = mysqli_query($mysqli, $sql) or die('-2'.mysqli_error());

	$userPage = 'true';
	$pageTitle = $userAuthsPageTitle;
	$addCss = '<link href="css/chosen.css" rel="stylesheet">';
	$chosen = 'true';
	$jsFile = 'userAuths';

	include 'includes/header.php';
?>
	<div class="container-fluid">
		<div class="container">
			<div class="row pageCont">
				<div class="col-md-12 pb-20">
					<?php
						if ((checkArray('APPAUTH', $auths)) || $tz_superUser != '') {
							if ($msgBox) { echo $msgBox; }
					?>
							<h4 class="head-title mt-20"><?php echo $userAuthsH4; ?></h4>
							<p>
								<?php echo $userAuthsQuip1; ?> <?php echo clean($set['siteName']); ?>.
								<?php echo $userAuthsQuip2; ?>
							</p>
							<input type="hidden" id="yesOpt" value="<?php echo $yesBtn; ?>" />
							<input type="hidden" id="noOpt" value="<?php echo $noBtn; ?>" />
							<input type="hidden" id="errorOne" value="<?php echo $errorOneText; ?>" />

							<div class="row">
								<div class="col-md-6">
									<h4 class="head-title mt-20"><?php echo $selUserModifyField; ?></h4>
									<div class="row">
										<div class="col-md-7">
											<select class="form-control chosen-select" id="selectUser" name="selectUser">
												<option value="..."><?php echo $selectUserField; ?></option>
												<?php
													while ($row = mysqli_fetch_assoc($res)) {
														echo '<option value="'.$row['userId'].'">'.clean($row['userFirst']).' '.clean($row['userLast']).'</option>';
													}
												?>
											</select>
										</div>
										<div class="col-md-5">
											<a href="#" class="btn btn-sm btn-info" id="loadUser"><?php echo $selUserModifyLoad; ?></a>
											<a href="#" class="btn btn-sm btn-warning resetForm"><?php echo $selUserModifyClear; ?></a>
										</div>
									</div>
									<div id="msgText"><span></span></div>

									<div class="userInfo">
										<hr />
										<li class="list-group-item" id="user_name"><span></span></li>
										<li class="list-group-item" id="user_email"><span></span></li>
										<li class="list-group-item" id="user_position"><span></span></li>
										<li class="list-group-item" id="is_admin"><span></span></li>
										<li class="list-group-item" id="is_superuser"><span></span></li>
										<hr />
									</div>

									<div class="row">
										<div class="col-md-6">
											<div class="admin">
												<form action="" method="post">
													<div class="form-group">
														<label for="isAdmin"><?php echo $mngAdmField; ?></label>
														<select class="form-control" name="isAdmin" id="isAdmin">
															<option value="0"><?php echo $noBtn; ?></option>
															<option value="1"><?php echo $yesBtn; ?></option>
														</select>
													</div>
													<input type="hidden" name="usrId" id="usrId1" />
													<input type="hidden" name="usrName" id="usrName1" />
													<button type="input" name="submit" value="updateAdmin" class="btn btn-success btn-sm btn-icon"><i class="fa fa-check-square-o"></i> <?php echo $saveBtn; ?></button>
												</form>
											</div>
										</div>
										<div class="col-md-6">
											<div class="superuser">
												<form action="" method="post">
													<div class="form-group">
														<label for="isSuperuser"><?php echo $superUserField; ?></label>
														<select class="form-control" name="isSuperuser" id="isSuperuser">
															<option value="0"><?php echo $noBtn; ?></option>
															<option value="1"><?php echo $yesBtn; ?></option>
														</select>
													</div>
													<input type="hidden" name="usrId" id="usrId2" />
													<input type="hidden" name="usrName" id="usrName2" />
													<button type="input" name="submit" value="updateSuperuser" class="btn btn-success btn-sm btn-icon"><i class="fa fa-check-square-o"></i> <?php echo $saveBtn; ?></button>
												</form>
											</div>
										</div>
									</div>

									<div class="typeMessage">
										<div class="alertMsg small default">
											<div class="msgIcon pull-left">
												<i class="fa fa-info-circle"></i>
											</div>
											<?php echo $admMngQuip; ?>
										</div>
									</div>
								</div>
								<div class="col-md-6">
									<h4 class="head-title mt-20"><?php echo $selectAuthsField; ?></h4>
									<p><?php echo $selectAuthsFieldQuip; ?></p>

									<div class="alertMsg small default">
										<div class="msgIcon pull-left">
											<i class="fa fa-warning"></i>
										</div>
										<?php echo $superUserQuip; ?>
									</div>

									<form action="" method="post">
										<?php
											while ($rows = mysqli_fetch_assoc($result)) {
												echo '
														<div class="checkbox">
															<label>
																<input type="checkbox" name="authFlags[]" value="'.$rows['authFlag'].'" id="'.$rows['authFlag'].'">
																'.$rows['flagDesc'].'
															</label>
														</div>
													';
											}
										?>
										<hr />
										<input type="hidden" name="theId" id="theId" />
										<input type="hidden" name="usrName" id="usrName3" />
										<button type="input" name="submit" value="updateFlags" class="btn btn-success btn-sm btn-icon"><i class="fa fa-check-square-o"></i> <?php echo $saveAuthsBtn; ?></button>
										<a href="#" class="btn btn-warning btn-sm btn-icon resetForm"><i class="fa fa-times-circle-o"></i> <?php echo $selUserModifyClear; ?></a>
									</form>
								</div>
							</div>

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