<?php
	$rptError = '';
	$where = array();

	$rptType = $mysqli->real_escape_string($_POST['rptType']);

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

	switch ($rptType) {
		case "edits":
			$reportName = $timeEditRptPageTitle1;
			$qry = "SELECT
						timelogedits.userId,
						timelogedits.timeId,
						timelogedits.editReason AS reason,
						timelogedits.ipAddress,
						timelogs.weekNo,
						timelogs.entryDate,
						timelogs.startTime,
						timelogs.endTime,
						CONCAT(users.userFirst,' ',users.userMiddleInt,' ',users.userLast) AS theUser
					FROM
						timelogedits
						LEFT JOIN timelogs ON timelogedits.timeId = timelogs.timeId
						LEFT JOIN users ON timelogedits.userId = users.userId ".$whereSql;
			$res = mysqli_query($mysqli, $qry) or die('-1' . mysqli_error());
		break;
		case "manual":
			$reportName = $timeEditRptPageTitle2;
			$qry = "SELECT
						timelogentries.userId,
						timelogentries.timeId,
						timelogentries.manualReason AS reason,
						timelogentries.ipAddress,
						timelogs.weekNo,
						timelogs.entryDate,
						timelogs.startTime,
						timelogs.endTime,
						CONCAT(users.userFirst,' ',users.userMiddleInt,' ',users.userLast) AS theUser
					FROM
						timelogentries
						LEFT JOIN timelogs ON timelogentries.timeId = timelogs.timeId
						LEFT JOIN users ON timelogentries.userId = users.userId ".$whereSql;
			$res = mysqli_query($mysqli, $qry) or die('-2' . mysqli_error());
		break;
		default: $rptError = 'true'; break;
	}

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
	$jsFile = 'timeEditRpt';

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
												<th><?php echo $reasonTh; ?></th>
											</tr>
										</thead>

										<tbody>
											<?php
												while ($rows = mysqli_fetch_assoc($res)) {
													if ($rows['endTime'] == '0000-00-00 00:00:00') {
														$endTime = '<strong class="text-success">'.$runningText.'</strong>';
													} else {
														$endTime = dbTimeFormat($rows['endTime']);
													}
													if ($rows['ipAddress'] == '::1') {
														$ipAddress = $locHostText;
													} else if ($rows['ipAddress'] == '') {
														$ipAddress = '<strong class="text-danger">'.$notSetText.'<strong>';
													} else {
														$ipAddress = $rows['ipAddress'];
													}
											?>
													<tr>
														<td class="text-center"><?php echo $rows['userId']; ?></td>
														<td><?php echo clean($rows['theUser']); ?></td>
														<td class="text-center"><?php echo $ipAddress; ?></td>
														<td class="text-center"><?php echo $rows['weekNo']; ?></td>
														<td class="text-center"><?php echo dateFormat($rows['entryDate']); ?></td>
														<td class="text-center"><?php echo dbTimeFormat($rows['startTime']); ?></td>
														<td class="text-center"><?php echo $endTime; ?></td>
														<td><?php echo clean($rows['reason']); ?></td>
													</tr>
											<?php } ?>
										</tbody>
									</table>
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