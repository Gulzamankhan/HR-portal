<?php
	
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
			$origId = htmlspecialchars($_POST['origId']);
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
								$origId,
								$messageTitle,
								$messageText
			);
			$stmt->execute();
			$stmt->close();

			
			$stmt = $mysqli->prepare("UPDATE
										privatemessages
									SET
										toRead = 1,
										lastUpdated = NOW()
									WHERE
										messageId = ?"
			);
			$stmt->bind_param('s',$origId);
			$stmt->execute();
			$stmt->close();

			
			$activityType = '13';
			$activityTitle = $tz_userFull.' '.$msgRepliedAct.' "'.$origMsgTitle.'"';
			updateActivity($tz_userId,$activityType,$activityTitle);

			$msgBox = alertBox($msgReplySentMsg1." \"".$origMsgTitle."\" ".$msgReplySentMsg2." ".$sentFrom.".", "<i class='fa fa-check-square'></i>", "success");
		}
    }

	
	if (isset($_POST['submit']) && $_POST['submit'] == 'markRead') {
		$messageId = htmlspecialchars($_POST['messageId']);
		$messageTitle = htmlspecialchars($_POST['messageTitle']);

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

		
		$activityType = '13';
		$activityTitle = $tz_userFull.' '.$msgMarkedReadAct.' "'.$messageTitle.'" '.$msgMarkedReadAct1;
		updateActivity($tz_userId,$activityType,$activityTitle);
    }

	
	if (isset($_POST['submit']) && $_POST['submit'] == 'archive') {
		$messageId = htmlspecialchars($_POST['messageId']);
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
		$deleteId = htmlspecialchars($_POST['deleteId']);
		$messageTitle = htmlspecialchars($_POST['messageTitle']);

		$stmt = $mysqli->prepare("UPDATE
									privatemessages
								SET
									toRead = 1,
									toDeleted = 1,
									lastUpdated = NOW()
								WHERE
									messageId = ?"
		);
		$stmt->bind_param('s',$deleteId);
		$stmt->execute();
		$stmt->close();

		
		$activityType = '13';
		$activityTitle = $tz_userFull.' '.$msgDelAct.' "'.$messageTitle.'"';
		updateActivity($tz_userId,$activityType,$activityTitle);

		$msgBox = alertBox($theMsgText." \"".$messageTitle."\" ".$delEventMsg2, "<i class='fa fa-check-square'></i>", "success");
    }

	
	$sql = "SELECT
				privatemessages.*,
				CONCAT(users.userFirst,' ',users.userLast) AS msgFrom
			FROM
				privatemessages
				LEFT JOIN users ON privatemessages.fromId = users.userId
			WHERE
				privatemessages.toId = ".$tz_userId." AND
				privatemessages.toArchived = 0 AND
				privatemessages.toDeleted = 0";
	$res = mysqli_query($mysqli, $sql) or die('-1' . mysqli_error());

	
	if (isset($_GET['deleted']) && $_GET['deleted'] == 'yes') {
		$msgBox = alertBox($msgDelConf, "<i class='fa fa-check-square'></i>", "success");
	}

	$msgPage = 'true';
	$pageTitle = $messagesPageTitle;
	$addCss = '<link href="css/dataTables.css" rel="stylesheet">';
	$dataTables = 'true';
	$jsFile = 'messages';

	include 'includes/header.php';
