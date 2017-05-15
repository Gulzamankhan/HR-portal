<?php
	$messageId = htmlspecialchars($_GET['messageId']);

	
	$actIp = $_SERVER['REMOTE_ADDR'];

	
	if (isset($_POST['submit']) && $_POST['submit'] == 'deleteMsg') {
		$messageTitle = htmlspecialchars($_POST['messageTitle']);

		$stmt = $mysqli->prepare("UPDATE
									privatemessages
								SET
									fromDeleted = 1,
									lastUpdated = NOW()
								WHERE
									messageId = ?"
		);
		$stmt->bind_param('s',$messageId);
		$stmt->execute();
		$stmt->close();

		
		$activityType = '13';
		$activityTitle = $tz_userFull.' '.$delSentMsgAct.' "'.$messageTitle.'"';
		updateActivity($tz_userId,$activityType,$activityTitle);

		
		header ('Location: index.php?page=messages&deleted=yes');
    }

	
	$sql = "SELECT
				privatemessages.*,
				(SELECT CONCAT(users.userFirst,' ',users.userLast) FROM users WHERE privatemessages.toId = users.userId) AS msgTo
			FROM
				privatemessages
			WHERE
				privatemessages.messageId = ".$messageId;
	$res = mysqli_query($mysqli, $sql) or die('-1' . mysqli_error());
	$row = mysqli_fetch_assoc($res);

	$msgPage = 'true';
	$pageTitle = $viewSentPageTitle;

	include 'includes/header.php';
?>
	<div class="container-fluid">
		<div class="container">
			<div class="row pageCont">
				<?php if ($row['fromDeleted'] != '1') { ?>
					<?php if ($row['fromId'] == $tz_userId) { ?>
						<div class="col-md-3 no-padding">
							<div class="sideBar pt-20 pb-20">
								<ul class="list-group">
									<li class="list-group-item task-lists"><strong><?php echo $sentToLi; ?>:</strong><br /><?php echo clean($row['msgTo']); ?></li>
									<li class="list-group-item task-lists"><strong><?php echo $sentOnLi; ?>:</strong><br /><?php echo dateTimeFormat($row['messageDate']); ?></li>
									<li class="list-group-item task-lists"><strong><?php echo $statusText; ?>:</strong>
										<?php
											if ($row['toRead'] != '1') {
												echo $unreadText;
											} else {
												echo $readText;
											}
										?>
									</li>
									<li class="list-group-item task-lists"><strong><?php echo $lastActivityLi; ?>:</strong><br /><?php echo dateTimeFormat($row['lastUpdated']); ?></li>
								</ul>

								<a href="#delete" data-toggle="modal" class="btn btn-hover btn-danger btn-sm btn-icon"><i class="fa fa-trash"></i><span><?php echo $delSentMsgLink; ?></span></a>
								<div class="modal fade" id="delete" tabindex="-1" role="dialog" aria-hidden="true">
									<div class="modal-dialog">
										<div class="modal-content">
											<form action="" method="post">
												<div class="modal-body">
													<p class="lead mt-0">
														<?php echo $delSentMsgConf1; ?> "<?php echo clean($row['messageTitle']); ?>"?<br />
														<small><?php echo $delSentMsgConf2; ?></small>
													</p>
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