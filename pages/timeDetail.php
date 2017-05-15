<?php
	$getYear = htmlspecialchars($_GET['year']);
	$getWeek = htmlspecialchars($_GET['week']);

	$actIp = $_SERVER['REMOTE_ADDR'];

	if ($set['enableTimeEdits'] == '1') {
		if (isset($_POST['submit']) && $_POST['submit'] == 'timeEntry') {
		
			if($_POST['manualReason'] == '') {
				$msgBox = alertBox($manTimeReasonReq, "<i class='fa fa-times-circle'></i>", "danger");
			} else if($_POST['entryDate'] == '') {
				$msgBox = alertBox($manTimeDateReq, "<i class='fa fa-times-circle'></i>", "danger");
			} else if($_POST['entryTimeIn'] == '') {
				$msgBox = alertBox($manTimeInReq, "<i class='fa fa-times-circle'></i>", "danger");
			} else {
				$manualReason = htmlspecialchars($_POST['manualReason']);
				$entryDate = htmlspecialchars($_POST['entryDate']);
				$entryTimeIn = htmlspecialchars($_POST['entryTimeIn']);

				if($_POST['entryTimeOut'] != '') {
					$entryTimeOut = htmlspecialchars($_POST['entryTimeOut']);
					$endTime = $entryDate.' '.$entryTimeOut.':00';
				} else {
					$endTime = '0000-00-00 00:00:00';
				}

				$theYear = date("Y", strtotime($entryDate));
				$theWeek = date("W", strtotime($entryDate));
				$startTime = $entryDate.' '.$entryTimeIn.':00';

				$stmt = $mysqli->prepare("
									INSERT INTO
										timelogs(
											userId,
											weekNo,
											clockYear,
											entryDate,
											startTime,
											endTime,
											entryType,
											lastUpdated,
											ipAddress
										) VALUES (
											?,
											?,
											?,
											?,
											?,
											?,
											0,
											NOW(),
											?
										)");
				$stmt->bind_param('sssssss',
					$tz_userId,
					$theWeek,
					$theYear,
					$entryDate,
					$startTime,
					$endTime,
					$actIp
				);
				$stmt->execute();
				$stmt->close();

				$te = "SELECT timeId
						FROM timelogs
						WHERE
							userId = ".$tz_userId." AND
							weekNo = '".$theWeek."' AND
							clockYear = '".$theYear."' AND
							entryDate = '".$entryDate."' AND
							startTime = '".$startTime."' AND
							endTime = '".$endTime."'
						LIMIT 1";
				$teres = mysqli_query($mysqli, $te) or die('-1'.mysqli_error());
				$terow = mysqli_fetch_assoc($teres);
				$timeId = $terow['timeId'];

				$stmt = $mysqli->prepare("
									INSERT INTO
										timelogentries(
											timeId,
											userId,
											manualReason,
											lastUpdated,
											ipAddress
										) VALUES (
											?,
											?,
											?,
											NOW(),
											?
										)");
				$stmt->bind_param('ssss',
					$timeId,
					$tz_userId,
					$manualReason,
					$actIp
				);
				$stmt->execute();
				$stmt->close();

				$logDate = shortMonthFormat($entryDate);

				$activityType = '14';
				$activityTitle = $tz_userFull.' '.$manTimeAct.' '.$logDate;
				updateActivity($tz_userId,$activityType,$activityTitle);

				$msgBox = alertBox($manTimeMsg." ".$logDate." ".$newEventMsg2, "<i class='fa fa-check-square'></i>", "success");
			}
		}

		if (isset($_POST['submit']) && $_POST['submit'] == 'editEntry') {
		
			if($_POST['editReason'] == '') {
				$msgBox = alertBox($timeEditReasonReq, "<i class='fa fa-times-circle'></i>", "danger");
			} else if($_POST['editTimeIn'] == '') {
				$msgBox = alertBox($manTimeInReq, "<i class='fa fa-times-circle'></i>", "danger");
			} else {
				$editReason = htmlspecialchars($_POST['editReason']);
				$editTimeIn = htmlspecialchars($_POST['editTimeIn']);
				$timeId = htmlspecialchars($_POST['timeId']);
				$entryDate = htmlspecialchars($_POST['entryDate']);

				if($_POST['editTimeOut'] != '') {
					$editTimeOut = htmlspecialchars($_POST['editTimeOut']);
					$timeOut = $entryDate.' '.$editTimeOut.':00';
				} else {
					$timeOut = '0000-00-00 00:00:00';
				}

				$timeIn = $entryDate.' '.$editTimeIn.':00';

				$stmt = $mysqli->prepare("UPDATE
											timelogs
										SET
											startTime = ?,
											endTime = ?,
											edited = 1,
											lastUpdated = NOW(),
											ipAddress = ?
										WHERE
											timeId = ?"
				);
				$stmt->bind_param('ssss',
										$timeIn,
										$timeOut,
										$actIp,
										$timeId
				);
				$stmt->execute();
				$stmt->close();

				$stmt = $mysqli->prepare("
									INSERT INTO
										timelogedits(
											timeId,
											userId,
											editReason,
											lastUpdated,
											ipAddress
										) VALUES (
											?,
											?,
											?,
											NOW(),
											?
										)");
				$stmt->bind_param('ssss',
					$timeId,
					$tz_userId,
					$editReason,
					$actIp
				);
				$stmt->execute();
				$stmt->close();

				$logDate = shortMonthFormat($entryDate);

				$activityType = '14';
				$activityTitle = $tz_userFull.' '.$editedTimeForText.' '.$logDate;
				updateActivity($tz_userId,$activityType,$activityTitle);

				$msgBox = alertBox($theTimeEntryForMsg." ".$logDate." ".$editEventMsg, "<i class='fa fa-check-square'></i>", "success");
			}
		}

		if (isset($_POST['submit']) && $_POST['submit'] == 'delEntry') {
			$deleteId = htmlspecialchars($_POST['deleteId']);
			$entryDate = htmlspecialchars($_POST['entryDate']);

			$stmt = $mysqli->prepare("DELETE FROM timelogs WHERE timeId = ?");
			$stmt->bind_param('s', $deleteId);
			$stmt->execute();
			$stmt->close();

			$activityType = '15';
			$activityTitle = $tz_userFull.' '.$delTimeEntAct.' '.$entryDate;
			updateActivity($tz_userId,$activityType,$activityTitle);

			$msgBox = alertBox($theTimeEntryForMsg." ".$entryDate." ".$delEventMsg2, "<i class='fa fa-check-square'></i>", "success");
		}
	}
	
	if ($set['weekStart'] == '0') {
		$wkDayStart = date("Y-m-d", strtotime($getYear."W".$getWeek."1"."-1 day"));
		$wkDayEnd = date("Y-m-d", strtotime($getYear."W".$getWeek."7"."-1 day"));
	} else {
		$wkDayStart = date("Y-m-d", strtotime($getYear."W".$getWeek."1"));
		$wkDayEnd = date("Y-m-d", strtotime($getYear."W".$getWeek."7"));
	}
	
	$wkDayStart1 = date('M j, Y',strtotime($wkDayStart));
	$wkDayEnd1 = date('M j, Y',strtotime($wkDayEnd));

	$qry = "SELECT
				*,
				TIMEDIFF(endTime,startTime) AS totTime
			FROM timelogs
			WHERE
				(entryDate BETWEEN '".$wkDayStart."' AND '".$wkDayEnd."') AND
				userId = ".$tz_userId;
	$res = mysqli_query($mysqli, $qry) or die('-2' . mysqli_error());

	$totHr = "SELECT
				TIMEDIFF(endTime,startTime) AS diff
			FROM
				timelogs
			WHERE
				userId = ".$tz_userId." AND
				(entryDate BETWEEN '".$wkDayStart."' AND '".$wkDayEnd."') AND
				endTime != '00:00:00'";
	$totHrres = mysqli_query($mysqli, $totHr) or die('-3'.mysqli_error());
	$totHrs = array();

	while ($thrs = mysqli_fetch_assoc($totHrres)) {
		$totHrs[] = $thrs['diff'];
	}

	$totalWeekHours = sumHours($totHrs);

	$acctPage = 'true';
	$pageTitle = $timeDeatilPageTitle;
	$addCss = '<link href="css/dataTables.css" rel="stylesheet"><link rel="stylesheet" type="text/css" href="css/datetimepicker.css" />';
	$dataTables = 'true';
	$datePicker = 'true';
	$jsFile = 'timeDetail';

	include 'includes/header.php';
?>
	<div class="container-fluid">
		<div class="container">
			<div class="row pageCont">
				<div class="col-md-12 pb-20">
					<?php if ($msgBox) { echo $msgBox; } ?>
					<h3 class="head-title"><?php echo $timeLogsForH3; ?> <?php echo $wkDayStart1; ?> <?php echo $throughText; ?> <?php echo $wkDayEnd1; ?></h3>
					<input type="hidden" id="calStart" value="<?php echo $wkDayStart; ?>" />
					<input type="hidden" id="calEnd" value="<?php echo $wkDayEnd; ?>" />

					<div class="row mb-10">
						<div class="col-md-8">
							<span class="alert alert-success" role="alert"><strong><?php echo $weekText; ?> <?php echo $getWeek; ?> <?php echo $totalHoursText; ?>:</strong> <?php echo $totalWeekHours; ?></span>
						</div>
						<div class="col-md-4">
							<?php if ($set['enableTimeEdits'] == '1') { ?>
								<a href="#timeEntry" data-toggle="modal" class="btn btn-hover btn-info btn-xs pull-right"><i class="fa fa-clock-o"></i><span><?php echo $manTimeEntryBtn; ?></span></a>
								<div class="modal fade" id="timeEntry" tabindex="-1" role="dialog" aria-hidden="true">
									<div class="modal-dialog">
										<div class="modal-content">
											<div class="modal-header">
												<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"><i class="fa fa-times"></i></span></button>
												<h4 class="modal-title"><?php echo $manTimeEntryBtn; ?></h4>
											</div>
											<form action="" method="post">
												<div class="modal-body">
													<div class="form-group">
														<label for="manualReason"><?php echo $manTimeReasonField; ?></label>
														<input type="text" class="form-control" name="manualReason" id="manualReason" maxlength="50" required="required" value="" />
													</div>
													<div class="row">
														<div class="col-md-4">
															<div class="form-group">
																<label for="entryDate"><?php echo $manTimeEntryDateField; ?></label>
																<input type="text" class="form-control" name="entryDate" id="entryDate" required="required" value="" />
															</div>
														</div>
														<div class="col-md-4">
															<div class="form-group">
																<label for="entryTimeIn"><?php echo $manTimeInField; ?></label>
																<input type="text" class="form-control" name="entryTimeIn" id="entryTimeIn" required="required" value="" />
															</div>
														</div>
														<div class="col-md-4">
															<div class="form-group">
																<label for="entryTimeOut"><?php echo $manTimeOutField; ?></label>
																<input type="text" class="form-control" name="entryTimeOut" id="entryTimeOut" value="" />
															</div>
														</div>
													</div>
												</div>
												<div class="modal-footer">
													<button type="button" class="btn btn-default btn-sm btn-icon" data-dismiss="modal"><i class="fa fa-times-circle-o"></i> <?php echo $cancelBtn; ?></button>
													<button type="input" name="submit" value="timeEntry" class="btn btn-success btn-sm btn-icon"><i class="fa fa-check-square-o"></i> <?php echo $saveBtn; ?></button>
												</div>
											</form>
										</div>
									</div>
								</div>
							<?php } ?>
						</div>
					</div>

					<table id="timelogs" class="display" cellspacing="0" width="100%">
						<thead>
							<tr>
								<th><?php echo $manTimeEntryDateField; ?></th>
								<th class="text-center"><?php echo $manTimeInField; ?></th>
								<th class="text-center"><?php echo $manTimeOutField; ?></th>
								<th class="text-center"><?php echo $typeTh; ?></th>
								<th class="text-center"><?php echo $editedTh; ?></th>
								<th class="text-center"><?php echo $notesTh; ?></th>
								<th class="text-center"><?php echo $totalTh; ?></th>
								<?php if ($set['enableTimeEdits'] == '1') { ?>
									<th></th>
								<?php } ?>
							</tr>
						</thead>

						<tbody>
							<?php
								while ($row = mysqli_fetch_assoc($res)) {
									if ($row['endTime'] == '0000-00-00 00:00:00') {
										$endTime = '<strong class="text-success">'.$runningText.'</strong>';
										$lineTotal = '';
										$editEndTime = '';
										$isRunning = '1';
									} else {
										$endTime = $editEndTime = dbTimeFormat($row['endTime']);
										$lineTotal = $row['totTime'];
										$isRunning = '0';
									}
									if ($row['entryType'] == '1') { $entryType = $capturedText; } else { $entryType = '<strong class="text-warning">'.$manualText.'<strong>'; }
									if ($row['edited'] == '1') { $edited = '<strong class="text-danger">'.$yesBtn.'</strong>'; } else { $edited = $noBtn; }

									$sql = "SELECT manualReason FROM timelogentries WHERE timeId = ".$row['timeId'];
									$result = mysqli_query($mysqli, $sql) or die('-4' . mysqli_error());
									$rows = mysqli_fetch_assoc($result);

									$stm = "SELECT editReason FROM timelogedits WHERE timeId = ".$row['timeId'];
									$results = mysqli_query($mysqli, $stm) or die('-5' . mysqli_error());
									$cols = mysqli_fetch_assoc($results);
							?>
									<tr>
										<td><?php echo dateFormat($row['entryDate']); ?></td>
										<td class="text-center"><?php echo dbTimeFormat($row['startTime']); ?></td>
										<td class="text-center"><?php echo $endTime; ?></td>
										<td class="text-center"><?php echo $entryType; ?></td>
										<td class="text-center"><?php echo $edited; ?></td>
										<td class="text-center"><?php echo clean($cols['editReason']); ?></td>
										<td class="text-center"><?php echo $lineTotal; ?></td>
										<?php if ($set['enableTimeEdits'] == '1') { ?>
											<td class="text-right">
												<a href="#editEntry<?php echo $row['timeId']; ?>" data-toggle="modal">
													<i class="fa fa-edit text-warning" data-toggle="tooltip" data-placement="left" title="<?php echo $editTimeEntTooltip; ?>"></i>
												</a>

												<div class="modal fade" id="editEntry<?php echo $row['timeId']; ?>" tabindex="-1" role="dialog" aria-hidden="true">
													<div class="modal-dialog">
														<div class="modal-content">
															<div class="modal-header">
																<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"><i class="fa fa-times"></i></span></button>
																<h4 class="modal-title"><?php echo $editTimeEntH4; ?> <?php echo dateFormat($row['entryDate']); ?></h4>
															</div>
															<form action="" method="post">
																<div class="modal-body">
																	<div class="form-group">
																		<label for="editReason"><?php echo $reasonForEditField; ?></label>
																		<input type="text" class="form-control" name="editReason" id="editReason" maxlength="50" required="required" value="" />
																	</div>
																	<div class="row">
																		<div class="col-md-6">
																			<div class="form-group">
																				<label for="editTimeIn"><?php echo $manTimeInField; ?></label>
																				<input type="text" class="form-control" name="editTimeIn" required="required" value="<?php echo dbTimeFormat($row['startTime']); ?>" />
																			</div>
																		</div>
																		<div class="col-md-6">
																			<div class="form-group">
																				<label for="editTimeOut"><?php echo $manTimeOutField; ?></label>
																				<input type="text" class="form-control" name="editTimeOut" value="<?php echo $editEndTime; ?>" />
																			</div>
																		</div>
																	</div>
																</div>
																<div class="modal-footer">
																	<input type="hidden" name="timeId" value="<?php echo $row['timeId']; ?>" />
																	<input type="hidden" name="entryDate" value="<?php echo $row['entryDate']; ?>" />
																	<button type="button" class="btn btn-default btn-sm btn-icon" data-dismiss="modal"><i class="fa fa-times-circle-o"></i> <?php echo $cancelBtn; ?></button>
																	<button type="input" name="submit" value="editEntry" class="btn btn-success btn-sm btn-icon"><i class="fa fa-check-square-o"></i> <?php echo $saveChangesBtn; ?></button>
																</div>
															</form>
														</div>
													</div>
												</div>

												<?php if ($isRunning == '0') { ?>
													<a href="#delEntry<?php echo $row['timeId']; ?>" data-toggle="modal">
														<i class="fa fa-trash text-danger" data-toggle="tooltip" data-placement="left" title="Delete Time Entry"></i>
													</a>

													<div class="modal fade" id="delEntry<?php echo $row['timeId']; ?>" tabindex="-1" role="dialog" aria-hidden="true">
														<div class="modal-dialog">
															<div class="modal-content">
																<form action="" method="post">
																	<div class="modal-body">
																		<p class="lead mt-0"><?php echo $delTimeEntryConf; ?> "<?php echo dateFormat($row['entryDate']); ?> &mdash; <?php echo dbTimeFormat($row['startTime']); ?> <?php echo $toText; ?> <?php echo $endTime; ?>"?</p>
																	</div>
																	<div class="modal-footer">
																		<input type="hidden" name="deleteId" value="<?php echo $row['timeId']; ?>" />
																		<input type="hidden" name="entryDate" value="<?php echo dateFormat($row['entryDate']); ?>" />
																		<button type="button" class="btn btn-default btn-sm btn-icon" data-dismiss="modal"><i class="fa fa-times-circle-o"></i> <?php echo $cancelBtn; ?></button>
																		<button type="input" name="submit" value="delEntry" class="btn btn-success btn-sm btn-icon"><i class="fa fa-check-square-o"></i> <?php echo $yesBtn; ?></button>
																	</div>
																</form>
															</div>
														</div>
													</div>
												<?php } else { ?>
													<i class="fa fa-trash text-muted" data-toggle="tooltip" data-placement="left" title="<?php echo $disabledTooltip; ?>"></i>
												<?php } ?>
											</td>
										<?php } ?>
									</tr>
							<?php } ?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>