?>
	<div class="container-fluid">
		<div class="container">
			<div class="row pageCont">
				<div class="col-md-12 pb-20">
					<?php if ($msgBox) { echo $msgBox; } ?>

					<input type="hidden" id="viewMsgText" value="<?php echo $viewMsaageHiddenText; ?>" />
					<input type="hidden" id="replyMsgText" value="<?php echo $replyMsgHiddenText; ?>" />
					<input type="hidden" id="markReadText" value="<?php echo $markReadHiddenText; ?>" />
					<input type="hidden" id="arcMsgText" value="<?php echo $arcMsgHiddenText; ?>" />
					<input type="hidden" id="delMsgText" value="<?php echo $delMsgHiddenText; ?>" />

					<div class="tabbed-panel">
						<div class="tabbed-line">
							<ul class="nav nav-tabs ">
								<li class="active"><a href="#inbox" data-toggle="tab"><?php echo $inboxNav; ?></a></li>
								<li><a href="index.php?page=sent"><?php echo $sentNav; ?></a></li>
								<li><a href="index.php?page=archived"><?php echo $archivedNav; ?></a></li>
								<li><a href="index.php?page=compose"><?php echo $composeNav; ?></a></li>
							</ul>
							<div class="tab-content">
								<?php if(mysqli_num_rows($res) > 0) { ?>
									<div class="row mt-20">
										<div class="col-md-7">
											<table id="msg" class="display" cellspacing="0" width="100%">
												<thead>
													<tr>
														<th></th>
														<th><?php echo $subjectTh; ?></th>
														<th class="text-center"><?php echo $fromTh; ?></th>
														<th class="text-right"><?php echo $receivedTh; ?></th>
														<th></th>
													</tr>
												</thead>

												<tbody>
													<?php
														while ($row = mysqli_fetch_assoc($res)) {
															if ($row['toRead'] == '0') {
																$readIcon = '<i class="fa fa-envelope text-info msg-status" data-toggle="tooltip" data-placement="top" title="'.$unreadText.'"></i>';
																$isUnread = 'msgUnread';
															} else {
																$readIcon = '<i class="fa fa-envelope-o text-success msg-status" data-toggle="tooltip" data-placement="top" title="'.$readText.'"></i>';
																$isUnread = '';
															}
													?>
															<tr class="msgLink <?php echo $isUnread; ?>">
																<td class="text-center"><?php echo $readIcon; ?></td>
																<td>
																	<?php echo clean($row['messageTitle']); ?>
																	<input type="hidden" name="fromName" value="<?php echo clean($row['msgFrom']); ?>" />
																	<input type="hidden" name="msgSubject" value="<?php echo clean($row['messageTitle']); ?>" />
																	<input type="hidden" name="dateSent" value="<?php echo dateFormat($row['messageDate']).' '.timeFormat($row['messageDate']); ?>" />
																	<input type="hidden" name="msgText" value="<?php echo nl2br(clean($row['messageText'])); ?>" />
																	<input type="hidden" name="msgId" value="<?php echo $row['messageId']; ?>" />
																	<input type="hidden" name="toRead" value="<?php echo clean($row['toRead']); ?>" />
																</td>
																<td class="text-center"><?php echo clean($row['msgFrom']); ?></td>
																<td class="text-right">
																	<?php echo shortMonthFormat($row['messageDate']); ?>

																	<div class="modal fade" id="reply<?php echo $row['messageId']; ?>" tabindex="-1" role="dialog" aria-hidden="true">
																		<div class="modal-dialog modal-lg">
																			<div class="modal-content">
																				<div class="modal-header">
																					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"><i class="fa fa-times"></i></span></button>
																					<h4 class="modal-title"><?php echo $sendReplyH4; ?></h4>
																				</div>
																				<form action="" method="post">
																					<div class="modal-body">
																						<div class="form-group">
																							<label for="messageTitle"><?php echo $subjectField; ?></label>
																							<input type="text" class="form-control" name="messageTitle" required="required" maxlength="50" value="re: <?php echo clean($row['messageTitle']); ?>" />
																						</div>
																						<div class="form-group">
																							<label for="messageText"><?php echo $messagesField; ?></label>
																							<textarea class="form-control" name="messageText" required="required" rows="5"></textarea>
																						</div>
																					</div>
																					<div class="modal-footer">
																						<input type="hidden" name="toId" value="<?php echo $row['fromId']; ?>" />
																						<input type="hidden" name="origId" value="<?php echo $row['messageId']; ?>" />
																						<input type="hidden" name="origMsgTitle" value="<?php echo clean($row['messageTitle']); ?>" />
																						<input type="hidden" name="sentFrom" value="<?php echo clean($row['msgFrom']); ?>" />
																						<button type="button" class="btn btn-default btn-sm btn-icon" data-dismiss="modal"><i class="fa fa-times-circle-o"></i> <?php echo $cancelBtn; ?></button>
																						<button type="input" name="submit" value="sendReply" class="btn btn-success btn-sm btn-icon"><i class="fa fa-check-square-o"></i> <?php echo $sendReplyBtn; ?></button>
																					</div>
																				</form>
																			</div>
																		</div>
																	</div>

																	<div class="modal fade" id="delete<?php echo $row['messageId']; ?>" tabindex="-1" role="dialog" aria-hidden="true">
																		<div class="modal-dialog">
																			<div class="modal-content">
																				<form action="" method="post">
																					<div class="modal-body">
																						<p class="lead mt-0"><?php echo $delMessageConf; ?> "<?php echo clean($row['messageTitle']); ?>"?</p>
																					</div>
																					<div class="modal-footer">
																						<input type="hidden" name="deleteId" value="<?php echo $row['messageId']; ?>" />
																						<input type="hidden" name="messageTitle" value="<?php echo $row['messageTitle']; ?>" />
																						<button type="button" class="btn btn-default btn-sm btn-icon" data-dismiss="modal"><i class="fa fa-times-circle-o"></i> <?php echo $cancelBtn; ?></button>
																						<button type="input" name="submit" value="deleteMsg" class="btn btn-success btn-sm btn-icon"><i class="fa fa-check-square-o"></i> <?php echo $yesBtn; ?></button>
																					</div>
																				</form>
																			</div>
																		</div>
																	</div>
																</td>
																<td><?php echo dbDateTimeFormat($row['messageDate']); ?></td>
															</tr>
													<?php } ?>
												</tbody>
											</table>
										</div>
										<div class="col-md-5">
											<div class="msgOptions text-right"></div>
											<h4 class="msg-title head-title msgHeading">
												<span class="whoFrom"></span> <i class="fa fa-long-arrow-right msgIcon"></i> You
												<small class="pull-right theDate"></small>
											</h4>
											<div class="msgTitle"></div>
											<div class="msgContent mb-20"></div>
											<div class="clearfix msgClear"></div>
											<div class="alert alert-default msgQuip" role="alert"><small><?php echo $selMessageQuip; ?></small></div>
										</div>
									</div>
								<?php } else { ?>
									<div class="alertMsg default">
										<div class="msgIcon pull-left">
											<i class="fa fa-envelope"></i>
										</div>
										<?php echo $noInboxMessages; ?>
									</div>
								<?php } ?>
							</div>
						</div>
					</div>

				</div>
			</div>
		</div>
	</div>