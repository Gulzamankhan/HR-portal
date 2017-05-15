<?php
	$noticeId = htmlspecialchars($_GET['noticeId']);

	
	$actIp = $_SERVER['REMOTE_ADDR'];

	
	if (isset($_POST['submit']) && $_POST['submit'] == 'updateNotice') {
		
		if($_POST['noticeTitle'] == '') {
			$msgBox = alertBox($noticeTitleReq, "<i class='fa fa-times-circle'></i>", "danger");
		} else if($_POST['noticeText'] == '') {
			$msgBox = alertBox($noticeTextReq, "<i class='fa fa-times-circle'></i>", "danger");
		} else {
			$isActive = htmlspecialchars($_POST['isActive']);
			$adminOnly = htmlspecialchars($_POST['adminOnly']);
			if ($_POST['noticeStart'] == '') {
				$noticeStart = '0000-00-00 00:00:00';
			} else {
				$noticeStart = htmlspecialchars($_POST['noticeStart']);
			}
			if ($_POST['noticeExpires'] == '') {
				$noticeExpires = '0000-00-00 00:00:00';
			} else {
				$noticeExpires = htmlspecialchars($_POST['noticeExpires']);
			}
			$noticeTitle = htmlspecialchars($_POST['noticeTitle']);
			$noticeText = htmlspecialchars($_POST['noticeText']);

			$stmt = $mysqli->prepare("UPDATE
										notices
									SET
										adminOnly = ?,
										isActive = ?,
										noticeTitle = ?,
										noticeText = ?,
										noticeStart = ?,
										noticeExpires = ?,
										lastUpdated = NOW(),
										ipAddress = ?
									WHERE
										noticeId = ?"
			);
			$stmt->bind_param('ssssssss',
									$adminOnly,
									$isActive,
									$noticeTitle,
									$noticeText,
									$noticeStart,
									$noticeExpires,
									$actIp,
									$noticeId
			);
			$stmt->execute();
			$stmt->close();

			
			$activityType = '17';
			$activityTitle = $tz_userFull.' '.$updNoticeAct.' "'.$noticeTitle.'"';
			updateActivity($tz_userId,$activityType,$activityTitle);

			$msgBox = alertBox($updNoticeMsg." \"".$noticeTitle."\" ".$editEventMsg, "<i class='fa fa-check-square'></i>", "success");
		}
	}


	
	$qry = "SELECT
				notices.*,
				CONCAT(users.userFirst,' ',users.userLast) AS createdBy
			FROM
				notices
				LEFT JOIN users ON notices.createdBy = users.userId
			WHERE
				notices.noticeId = ".$noticeId;
	$res = mysqli_query($mysqli, $qry) or die('-1' . mysqli_error());
	$row = mysqli_fetch_assoc($res);

	if ($row['noticeStart'] == '0000-00-00 00:00:00') { $noticeStart = ''; } else { $noticeStart = shortMonthFormat($row['noticeStart']); }
	if ($row['noticeExpires'] == '0000-00-00 00:00:00') { $noticeExpires = ''; } else { $noticeExpires = shortMonthFormat($row['noticeExpires']); }
	if ($row['isActive'] == '1') { $active = 'selected'; } else { $active = ''; }
	if ($row['adminOnly'] == '1') { $adminOnly = 'selected'; } else { $adminOnly = ''; }

	$mngPage = 'true';
	$pageTitle = $viewNoticePageTitle;
	$addCss = '<link rel="stylesheet" type="text/css" href="css/datetimepicker.css" />';
	$datePicker = 'true';
	$jsFile = 'viewNotice';

	include 'includes/header.php';
?>
	<div class="container-fluid">
		<div class="container">
			<div class="row pageCont">
				<div class="col-md-12 pb-20">
					<?php
						if ((checkArray('SITENOTES', $auths)) || $tz_superUser != '') {
							if ($msgBox) { echo $msgBox; }
					?>
							<h4 class="head-title mt-20"><?php echo $siteNoticeH4; ?> &mdash; <?php echo clean($row['noticeTitle']); ?></h4>
							<p><?php echo $siteNoticeDatesQuip; ?></p>
							<form action="" method="post">
								<div class="row">
									<div class="col-md-6">
										<div class="form-group">
											<label for="isActive"><?php echo $noticeStatusField; ?></label>
											<select class="form-control" name="isActive" id="isActive">
												<option value="0"><?php echo $inactiveOption; ?></option>
												<option value="1" <?php echo $active; ?>><?php echo $activeOption; ?></option>
											</select>
											<span class="help-block"><?php echo $noticeActiveQuip; ?></span>
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<label for="adminOnly"><?php echo $noticeTypeField; ?></label>
											<select class="form-control" name="adminOnly" id="adminOnly">
												<option value="0"><?php echo $publicSelOpt; ?></option>
												<option value="1" <?php echo $adminOnly; ?>><?php echo $privateSelOpt; ?></option>
											</select>
											<span class="help-block"><?php echo $noticeTypeFieldHelp; ?></span>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-md-6">
										<div class="form-group">
											<label for="noticeStart"><?php echo $startDateField; ?></label>
											<input type="text" class="form-control" name="noticeStart" id="noticeStart" value="<?php echo $noticeStart; ?>" />
											<span class="help-block"><?php echo $noticeStartHelp; ?></span>
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<label for="noticeExpires"><?php echo $endDateField; ?></label>
											<input type="text" class="form-control" name="noticeExpires" id="noticeExpires" value="<?php echo $noticeExpires; ?>" />
											<span class="help-block"><?php echo $noticeEndHelp; ?></span>
										</div>
									</div>
								</div>
								<div class="form-group">
									<label for="noticeTitle"><?php echo $noticeTitleField; ?></label>
									<input type="text" class="form-control" name="noticeTitle" id="noticeTitle" required="required" value="<?php echo clean($row['noticeTitle']); ?>" />
								</div>
								<div class="form-group">
									<label for="noticeText"><?php echo $noticeTextField; ?></label>
									<textarea class="form-control" name="noticeText" id="noticeText" required="required" rows="4"><?php echo clean($row['noticeText']); ?></textarea>
								</div>
								<button type="input" name="submit" value="updateNotice" class="btn btn-success btn-sm btn-icon"><i class="fa fa-check-square-o"></i> <?php echo $saveChangesBtn; ?></button>
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