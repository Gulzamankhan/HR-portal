<?php
	
	$actIp = $_SERVER['REMOTE_ADDR'];

	
	if (isset($_POST['submit']) && $_POST['submit'] == 'newNotice') {
		
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

			$stmt = $mysqli->prepare("
								INSERT INTO
									notices(
										createdBy,
										adminOnly,
										isActive,
										noticeTitle,
										noticeText,
										noticeDate,
										noticeStart,
										noticeExpires,
										lastUpdated,
										ipAddress
									) VALUES (
										?,
										?,
										?,
										?,
										?,
										NOW(),
										?,
										?,
										NOW(),
										?
									)
			");
			$stmt->bind_param('ssssssss',
								$tz_userId,
								$adminOnly,
								$isActive,
								$noticeTitle,
								$noticeText,
								$noticeStart,
								$noticeExpires,
								$actIp
			);
			$stmt->execute();
			$stmt->close();

			
			$activityType = '17';
			$activityTitle = $tz_userFull.' '.$newNoticeAct.' "'.$noticeTitle.'"';
			updateActivity($tz_userId,$activityType,$activityTitle);

			$msgBox = alertBox($newNoticeMsg1." \"".$noticeTitle."\" ".$newEventMsg2, "<i class='fa fa-check-square'></i>", "success");

			
			$_POST['noticeStart'] = $_POST['noticeExpires'] = $_POST['noticeTitle'] = $_POST['noticeText'] = '';
		}
	}

	
	if (isset($_POST['submit']) && $_POST['submit'] == 'delete') {
		$deleteId = htmlspecialchars($_POST['deleteId']);
		$noticeTitle = htmlspecialchars($_POST['noticeTitle']);

		$stmt = $mysqli->prepare("DELETE FROM notices WHERE noticeId = ?");
		$stmt->bind_param('s', $deleteId);
		$stmt->execute();
		$stmt->close();

		
		$activityType = '17';
		$activityTitle = $tz_userFull.' '.$delNoticeAct.' "'.$noticeTitle.'"';
		updateActivity($tz_userId,$activityType,$activityTitle);

		$msgBox = alertBox($delNoticeMsg." \"".$noticeTitle."\" ".$delEventMsg2, "<i class='fa fa-check-square'></i>", "success");

		
		$_POST['noticeTitle'] = '';
	}

	
	$qry = "SELECT
				notices.*,
				CONCAT(users.userFirst,' ',users.userLast) AS createdBy
			FROM
				notices
				LEFT JOIN users ON notices.createdBy = users.userId";
	$res = mysqli_query($mysqli, $qry) or die('-1' . mysqli_error());

	$mngPage = 'true';
	$pageTitle = $mngNoticesPageTitle;
	$addCss = '<link href="css/dataTables.css" rel="stylesheet"><link rel="stylesheet" type="text/css" href="css/datetimepicker.css" />';
	$dataTables = 'true';
	$datePicker = 'true';
	$jsFile = 'mngNotices';

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
							<a href="#newNotice" data-toggle="modal" class="btn btn-hover btn-success btn-xs btn-icon pull-right"><i class="fa fa-plus"></i><span><?php echo $newNoticeLink; ?></span></a>
							<div class="modal fade" id="newNotice" tabindex="-1" role="dialog" aria-hidden="true">
								<div class="modal-dialog modal-lg">
									<div class="modal-content">
										<div class="modal-header">
											<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"><i class="fa fa-times"></i></span></button>
											<h4 class="modal-title"><?php echo $newNoticeH4; ?></h4>
										</div>
										<form action="" method="post">
											<div class="modal-body">
												<p><?php echo $newNoticeQuip; ?></p>
												<div class="row">
													<div class="col-md-6">
														<div class="form-group">
															<label for="isActive"><?php echo $noticeStatusField; ?></label>
															<select class="form-control" name="isActive" id="isActive">
																<option value="0"><?php echo $inactiveOption; ?></option>
																<option value="1"><?php echo $activeOption; ?></option>
															</select>
															<span class="help-block"><?php echo $noticeStatusFieldHelp; ?></span>
														</div>
													</div>
													<div class="col-md-6">
														<div class="form-group">
															<label for="adminOnly"><?php echo $noticeTypeField; ?></label>
															<select class="form-control" name="adminOnly" id="adminOnly">
																<option value="0"><?php echo $publicSelOpt; ?></option>
																<option value="1"><?php echo $privateSelOpt; ?></option>
															</select>
															<span class="help-block"><?php echo $noticeTypeFieldHelp; ?></span>
														</div>
													</div>
												</div>
												<div class="row">
													<div class="col-md-6">
														<div class="form-group">
															<label for="noticeStart"><?php echo $startDateField; ?></label>
															<input type="text" class="form-control" name="noticeStart" id="noticeStart" value="<?php echo isset($_POST['noticeStart']) ? $_POST['noticeStart'] : ''; ?>" />
															<span class="help-block"><?php echo $noticeStartHelp; ?></span>
														</div>
													</div>
													<div class="col-md-6">
														<div class="form-group">
															<label for="noticeExpires"><?php echo $endDateField; ?></label>
															<input type="text" class="form-control" name="noticeExpires" id="noticeExpires" value="<?php echo isset($_POST['noticeExpires']) ? $_POST['noticeExpires'] : ''; ?>" />
															<span class="help-block"><?php echo $noticeEndHelp; ?></span>
														</div>
													</div>
												</div>
												<div class="form-group">
													<label for="noticeTitle"><?php echo $noticeTitleField; ?></label>
													<input type="text" class="form-control" name="noticeTitle" id="noticeTitle" required="required" value="<?php echo isset($_POST['noticeTitle']) ? $_POST['noticeTitle'] : ''; ?>" />
												</div>
												<div class="form-group">
													<label for="noticeText"><?php echo $noticeTextField; ?></label>
													<textarea class="form-control" name="noticeText" id="noticeText" required="required" rows="4"><?php echo isset($_POST['noticeText']) ? $_POST['noticeText'] : ''; ?></textarea>
												</div>
											</div>
											<div class="modal-footer">
												<button type="button" class="btn btn-default btn-sm btn-icon" data-dismiss="modal"><i class="fa fa-times-circle-o"></i> <?php echo $cancelBtn; ?></button>
												<button type="input" name="submit" value="newNotice" class="btn btn-success btn-sm btn-icon"><i class="fa fa-check-square-o"></i> <?php echo $saveBtn; ?></button>
											</div>
										</form>
									</div>
								</div>
							</div>

							<div class="clearfix"></div>
							<hr />

							<?php if(mysqli_num_rows($res) > 0) { ?>
								<table id="notices" class="display" cellspacing="0" width="100%">
									<thead>
										<tr>
											<th><?php echo $noticeTitleField; ?></th>
											<th><?php echo $createdByTh; ?></th>
											<th class="text-center"><?php echo $dateCreatedTh; ?></th>
											<th class="text-center"><?php echo $startsOnTh; ?></th>
											<th class="text-center"><?php echo $endsOnTh; ?></th>
											<th class="text-center"><?php echo $activeOption; ?></th>
											<th class="text-center"><?php echo $privateSelOpt; ?></th>
											<th></th>
										</tr>
									</thead>

									<tbody>
										<?php
											while ($rows = mysqli_fetch_assoc($res)) {
												if ($rows['isActive'] == '1') { $isActive = $yesBtn; } else { $isActive = $noBtn; }
												if ($rows['adminOnly'] == '1') { $adminOnly = $yesBtn; } else { $adminOnly = $noBtn; }
												if ($rows['noticeStart'] == '0000-00-00 00:00:00') { $noticeStart = ''; } else { $noticeStart = shortMonthFormat($rows['noticeStart']); }
												if ($rows['noticeExpires'] == '0000-00-00 00:00:00') { $noticeExpires = ''; } else { $noticeExpires = shortMonthFormat($rows['noticeExpires']); }
										?>
												<tr>
													<td>
														<a href="index.php?page=viewNotice&noticeId=<?php echo $rows['noticeId']; ?>" data-toggle="tooltip" data-placement="right" title="<?php echo $viewNoticeTooltip; ?>">
															<?php echo clean($rows['noticeTitle']); ?>
														</a>
													</td>
													<td><?php echo clean($rows['createdBy']); ?></td>
													<td class="text-center"><?php echo shortMonthFormat($rows['noticeDate']); ?></td>
													<td class="text-center"><?php echo $noticeStart; ?></td>
													<td class="text-center"><?php echo $noticeExpires; ?></td>
													<td class="text-center"><?php echo $isActive; ?></td>
													<td class="text-center"><?php echo $adminOnly; ?></td>
													<td class="text-center">
														<a href="#delNotice<?php echo $rows['noticeId']; ?>" data-toggle="modal">
															<i class="fa fa-trash text-danger" data-toggle="tooltip" data-placement="left" title="<?php echo $delNoticeTooltip; ?>"></i>
														</a>

														<div class="modal fade" id="delNotice<?php echo $rows['noticeId']; ?>" tabindex="-1" role="dialog" aria-hidden="true">
															<div class="modal-dialog">
																<div class="modal-content">
																	<form action="" method="post">
																		<div class="modal-body">
																			<p class="lead mt-0"><?php echo $delNoticeConf; ?> "<?php echo clean($rows['noticeTitle']); ?>"?</p>
																		</div>
																		<div class="modal-footer">
																			<input type="hidden" name="deleteId" value="<?php echo $rows['noticeId']; ?>" />
																			<input type="hidden" name="noticeTitle" value="<?php echo clean($rows['noticeTitle']); ?>" />
																			<button type="button" class="btn btn-default btn-sm btn-icon" data-dismiss="modal"><i class="fa fa-times-circle-o"></i> <?php echo $cancelBtn; ?></button>
																			<button type="input" name="submit" value="delete" class="btn btn-success btn-sm btn-icon"><i class="fa fa-check-square-o"></i> <?php echo $yesBtn; ?></button>
																		</div>
																	</form>
																</div>
															</div>
														</div>
													</td>
												</tr>
										<?php } ?>
									</tbody>
								</table>
							<?php
								} else {
							?>
								<div class="alertMsg default">
									<div class="msgIcon pull-left">
										<i class="fa fa-check"></i>
									</div>
									<?php echo $noNoticesFoundMsg; ?>
								</div>
							<?php } ?>

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