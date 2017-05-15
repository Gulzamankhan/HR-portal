<?php
	$messageId = htmlspecialchars($_GET['messageId']);

	
	$actIp = $_SERVER['REMOTE_ADDR'];

	
	if (isset($_POST['submit']) && $_POST['submit'] == 'sendReply') {
		if($_POST['messageTitle'] == '') {
			$msgBox = alertBox($msgSubjectReq, "<i class='fa fa-times-circle'></i>", "danger");
		} else if($_POST['messageText'] == '') {
			$msgBox = alertBox($replyMsgReq, "<i class='fa fa-times-circle'></i>", "danger");
		} else {
			$messageTitle = htmlspecialchars($_POST['messageTitle']);
			$origMsgTitle = htmlspecialchars($_POST['origMsgTitle']);
			$messageText = htmlspecialchars($_POST['messageText']);
			$toId = htmlspecialchars($_POST['toId']);
			$sentFrom = htmlspecialchars($_POST['sentFrom']);

			$stmt = $mysqli->prepare("
								INSERT INTO
									privatemessages(
										fromId,
										toId,
										origId,
										messageTitle,
										messageText,
										messageDate,
										toRead,
										toArchived,
										toDeleted,
										fromDeleted,
										lastUpdated
									) VALUES (
										?,
										?,
										?,
										?,
										?,
										NOW(),
										0,
										0,
										0,
										0,
										NOW()
									)
			");
			$stmt->bind_param('sssss',
								$tz_userId,
								$toId,
								$messageId,
								$messageTitle,
								$messageText
			);
			$stmt->execute();
			$stmt->close();

			
			$activityType = '13';
			$activityTitle = $tz_userFull.' '.$msgRepliedAct.' "'.$origMsgTitle.'"';
			updateActivity($tz_userId,$activityType,$activityTitle);

			$msgBox = alertBox($msgReplySentMsg1." \"".$origMsgTitle."\" ".$msgReplySentMsg2." ".$sentFrom.".", "<i class='fa fa-check-square'></i>", "success");
		}
    }

	
	if (isset($_POST['submit']) && $_POST['submit'] == 'archiveMsg') {
		$messageTitle = htmlspecialchars($_POST['messageTitle']);

		$stmt = $mysqli->prepare("UPDATE
									privatemessages
								SET
									toArchived = 1,
									lastUpdated = NOW()
								WHERE
									messageId = ?"
		);
		$stmt->bind_param('s',$messageId);
		$stmt->execute();
		$stmt->close();

		
		$activityType = '13';
		$activityTitle = $tz_userFull.' '.$msgMarkedReadAct.' "'.$messageTitle.'" '.$msgArchivedAct;
		updateActivity($tz_userId,$activityType,$activityTitle);

		$msgBox = alertBox($theMsgText." \"".$messageTitle."\" ".$msgArchivedAct1, "<i class='fa fa-check-square'></i>", "success");
    }

	
	if (isset($_POST['submit']) && $_POST['submit'] == 'deleteMsg') {
		$messageTitle = htmlspecialchars($_POST['messageTitle']);

		$stmt = $mysqli->prepare("UPDATE
									privatemessages
								SET
									toDeleted = 1,
									lastUpdated = NOW()
								WHERE
									messageId = ?"
		);
		$stmt->bind_param('s',$messageId);
		$stmt->execute();
		$stmt->close();

		
		$activityType = '13';
		$activityTitle = $tz_userFull.' '.$msgDelAct.' "'.$messageTitle.'"';
		updateActivity($tz_userId,$activityType,$activityTitle);

		
		header ('Location: index.php?page=messages&deleted=yes');
    }

	
	$sql = "SELECT
				privatemessages.*,
				(SELECT CONCAT(users.userFirst,' ',users.userLast) FROM users WHERE privatemessages.fromId = users.userId) AS msgFrom
			FROM
				privatemessages
			WHERE
				privatemessages.messageId = ".$messageId;
	$res = mysqli_query($mysqli, $sql) or die('-1' . mysqli_error());
	$row = mysqli_fetch_assoc($res);

	$messageTitle = clean($row['messageTitle']);
	if ($row['toRead'] != '1') {
		
		$stmt = $mysqli->prepare("UPDATE
									privatemessages
								SET
									toRead = 1,
									lastUpdated = NOW()
								WHERE
									messageId = ?"
		);
		$stmt->bind_param('s',$messageId);
		$stmt->execute();
		$stmt->close();
	}

	
	$activityType = '13';
	$activityTitle = $tz_userFull.' '.$openedMsgAct.' "'.$messageTitle;
	updateActivity($tz_userId,$activityType,$activityTitle);

	$msgPage = 'true';
	$pageTitle = $viewReceivedPageTitle;

	include 'includes/header.php';
?>
	<div class="container-fluid">
		<div class="container">
			<div class="row pageCont">
				<?php if ($row['toDeleted'] != '1') { ?>
					<?php if ($row['toId'] == $tz_userId) { ?>
						<div class="col-md-3 no-padding">
							<div class="sideBar pt-20 pb-20">
								<ul class="list-group">
									<li class="list-group-item task-lists"><strong><?php echo $fromTh; ?>:</strong><br /><?php echo clean($row['msgFrom']); ?></li>
									<li class="list-group-item task-lists"><strong><?php echo $recvdText; ?>:</strong><br /><?php echo dateTimeFormat($row['messageDate']); ?></li>
									<li class="list-group-item task-lists"><strong><?php echo $locationField; ?>:</strong>
										<?php
											if ($row['toArchived'] != '1') {
												echo $inboxNav;
											} else {
												echo $archiveText;
											}
										?>
									</li>
									<li class="list-group-item task-lists"><strong><?php echo $lastActivityLi; ?>:</strong><br /><?php echo dateTimeFormat($row['lastUpdated']); ?></li>
								</ul>
								<?php if ($row['toArchived'] != '1') { ?>
									<a href="#archive" data-toggle="modal" class="btn btn-hover btn-warning btn-sm"><i class="fa fa-archive"></i><span><?php echo $arcMsgHiddenText; ?></span></a>
									<div class="modal fade" id="archive" tabindex="-1" role="dialog" aria-hidden="true">
										<div class="modal-dialog">
											<div class="modal-content">
												<form action="" method="post">
													<div class="modal-body">
														<p class="lead mt-0"><?php echo $archiveMsgConf; ?> "<?php echo clean($row['messageTitle']); ?>"?</p>
													</div>
													<div class="modal-footer">
														<input type="hidden" name="messageTitle" value="<?php echo $row['messageTitle']; ?>" />
														<button type="button" class="btn btn-default btn-sm btn-icon" data-dismiss="modal"><i class="fa fa-times-circle-o"></i> <?php echo $cancelBtn; ?></button>
														<button type="input" name="submit" value="archiveMsg" class="btn btn-success btn-sm btn-icon"><i class="fa fa-check-square-o"></i> <?php echo $yesBtn; ?></button>
													</div>
												</form>
											</div>
										</div>
									</div>
								<?php } ?>

								<a href="#delete" data-toggle="modal" class="btn btn-hover btn-danger btn-sm btn-icon"><i class="fa fa-trash"></i><span><?php echo $delMsgHiddenText; ?></span></a>
								<div class="modal fade" id="delete" tabindex="-1" role="dialog" aria-hidden="true">
									<div class="modal-dialog">
										<div class="modal-content">
											<form action="" method="post">
												<div class="modal-body">
													<p class="lead mt-0"><?php echo $delMessageConf; ?> "<?php echo clean($row['messageTitle']); ?>"?</p>
												</div>
												<div class="modal-footer">
													<input type="hidden" name="messageTitle" value="<?php echo $row['messageTitle']; ?>" />
													<button type="button" class="btn btn-default btn-sm btn-icon" data-dismiss="modal"><i class="fa fa-times-circle-o"></i> <?php echo $cancelBtn; ?></button>
													<button type="input" name="submit" value="deleteMsg" class="btn btn-success btn-sm btn-icon"><i class="fa fa-check-square-o"></i> <?php echo $yesBtn; ?></button>
												</div>
											</form>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="col-md-9 no-padding">
							<div class="mainCont pb-20">
								<?php if ($msgBox) { echo $msgBox; } ?>
								<div class="well well-sm well-msg mt-20">
									<h3 class="head-title mt-0"><?php echo clean($row['messageTitle']); ?></h3>
									<?php echo nl2br(clean($row['messageText'])); ?>
								</div>

								<h4 class="head-title mt-20"><?php echo $sendReplyH4; ?></h4>
								<form action="" method="post">
									<div class="form-group">
										<label for="messageTitle"><?php echo $subjectField; ?></label>
										<input type="text" class="form-control" name="messageTitle" required="required" maxlength="50" value="re: <?php echo clean($row['messageTitle']); ?>" />
									</div>
									<div class="form-group">
										<label for="messageText"><?php echo $messagesField; ?></label>
										<textarea class="form-control" name="messageText" required="required" rows="5"></textarea>
									</div>
									<input type="hidden" name="toId" value="<?php echo $row['fromId']; ?>" />
									<input type="hidden" name="origMsgTitle" value="<?php echo clean($row['messageTitle']); ?>" />
									<input type="hidden" name="sentFrom" value="<?php echo clean($row['msgFrom']); ?>" />
									<button type="input" name="submit" value="sendReply" class="btn btn-success btn-sm btn-icon"><i class="fa fa-check-square-o"></i> <?php echo $sendReplyBtn; ?></button>
								</form>
							</div>
						</div>
					<?php } else { ?>
						<div class="col-md-12 pb-20">
							<div class="alertMsg danger">
								<div class="msgIcon pull-left">
									<i class="fa fa-ban"></i>
								</div>
								<?php echo $accessErrorMsg; ?>
							</div>
						</div>
				<?php
						}
					} else {
				?>
					<div class="col-md-12 pb-20">
						<div class="alertMsg warning">
							<div class="msgIcon pull-left">
								<i class="fa fa-ban"></i>
							</div>
							<?php echo $ViewDelMsgErrMsg; ?>
						</div>
					</div>
				<?php } ?>
			</div>
		</div>
	</div>