<?php
	$rptError = '';
	$reportName = $timeRptPageTitle;
	$where = array();

	if (!empty($_POST['userId']) && is_array($_POST['userId']) && !in_array('all',$_POST['userId'])) {
		$uids = array();
		foreach ($_POST['userId'] as $userId) {
		  $uids[] = $mysqli->real_escape_string($userId);
		}
		$userIds = '"'.implode('", "', $uids).'"';
		$where[] = 'timelogs.userId IN ('.$userIds.')';
	}

	if (!empty($_POST['fromDate'])) {
		$fromDate = $mysqli->real_escape_string($_POST['fromDate']);
		$where[] = 'timelogs.entryDate >= "'.$fromDate.'"';

		$fdate = dateFormat($fromDate);
	}
	if (!empty($_POST['toDate'])) {
		$toDate = $mysqli->real_escape_string($_POST['toDate']);
		$where[] = 'timelogs.entryDate <= "'.$toDate.'"';

		$tdate = dateFormat($toDate);
	}

	if (!empty($where)) {
		$whereSql = "WHERE\n" . implode("\nAND ",$where);
	}

	// Get Data
	$qry = "SELECT
				CONCAT(users.userFirst,' ',users.userMiddleInt,' ',users.userLast) AS theUser,
				timelogs.*,
				TIMEDIFF(timelogs.endTime,timelogs.startTime) AS totTime,
				(SELECT COUNT(*) FROM timelogentries WHERE timelogentries.timeId = timelogs.timeId) AS manCount,
				(SELECT COUNT(*) FROM timelogedits WHERE timelogedits.timeId = timelogs.timeId) AS editCount
			FROM
				timelogs
				LEFT JOIN users ON timelogs.userId = users.userId ".$whereSql;
	$res = mysqli_query($mysqli, $qry) or die('-1' . mysqli_error());

	if ($rptError == 'true') {
		$activityType = '0';
		$activityTitle = $tz_userFull.' '.$rptErrorAct.' '.$reportName;
		updateActivity($tz_userId,$activityType,$activityTitle);
	} else {
		$activityType = '21';
		$activityTitle = $tz_userFull.' '.$rptGeneratedAct.' '.$reportName;
		updateActivity($tz_userId,$activityType,$activityTitle);
	}

	$rptPage = 'true';
	$pageTitle = $reportName;
	$addCss = '<link href="css/dataTables.css" rel="stylesheet">';
	$dataTables = 'true';
	$jsFile = 'timeRpt';

	include 'includes/header.php';
?>
	<div class="container-fluid">
		<div class="container">
			<div class="row pageCont">
				<div class="col-md-12 pb-20">
					<?php
						if ((checkArray('TIMERPT', $auths)) || $tz_superUser != '') {
							if ($rptError == '') {
								if ($msgBox) { echo $msgBox; }
					?>
								<div class="mt-20"></div>
								<?php if(mysqli_num_rows($res) > 0) { ?>
									<table id="rpts" class="display" cellspacing="0" width="100%">
										<thead>
											<tr>
												<th class="text-center"><span data-toggle="tooltip" data-placement="top" title="<?php echo $usersIdTooltip; ?>"><?php echo $idSpanText; ?></span></th>
												<th><?php echo $nameTh; ?></th>
												<th class="text-center"><?php echo $ipAddressTh; ?></th>
												<th class="text-center"><?php echo $weekText; ?></th>
												<th class="text-center"><?php echo $manTimeEntryDateField; ?></th>
												<th class="text-center"><?php echo $manTimeInField; ?></th>
												<th class="text-center"><?php echo $manTimeOutField; ?></th>
												<th class="text-center"><?php echo $typeTh; ?></th>
												<th class="text-center"><?php echo $editsTh; ?></th>
												<th class="text-center"><?php echo $hoursText; ?></th>
												<th class="text-center"><?php echo $decimalTh; ?></th>
											</tr>
										</thead>

										<tbody>
											<?php
												$sumHrTotal = array();
												$sumDecHours = 0;
												while ($rows = mysqli_fetch_assoc($res)) {
													if ($rows['endTime'] == '0000-00-00 00:00:00') {
														$endTime = '<strong class="text-success">'.$runningText.'</strong>';
														$hrTotal = $decTotal = '';
													} else {
														$endTime = dbTimeFormat($rows['endTime']);
														$hrTotal = dbTimeFormat($rows['totTime']);
														$decTotal = decimalHours(dbTimeFormat($rows['totTime']));
													}
													if ($rows['manCount'] > 0) { $entType = '<strong class="text-warning">'.$manualText.'<strong>'; } else { $entType = $capturedText; }
													if ($rows['ipAddress'] == '::1') {
														$ipAddress = $locHostText;
													} else if ($rows['ipAddress'] == '') {
														$ipAddress = '<strong class="text-danger">'.$notSetText.'<strong>';
													} else {
														$ipAddress = $rows['ipAddress'];
													}
													
													array_push($sumHrTotal, $hrTotal.':00');
													$sumDecHours+= $decTotal;
											?>
													<tr>
														<td class="text-center"><?php echo $rows['userId']; ?></td>
														<td><?php echo clean($rows['theUser']); ?></td>
														<td class="text-center"><?php echo $ipAddress; ?></td>
														<td class="text-center"><?php echo $rows['weekNo']; ?></td>
														<td class="text-center"><?php echo dateFormat($rows['entryDate']); ?></td>
														<td class="text-center"><?php echo dbTimeFormat($rows['startTime']); ?></td>
														<td class="text-center"><?php echo $endTime; ?></td>
														<td class="text-center"><?php echo $entType; ?></td>
														<td class="text-center"><?php echo $rows['editCount']; ?></td>
														<td class="text-center"><?php echo $hrTotal; ?></td>
														<td class="text-center"><?php echo $decTotal; ?></td>
													</tr>
											<?php } ?>
										</tbody>
									</table>
									<div class="mt-20">
										<span class="alert alert-success" role="alert">
											<strong><?php echo $rptTotHoursText; ?>:</strong> <?php echo sumHours($sumHrTotal); ?>
										</span>
										&nbsp;
										<span class="alert alert-success" role="alert">
											<strong><?php echo $rptTotDecText; ?>:</strong> <?php echo $sumDecHours; ?>
										</span>
									</div>
								<?php
									} else {
								?>
									<div class="alertMsg default">
										<div class="msgIcon pull-left">
											<i class="fa fa-minus-circle"></i>
										</div>
										<?php echo $noRptDataFoundMsg; ?>
									</div>
								<?php } ?>
					<?php } else { ?>
							<div class="alertMsg warning">
								<div class="msgIcon pull-left">
									<i class="fa fa-warning"></i>
								</div>
								<?php echo $rptErrMsg; ?>
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