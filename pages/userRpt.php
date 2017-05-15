<?php
	$reportName = $reportType = $showStatus = $showTrmDate = $rptError = '';
	$where = array();

	switch ($_POST['rptType']) {
		case "usrReport":
			$usrType = $mysqli->real_escape_string($_POST['usrType']);

			if ($usrType == 'all') {
				$where[] = 'users.isAdmin = 0';
				$reportName = $userRptPageTitle1;
				$showStatus = '1';
				$showTrmDate = '0';
			} else if ($usrType == 'active') {
				$where[] = 'users.isActive = 1 AND users.isAdmin = 0';
				$reportName = $userRptPageTitle2;
				$showStatus = '0';
				$showTrmDate = '0';
			} else if ($usrType == 'inactive') {
				$where[] = 'users.isActive = 0 AND users.isAdmin = 0';
				$reportName = $userRptPageTitle3;
				$showStatus = '0';
				$showTrmDate = '1';
			} else {
				$rptError = 'true';
				$reportName = $userRptPageTitle4;
				$showStatus = '0';
				$showTrmDate = '0';
			}
			$reportType = 'user';
		break;
		case "admReport":
			$mgrType = $mysqli->real_escape_string($_POST['mgrType']);

			if ($mgrType == 'all') {
				$where[] = 'users.isAdmin = 1';
				$reportName = $userRptPageTitle5;
				$showStatus = '1';
				$showTrmDate = '0';
			} else if ($mgrType == 'active') {
				$where[] = 'users.isActive = 1 AND users.isAdmin = 1';
				$reportName = $userRptPageTitle6;
				$showStatus = '0';
				$showTrmDate = '0';
			} else if ($mgrType == 'inactive') {
				$where[] = 'users.isActive = 0 AND users.isAdmin = 1';
				$reportName = $userRptPageTitle7;
				$showStatus = '0';
				$showTrmDate = '1';
			} else {
				$rptError = 'true';
				$reportName = $userRptPageTitle8;
				$showStatus = '0';
				$showTrmDate = '0';
			}
			$reportType = 'admin';
		break;
		default: $rptError = 'true'; break;
	}

	if (!empty($where)) {
		$whereSql = "WHERE\n" . implode($where);
	}

	$sql = "SELECT
				users.*,
				CONCAT(usr.userFirst,' ',usr.userLast) AS theManager,
				(SELECT COUNT(*) FROM events WHERE events.userId = users.userId) AS eventCount,
				(SELECT COUNT(*) FROM privatemessages WHERE privatemessages.toId = users.userId) AS msgCount,
				(SELECT COUNT(*) FROM tasks WHERE tasks.assignedTo = users.userId) AS taskCount
			FROM
				users
				LEFT JOIN users AS usr ON users.managerId = usr.userId ".$whereSql;
	$res = mysqli_query($mysqli, $sql) or die('-1' . mysqli_error());

	if ($rptError == 'true') {
		$activityType = '0';
		$activityTitle = $tz_userFull.' '.$rptErrorAct.' '.$reportName;
		updateActivity($tz_userId,$activityType,$activityTitle);
	} else {
		$activityType = '20';
		$activityTitle = $tz_userFull.' '.$rptGeneratedAct.' '.$reportName;
		updateActivity($tz_userId,$activityType,$activityTitle);
	}

	$rptPage = 'true';
	$pageTitle = $reportName;
	$addCss = '<link href="css/dataTables.css" rel="stylesheet">';
	$dataTables = 'true';
	$jsFile = 'userRpt';

	include 'includes/header.php';
?>
	<div class="container-fluid">
		<div class="container">
			<div class="row pageCont">
				<div class="col-md-12 pb-20">
					<?php
						if ((checkArray('USRRPT', $auths)) || $tz_superUser != '') {
							if ($rptError == '') {
								if ($msgBox) { echo $msgBox; }
					?>
								<div class="mt-20"></div>
								<?php if(mysqli_num_rows($res) > 0) { ?>
									<table id="rpts" class="display" cellspacing="0" width="100%">
										<thead>
											<tr>
												<th class="text-center"><span data-toggle="tooltip" data-placement="top" title="<?php echo $usersIdTooltip; ?>"><?php echo $idSpanText; ?></span></th>
												<?php if ($reportType == 'admin') { ?>
													<th class="text-center"><?php echo $superUserText; ?></th>
												<?php } ?>
												<th><?php echo $nameTh; ?></th>
												<th><?php echo $positionTh; ?></th>
												<th><?php echo $managerText; ?></th>
												<?php if ($showStatus == '1') { ?>
													<th class="text-center"><?php echo $statusTh; ?></th>
												<?php } ?>
												<th class="text-center"><?php echo $hireDtTh; ?></th>
												<?php if ($showTrmDate == '1') { ?>
													<th class="text-center"><?php echo $termDateTh; ?></th>
												<?php } ?>
												<th class="text-center"><?php echo $lastSignInTh; ?></th>
												<th class="text-center"><span data-toggle="tooltip" data-placement="top" title="<?php echo $taskCountTooltip; ?>"><i class="fa fa-list"></i></span></th>
												<th class="text-center"><span data-toggle="tooltip" data-placement="top" title="<?php echo $msgCountTooltip; ?>"><i class="fa fa-comments"></i></span></th>
												<th class="text-center"><span data-toggle="tooltip" data-placement="top" title="<?php echo $eventCountTooltip; ?>"><i class="fa fa-calendar-o"></i></span></th>
											</tr>
										</thead>

										<tbody>
											<?php
												while ($rows = mysqli_fetch_assoc($res)) {
													if ($rows['lastVisited'] != '0000-00-00 00:00:00') { $lastVisited = shortMonthFormat($rows['lastVisited']).' '.timeFormat($rows['lastVisited']); } else { $lastVisited = ''; }
													if ($rows['terminationDate'] != '0000-00-00 00:00:00') { $terminationDate = shortMonthFormat($rows['terminationDate']); } else { $terminationDate = ''; }
													if ($rows['superUser'] == '1') { $superUser = '<strong class="text-warning">'.$yesBtn.'</strong>'; } else { $superUser = $noBtn; }
													if ($rows['isActive'] == '1') { $usrStatus = '<strong class="text-success">'.$activeOption.'</strong>'; } else { $usrStatus = '<strong class="text-danger">'.$inactiveOption.'</strong>'; }
											?>
													<tr>
														<td class="text-center"><?php echo $rows['userId']; ?></td>
														<?php if ($reportType == 'admin') { ?>
															<td class="text-center"><?php echo $superUser; ?></td>
														<?php } ?>
														<td><?php echo clean($rows['userFirst']).' '.clean($rows['userMiddleInt']).' '.clean($rows['userLast']); ?></td>
														<td><?php echo clean($rows['userPosition']); ?></td>
														<td><?php echo clean($rows['theManager']); ?></td>
														<?php if ($showStatus == '1') { ?>
															<td class="text-center"><?php echo $usrStatus; ?></td>
														<?php } ?>
														<td class="text-center"><?php echo shortMonthFormat($rows['userHireDate']); ?></td>
														<?php if ($showTrmDate == '1') { ?>
															<td class="text-center"><?php echo $terminationDate; ?></td>
														<?php } ?>
														<td class="text-center"><?php echo $lastVisited; ?></td>
														<td class="text-center"><?php echo $rows['taskCount']; ?></td>
														<td class="text-center"><?php echo $rows['msgCount']; ?></td>
														<td class="text-center"><?php echo $rows['eventCount']; ?></td>
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