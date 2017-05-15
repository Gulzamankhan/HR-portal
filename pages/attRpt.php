<?php
	$reportType = $showStatus = $showTrmDate = $rptError = '';
	$reportName = $timeRptPageTitle;
	$where = array();

	if (!empty($_POST['userId']) && is_array($_POST['userId']) && !in_array('all',$_POST['userId'])) {
		$uids = array();
		foreach ($_POST['userId'] as $userId) {
		  $uids[] = $mysqli->real_escape_string($userId);
		}
		$userIds = '"'.implode('", "', $uids).'"';
		$where[] = 'schedule.userId IN ('.$userIds.')';
	}

	if (!empty($_POST['fromDate'])) {
		$fromDate = $mysqli->real_escape_string($_POST['fromDate']);
		$where[] = 'schedule.startDate >= "'.$fromDate.'"';

		$fdate = dateFormat($fromDate);
	}
	if (!empty($_POST['toDate'])) {
		$toDate = $mysqli->real_escape_string($_POST['toDate']);
		$where[] = 'schedule.startDate <= "'.$toDate.'"';

		$tdate = dateFormat($toDate);
	}

	if (!empty($where)) {
		$whereSql = "WHERE\n" . implode("\nAND ",$where);
	}



	$qry = "SELECT
				CONCAT(users.userId) AS userNum,
				CONCAT(users.userFirst,' ',users.userLast) AS theUser,
				schedule.*,
				TIMEDIFF(schedule.endTime,schedule.startTime) AS totTime,
				(SELECT COUNT(*) FROM timelogentries WHERE timelogentries.timeId = schedule.schedId) AS manCount
			FROM
				schedule
				LEFT JOIN users ON schedule.userId = users.userId ".$whereSql;
	$res = mysqli_query($mysqli, $qry) or die('-1' . mysqli_error());

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
												<?php if ($reportType == 'admin') { ?>
													<th class="text-center"><?php echo $superUserText; ?></th>
												<?php } ?>

												<th><?php echo $nameTh; ?></th>
												<th style="font-weight: bold;">Date</th>
												
												<th>1</th>
												
												<th> 2</th>
												<th> 3</th>
												<th> 4</th>
												<th> 5</th>
												<th> 6</th>
												<th> 7</th>
												<th> 8</th>
												<th> 9</th>
												<th> 10</th>
												<th> 11</th>
												<th> 12</th>
												<th> 13</th>
												<th> 14</th>
												<th> 15</th>
												<th> 16</th>
												<th> 17</th>
												<th> 18</th>
												<th> 19</th>
												<th> 20</th>
												<th> 21</th>
												<th> 22</th>
												<th> 23</th>
												<th> 24</th>
												<th> 25</th>
												<th> 26</th>
												<th> 27</th>
												<th> 28</th>
												<th> 29</th>
												<th> 30</th>
												<th> 31</th>
												<th style="font-weight: bold;">Total Hrs</th>

											</tr>
										</thead>

										<tbody style="  text-align: -webkit-center;">
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
														
														<?php if ($reportType == 'admin') { ?>
															<td class="text-center"><?php echo $superUser; ?></td>
														<?php } ?>
														<td><?php echo clean($rows['theUser']); ?></td>
														<td style="font-weight: bold;">Hrs</td>
														<?php
												$daysel1 = "SELECT MAX(schedule.day1) AS daynum1 FROM schedule WHERE startDate BETWEEN STR_TO_DATE('$fromDate', '%Y-%m-%d') AND STR_TO_DATE('$toDate', '%Y-%m-%d') AND userId = ". $rows['userId'];
												$dayssel1 = mysqli_query($mysqli, $daysel1) or die('-1' . mysqli_error());
												$daysnum1 = mysqli_fetch_assoc($dayssel1);
												$daynum1 = $daysnum1['daynum1']; { ?>
														<td><?php echo $daynum1; ?></td>
														<?php } ?>
<?php
																	$daysel2 = "SELECT MAX(schedule.day2) AS daynum2 FROM schedule WHERE startDate BETWEEN STR_TO_DATE('$fromDate', '%Y-%m-%d') AND STR_TO_DATE('$toDate', '%Y-%m-%d') AND userId = ". $rows['userId'];
			$dayssel2 = mysqli_query($mysqli, $daysel2) or die('-2' . mysqli_error());
			$daysnum2 = mysqli_fetch_assoc($dayssel2);
			$daynum2 = $daysnum2['daynum2'];{ ?>
														<td><?php echo $daynum2; ?></td>
														<?php } ?>
			<?php
			$daysel3 = "SELECT MAX(schedule.day3) AS daynum3 FROM schedule WHERE startDate BETWEEN STR_TO_DATE('$fromDate', '%Y-%m-%d') AND STR_TO_DATE('$toDate', '%Y-%m-%d') AND userId = ". $rows['userId'];
			$dayssel3 = mysqli_query($mysqli, $daysel3) or die('-3' . mysqli_error());
			$daysnum3 = mysqli_fetch_assoc($dayssel3);
			$daynum3 = $daysnum3['daynum3'];{ ?>
														<td><?php echo $daynum3; ?></td>
														<?php } ?>
<?php
						$daysel4 = "SELECT MAX(schedule.day4) AS daynum4 FROM schedule WHERE startDate BETWEEN STR_TO_DATE('$fromDate', '%Y-%m-%d') AND STR_TO_DATE('$toDate', '%Y-%m-%d') AND userId = ". $rows['userId'];
			$dayssel4 = mysqli_query($mysqli, $daysel4) or die('-4' . mysqli_error());
			$daysnum4 = mysqli_fetch_assoc($dayssel4);
			$daynum4 = $daysnum4['daynum4'];{ ?>
														<td><?php echo $daynum4; ?></td>
														<?php } ?>
<?php
			$daysel5 = "SELECT MAX(schedule.day5) AS daynum5 FROM schedule WHERE startDate BETWEEN STR_TO_DATE('$fromDate', '%Y-%m-%d') AND STR_TO_DATE('$toDate', '%Y-%m-%d') AND userId = ". $rows['userId'];
			$dayssel5 = mysqli_query($mysqli, $daysel5) or die('-5' . mysqli_error());
			$daysnum5 = mysqli_fetch_assoc($dayssel5);
			$daynum5 = $daysnum5['daynum5'];{ ?>
														<td><?php echo $daynum5; ?></td>
														<?php } ?>
<?php
			$daysel6 = "SELECT MAX(schedule.day6) AS daynum6 FROM schedule WHERE startDate BETWEEN STR_TO_DATE('$fromDate', '%Y-%m-%d') AND STR_TO_DATE('$toDate', '%Y-%m-%d') AND userId = ". $rows['userId'];
			$dayssel6 = mysqli_query($mysqli, $daysel6) or die('-6' . mysqli_error());
			$daysnum6 = mysqli_fetch_assoc($dayssel6);
			$daynum6 = $daysnum6['daynum6'];{ ?>
														<td><?php echo $daynum6; ?></td>
														<?php } ?>
<?php
			$daysel7 = "SELECT MAX(schedule.day7) AS daynum7 FROM schedule WHERE startDate BETWEEN STR_TO_DATE('$fromDate', '%Y-%m-%d') AND STR_TO_DATE('$toDate', '%Y-%m-%d') AND userId = ". $rows['userId'];
			$dayssel7 = mysqli_query($mysqli, $daysel7) or die('-7' . mysqli_error());
			$daysnum7 = mysqli_fetch_assoc($dayssel7);
			$daynum7 = $daysnum7['daynum7'];{ ?>
														<td><?php echo $daynum7; ?></td>
														<?php } ?>

<?php
			$daysel8 = "SELECT MAX(schedule.day8) AS daynum8 FROM schedule WHERE startDate BETWEEN STR_TO_DATE('$fromDate', '%Y-%m-%d') AND STR_TO_DATE('$toDate', '%Y-%m-%d') AND userId = ". $rows['userId'];
			$dayssel8 = mysqli_query($mysqli, $daysel8) or die('-8' . mysqli_error());
			$daysnum8 = mysqli_fetch_assoc($dayssel8);
			$daynum8 = $daysnum8['daynum8'];{ ?>
														<td><?php echo $daynum8; ?></td>
														<?php } ?>
<?php
			$daysel9 = "SELECT MAX(schedule.day9) AS daynum9 FROM schedule WHERE startDate BETWEEN STR_TO_DATE('$fromDate', '%Y-%m-%d') AND STR_TO_DATE('$toDate', '%Y-%m-%d') AND userId = ". $rows['userId'];
			$dayssel9 = mysqli_query($mysqli, $daysel9) or die('-9' . mysqli_error());
			$daysnum9 = mysqli_fetch_assoc($dayssel9);
			$daynum9 = $daysnum9['daynum9'];{ ?>
														<td><?php echo $daynum9; ?></td>
														<?php } ?>
<?php
			$daysel10 = "SELECT MAX(schedule.day10) AS daynum10 FROM schedule WHERE startDate BETWEEN STR_TO_DATE('$fromDate', '%Y-%m-%d') AND STR_TO_DATE('$toDate', '%Y-%m-%d') AND userId = ". $rows['userId'];
			$dayssel10 = mysqli_query($mysqli, $daysel10) or die('-10' . mysqli_error());
			$daysnum10 = mysqli_fetch_assoc($dayssel10);
			$daynum10 = $daysnum10['daynum10'];{ ?>
														<td><?php echo $daynum10; ?></td>
														<?php } ?>
<?php
			$daysel11 = "SELECT MAX(schedule.day11) AS daynum11 FROM schedule WHERE startDate BETWEEN STR_TO_DATE('$fromDate', '%Y-%m-%d') AND STR_TO_DATE('$toDate', '%Y-%m-%d') AND userId = ". $rows['userId'];
			$dayssel11 = mysqli_query($mysqli, $daysel11) or die('-11' . mysqli_error());
			$daysnum11 = mysqli_fetch_assoc($dayssel11);
			$daynum11 = $daysnum11['daynum11'];{ ?>
														<td><?php echo $daynum11; ?></td>
														<?php } ?>
<?php
			$daysel12 = "SELECT MAX(schedule.day12) AS daynum12 FROM schedule WHERE startDate BETWEEN STR_TO_DATE('$fromDate', '%Y-%m-%d') AND STR_TO_DATE('$toDate', '%Y-%m-%d') AND userId = ". $rows['userId'];
			$dayssel12 = mysqli_query($mysqli, $daysel12) or die('-12' . mysqli_error());
			$daysnum12 = mysqli_fetch_assoc($dayssel12);
			$daynum12 = $daysnum12['daynum12'];{ ?>
														<td><?php echo $daynum12; ?></td>
														<?php } ?>
<?php
			$daysel13 = "SELECT MAX(schedule.day13) AS daynum13 FROM schedule WHERE startDate BETWEEN STR_TO_DATE('$fromDate', '%Y-%m-%d') AND STR_TO_DATE('$toDate', '%Y-%m-%d') AND userId = ". $rows['userId'];
			$dayssel13 = mysqli_query($mysqli, $daysel13) or die('-13' . mysqli_error());
			$daysnum13 = mysqli_fetch_assoc($dayssel13);
			$daynum13 = $daysnum13['daynum13'];{ ?>
														<td><?php echo $daynum13; ?></td>
														<?php } ?>
<?php
			$daysel14 = "SELECT MAX(schedule.day14) AS daynum14 FROM schedule WHERE startDate BETWEEN STR_TO_DATE('$fromDate', '%Y-%m-%d') AND STR_TO_DATE('$toDate', '%Y-%m-%d') AND userId = ". $rows['userId'];
			$dayssel14 = mysqli_query($mysqli, $daysel14) or die('-14' . mysqli_error());
			$daysnum14 = mysqli_fetch_assoc($dayssel14);
			$daynum14 = $daysnum14['daynum14'];{ ?>
														<td><?php echo $daynum14; ?></td>
														<?php } ?>
<?php
			$daysel15 = "SELECT MAX(schedule.day15) AS daynum15 FROM schedule WHERE startDate BETWEEN STR_TO_DATE('$fromDate', '%Y-%m-%d') AND STR_TO_DATE('$toDate', '%Y-%m-%d') AND userId = ". $rows['userId'];
			$dayssel15 = mysqli_query($mysqli, $daysel15) or die('-15' . mysqli_error());
			$daysnum15 = mysqli_fetch_assoc($dayssel15);
			$daynum15 = $daysnum15['daynum15'];{ ?>
														<td><?php echo $daynum15; ?></td>
														<?php } ?>
<?php
			$daysel16 = "SELECT MAX(schedule.day16) AS daynum16 FROM schedule WHERE startDate BETWEEN STR_TO_DATE('$fromDate', '%Y-%m-%d') AND STR_TO_DATE('$toDate', '%Y-%m-%d') AND userId = ". $rows['userId'];
			$dayssel16 = mysqli_query($mysqli, $daysel16) or die('-16' . mysqli_error());
			$daysnum16 = mysqli_fetch_assoc($dayssel16);
			$daynum16 = $daysnum16['daynum16'];{ ?>
														<td><?php echo $daynum16; ?></td>
														<?php } ?>
<?php
			$daysel17 = "SELECT MAX(schedule.day17) AS daynum17 FROM schedule WHERE startDate BETWEEN STR_TO_DATE('$fromDate', '%Y-%m-%d') AND STR_TO_DATE('$toDate', '%Y-%m-%d') AND userId = ". $rows['userId'];
			$dayssel17 = mysqli_query($mysqli, $daysel17) or die('-17' . mysqli_error());
			$daysnum17 = mysqli_fetch_assoc($dayssel17);
			$daynum17 = $daysnum17['daynum17'];{ ?>
														<td><?php echo $daynum17; ?></td>
														<?php } ?>
<?php
			$daysel18 = "SELECT MAX(schedule.day18) AS daynum18 FROM schedule WHERE startDate BETWEEN STR_TO_DATE('$fromDate', '%Y-%m-%d') AND STR_TO_DATE('$toDate', '%Y-%m-%d') AND userId = ". $rows['userId'];
			$dayssel18 = mysqli_query($mysqli, $daysel18) or die('-18' . mysqli_error());
			$daysnum18 = mysqli_fetch_assoc($dayssel18);
			$daynum18 = $daysnum18['daynum18'];{ ?>
														<td><?php echo $daynum18; ?></td>
														<?php } ?>
<?php
			$daysel19 = "SELECT MAX(schedule.day19) AS daynum19 FROM schedule WHERE startDate BETWEEN STR_TO_DATE('$fromDate', '%Y-%m-%d') AND STR_TO_DATE('$toDate', '%Y-%m-%d') AND userId = ". $rows['userId'];
			$dayssel19 = mysqli_query($mysqli, $daysel19) or die('-19' . mysqli_error());
			$daysnum19 = mysqli_fetch_assoc($dayssel19);
			$daynum19 = $daysnum19['daynum19'];{ ?>
														<td><?php echo $daynum19; ?></td>
														<?php } ?>
<?php
			$daysel20 = "SELECT MAX(schedule.day20) AS daynum20 FROM schedule WHERE startDate BETWEEN STR_TO_DATE('$fromDate', '%Y-%m-%d') AND STR_TO_DATE('$toDate', '%Y-%m-%d') AND userId = ". $rows['userId'];
			$dayssel20 = mysqli_query($mysqli, $daysel20) or die('-20' . mysqli_error());
			$daysnum20 = mysqli_fetch_assoc($dayssel20);
			$daynum20 = $daysnum20['daynum20'];{ ?>
														<td><?php echo $daynum20; ?></td>
														<?php } ?>
<?php
			$daysel21 = "SELECT MAX(schedule.day21) AS daynum21 FROM schedule WHERE startDate BETWEEN STR_TO_DATE('$fromDate', '%Y-%m-%d') AND STR_TO_DATE('$toDate', '%Y-%m-%d') AND userId = ". $rows['userId'];
			$dayssel21 = mysqli_query($mysqli, $daysel21) or die('-21' . mysqli_error());
			$daysnum21 = mysqli_fetch_assoc($dayssel21);
			$daynum21 = $daysnum21['daynum21'];{ ?>
														<td><?php echo $daynum21; ?></td>
														<?php } ?>
<?php
			$daysel22 = "SELECT MAX(schedule.day22) AS daynum22 FROM schedule WHERE startDate BETWEEN STR_TO_DATE('$fromDate', '%Y-%m-%d') AND STR_TO_DATE('$toDate', '%Y-%m-%d') AND userId = ". $rows['userId'];
			$dayssel22 = mysqli_query($mysqli, $daysel22) or die('-22' . mysqli_error());
			$daysnum22 = mysqli_fetch_assoc($dayssel22);
			$daynum22 = $daysnum22['daynum22'];{ ?>
														<td><?php echo $daynum22; ?></td>
														<?php } ?>
<?php
			$daysel23 = "SELECT MAX(schedule.day23) AS daynum23 FROM schedule WHERE startDate BETWEEN STR_TO_DATE('$fromDate', '%Y-%m-%d') AND STR_TO_DATE('$toDate', '%Y-%m-%d') AND userId = ". $rows['userId'];
			$dayssel23 = mysqli_query($mysqli, $daysel23) or die('-23' . mysqli_error());
			$daysnum23 = mysqli_fetch_assoc($dayssel23);
			$daynum23 = $daysnum23['daynum23'];{ ?>
														<td><?php echo $daynum23; ?></td>
														<?php } ?>
<?php
			$daysel24 = "SELECT MAX(schedule.day24) AS daynum24 FROM schedule WHERE startDate BETWEEN STR_TO_DATE('$fromDate', '%Y-%m-%d') AND STR_TO_DATE('$toDate', '%Y-%m-%d') AND userId = ". $rows['userId'];
			$dayssel24 = mysqli_query($mysqli, $daysel24) or die('-24' . mysqli_error());
			$daysnum24 = mysqli_fetch_assoc($dayssel24);
			$daynum24 = $daysnum24['daynum24'];{ ?>
														<td><?php echo $daynum24; ?></td>
														<?php } ?>
<?php
			$daysel25 = "SELECT MAX(schedule.day25) AS daynum25 FROM schedule WHERE startDate BETWEEN STR_TO_DATE('$fromDate', '%Y-%m-%d') AND STR_TO_DATE('$toDate', '%Y-%m-%d') AND userId = ". $rows['userId'];
			$dayssel25 = mysqli_query($mysqli, $daysel25) or die('-25' . mysqli_error());
			$daysnum25 = mysqli_fetch_assoc($dayssel25);
			$daynum25 = $daysnum25['daynum25'];{ ?>
														<td><?php echo $daynum25; ?></td>
														<?php } ?>
<?php
			$daysel26 = "SELECT MAX(schedule.day26) AS daynum26 FROM schedule WHERE startDate BETWEEN STR_TO_DATE('$fromDate', '%Y-%m-%d') AND STR_TO_DATE('$toDate', '%Y-%m-%d') AND userId = ". $rows['userId'];
			$dayssel26 = mysqli_query($mysqli, $daysel26) or die('-26' . mysqli_error());
			$daysnum26 = mysqli_fetch_assoc($dayssel26);
			$daynum26 = $daysnum26['daynum26'];{ ?>
														<td><?php echo $daynum26; ?></td>
														<?php } ?>
<?php
			$daysel27 = "SELECT MAX(schedule.day27) AS daynum27 FROM schedule WHERE startDate BETWEEN STR_TO_DATE('$fromDate', '%Y-%m-%d') AND STR_TO_DATE('$toDate', '%Y-%m-%d') AND userId = ". $rows['userId'];
			$dayssel27 = mysqli_query($mysqli, $daysel27) or die('-27' . mysqli_error());
			$daysnum27 = mysqli_fetch_assoc($dayssel27);
			$daynum27 = $daysnum27['daynum27'];{ ?>
														<td><?php echo $daynum27; ?></td>
														<?php } ?>
<?php
			$daysel28 = "SELECT MAX(schedule.day28) AS daynum28 FROM schedule WHERE startDate BETWEEN STR_TO_DATE('$fromDate', '%Y-%m-%d') AND STR_TO_DATE('$toDate', '%Y-%m-%d') AND userId = ". $rows['userId'];
			$dayssel28 = mysqli_query($mysqli, $daysel28) or die('-28' . mysqli_error());
			$daysnum28 = mysqli_fetch_assoc($dayssel28);
			$daynum28 = $daysnum28['daynum28'];{ ?>
														<td><?php echo $daynum28; ?></td>
														<?php } ?>
<?php
			$daysel29 = "SELECT MAX(schedule.day29) AS daynum29 FROM schedule WHERE startDate BETWEEN STR_TO_DATE('$fromDate', '%Y-%m-%d') AND STR_TO_DATE('$toDate', '%Y-%m-%d') AND userId = ". $rows['userId'];
			$dayssel29 = mysqli_query($mysqli, $daysel29) or die('-29' . mysqli_error());
			$daysnum29 = mysqli_fetch_assoc($dayssel29);
			$daynum29 = $daysnum29['daynum29'];{ ?>
														<td><?php echo $daynum29; ?></td>
														<?php } ?>
<?php
			$daysel30 = "SELECT MAX(schedule.day30) AS daynum30 FROM schedule WHERE startDate BETWEEN STR_TO_DATE('$fromDate', '%Y-%m-%d') AND STR_TO_DATE('$toDate', '%Y-%m-%d') AND userId = ". $rows['userId'];
			$dayssel30 = mysqli_query($mysqli, $daysel30) or die('-30' . mysqli_error());
			$daysnum30 = mysqli_fetch_assoc($dayssel30);
			$daynum30 = $daysnum30['daynum30'];{ ?>
														<td><?php echo $daynum30; ?></td>
														<?php } ?>
<?php
			$daysel31 = "SELECT MAX(schedule.day31) AS daynum31 FROM schedule WHERE startDate BETWEEN STR_TO_DATE('$fromDate', '%Y-%m-%d') AND STR_TO_DATE('$toDate', '%Y-%m-%d') AND userId = ". $rows['userId'];
			$dayssel31 = mysqli_query($mysqli, $daysel31) or die('-31' . mysqli_error());
			$daysnum31 = mysqli_fetch_assoc($dayssel31);
			$daynum31 = $daysnum31['daynum31'];{ ?>
														<td><?php echo $daynum31; ?></td>
														<?php } ?>
														<td style="font-weight: bold;text-align: center;"><?php echo ($daynum1 + $daynum2 + $daynum3 + $daynum4 + $daynum5 + $daynum6 + $daynum7 + $daynum8 + $daynum9 + $daynum10 + $daynum11 + $daynum12 + $daynum13 + $daynum14 + $daynum15 + $daynum16 + $daynum17 + $daynum18 + $daynum19 + $daynum20 + $daynum21 + $daynum22 + $daynum23 + $daynum24 + $daynum25 + $daynum26 + $daynum27 + $daynum28 + $daynum29 + $daynum30 + $daynum31)  ;?></td>
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



	


	<?php
	
	$actIp = $_SERVER['REMOTE_ADDR'];

	
    $maxUpload = (int)(ini_get('upload_max_filesize'));

	
	$userDocsPath = $set['userDocsPath'];

	
	$filesAllowed = $set['fileTypesAllowed'];
	
	$fileTypesAllowed = preg_replace('/,/', ', ', $filesAllowed);

	
	if (isset($_POST['submit']) && $_POST['submit'] == 'uploadFile') {
		
		if($_POST['fileName'] == '') {
			$msgBox = alertBox($fileTitleReq, "<i class='fa fa-times-circle'></i>", "danger");
		} else {
			$fileName = htmlspecialchars($_POST['fileName']);
			$usrId = htmlspecialchars($_POST['usrId']);

			
			$fldr1 = "SELECT userFolder FROM users WHERE userId = ".$usrId;
			$fldrres1 = mysqli_query($mysqli, $fldr1) or die('-1' . mysqli_error());
			$usrFldr1 = mysqli_fetch_assoc($fldrres1);
			$usersFolder = $usrFldr1['userFolder'];

			
			$fileExt = $set['fileTypesAllowed'];
			$ftypes = array($fileExt);
			$ftypes_data = explode( ',', $fileExt );

			
			$ext = substr(strrchr(basename($_FILES['file']['name']), '.'), 1);
			if (!in_array($ext, $ftypes_data)) {
				$msgBox = alertBox($fileTypeError, "<i class='fa fa-times-circle'></i>", "danger");
			} else {
				
				$fName = clean(strip($fileName));

				
				
				$newName = str_replace(' ', '-', $fName);
				$newFName = strtolower($newName);

				
				$randomHash = uniqid(rand());
				
				$randHash = substr($randomHash, 0, 8);

				$fullName = $newFName.'-'.$randHash;

				
				$fileUrl = basename($_FILES['file']['name']);

				
				$extension = explode(".", $fileUrl);
				$extension = end($extension);

				
				
				$newFileName = $fullName.'.'.$extension;
				$movePath = $userDocsPath.$usersFolder.'/'.$newFileName;

				$stmt = $mysqli->prepare("
									INSERT INTO
										userdocs(
											userId,
											uploadedBy,
											docTitle,
											docUrl,
											uploadDate,
											ipAddress
										) VALUES (
											?,
											?,
											?,
											?,
											NOW(),
											?
										)");
				$stmt->bind_param('sssss',
					$usrId,
					$tz_userId,
					$fileName,
					$newFileName,
					$actIp
				);

				if (move_uploaded_file($_FILES['file']['tmp_name'], $movePath)) {
					$stmt->execute();
					$msgBox = alertBox($theDocAct1." \"".$fileName."\" ".$theDocMsg2,"<i class='fa fa-check-square'></i>", "success");
					$stmt->close();

					
					$activityType = '12';
					$activityTitle = $tz_userFull.' '.$uplDocAct.' '.$newFileName;
					updateActivity($tz_userId,$activityType,$activityTitle);
				} else {
					$msgBox = alertBox($uplDocErr, "<i class='fa fa-times-circle'></i>", "danger");

					
					$activityType = '0';
					$activityTitle = $uplDocAct1.' "'.$tz_userFull.'" '.$uplDocAct2;
					updateActivity($tz_userId,$activityType,$activityTitle);
				}
				
				$_POST['fileName'] = '';
			}
		}
	}

	
	if (isset($_POST['submit']) && $_POST['submit'] == 'deleteDoc') {
		$docTitle = htmlspecialchars($_POST['docTitle']);
		$usrFolder = htmlspecialchars($_POST['usrFolder']);
		$docUrl = htmlspecialchars($_POST['docUrl']);
		$deleteId = htmlspecialchars($_POST['deleteId']);

		
		$filePath = $userDocsPath.$usrFolder.'/'.$docUrl;

		
		if (file_exists($filePath)) {
			unlink($filePath);

			$stmt = $mysqli->prepare("DELETE FROM userdocs WHERE docId = ".$deleteId);
			$stmt->execute();
			$stmt->close();

			$msgBox = alertBox($theDocMsg1." \"".$docTitle."\" ".$mngTaskDelFileAct2, "<i class='fa fa-check-square'></i>", "success");

			
			$activityType = '10';
			$activityTitle = $tz_userFull.' '.$delDocAct.' "'.$docUrl.'"';
			updateActivity($tz_userId,$activityType,$activityTitle);
		} else {
			$msgBox = alertBox($delDocError, "<i class='fa fa-warning'></i>", "warning");

			
			$activityType = '0';
			$activityTitle = $theDocMsg1.' "'.$docUrl.'" '.$mngTaskFileDelAct1;
			updateActivity($tz_userId,$activityType,$activityTitle);
		}
	}

	
	if (COUNT($userIdss) < '1') {
		
		$sql = "SELECT
					userdocs.*,
					CONCAT(users.userFirst,' ',users.userLast) AS TheUser,
					(SELECT usrs.userFolder FROM users AS usrs WHERE usrs.userId = userdocs.userId) AS usrFolder,
					(SELECT CONCAT(usr.userFirst,' ',usr.userLast) FROM users AS usr WHERE usr.userId = userdocs.uploadedBy) AS uplBy
				FROM
					userdocs
					LEFT JOIN users ON userdocs.userId = users.userId";
					
		$res = mysqli_query($mysqli, $sql) or die('-2' . mysqli_error());
	} else {
		
		$sql = "SELECT
					userdocs.*,
					CONCAT(users.userFirst,' ',users.userLast) AS TheUser,
					(SELECT usrs.userFolder FROM users AS usrs WHERE usrs.userId = userdocs.userId) AS usrFolder,
					(SELECT CONCAT(usr.userFirst,' ',usr.userLast) FROM users AS usr WHERE usr.userId = userdocs.uploadedBy) AS uplBy
				FROM
					userdocs
					LEFT JOIN users ON userdocs.userId = users.userId
				WHERE
					users.managerId = ".$userIdss." OR
					userdocs.userId = ".$userIdss;
		$res = mysqli_query($mysqli, $sql) or die('-3' . mysqli_error());
	}
?>
	<div class="container-fluid">
		<div class="container">
			<div class="row pageCont">
				<div class="col-md-12 pb-20">
					<?php
						if ((checkArray('MNGUSRDOCS', $auths)) || $tz_superUser != '') {
							if ($msgBox) { echo $msgBox; }
					?>
							
							<?php if(mysqli_num_rows($res) > 0) { ?>
								<table id="docs" class="hide" cellspacing="0" width="100%">
									<thead>
										<tr>
											<th><?php echo $docTitleTh; ?></th>
											<th><?php echo $userTh; ?></th>
											<th class="text-center"><?php echo $dateUplTh; ?></th>
											<th></th>
										</tr>
									</thead>

									<tbody>
										<?php while ($row = mysqli_fetch_assoc($res)) { ?>
											<tr>
												<td class="text-center">
													<a href="<?php echo $userDocsPath.$row['usrFolder'].'/'.$row['docUrl']; ?>" target="_blank" data-toggle="tooltip" data-placement="right" title="<?php echo $viewDocTooltip; ?>">
														<?php echo clean($row['docTitle']); ?>
													</a>
												</td>
												<td class="text-center"><?php echo clean($row['TheUser']); ?></td>
												<td class="text-center"><?php echo dateFormat($row['uploadDate']); ?></td>
												<td class="text-right">
													<a href="#delete<?php echo $row['docId']; ?>" data-toggle="modal">
														<i class="fa fa-trash text-danger" data-toggle="tooltip" data-placement="left" title="<?php echo $delDocTooltip; ?>"></i>
													</a>

													<div class="modal fade" id="delete<?php echo $row['docId']; ?>" tabindex="-1" role="dialog" aria-hidden="true">
														<div class="modal-dialog">
															<div class="modal-content">
																<form action="" method="post">
																	<div class="modal-body">
																		<p class="lead mt-0"><?php echo $delDocConf; ?> "<?php echo clean($row['docTitle']); ?>"?</p>
																	</div>
																	<div class="modal-footer">
																		<input type="hidden" name="deleteId" value="<?php echo $row['docId']; ?>" />
																		<input type="hidden" name="docTitle" value="<?php echo clean($row['docTitle']); ?>" />
																		<input type="hidden" name="usrFolder" value="<?php echo clean($row['usrFolder']); ?>" />
																		<input type="hidden" name="docUrl" value="<?php echo clean($row['docUrl']); ?>" />
																		<button type="button" class="btn btn-default btn-sm btn-icon" data-dismiss="modal"><i class="fa fa-times-circle-o"></i> <?php echo $cancelBtn; ?></button>
																		<button type="input" name="submit" value="deleteDoc" class="btn btn-success btn-sm btn-icon"><i class="fa fa-check-square-o"></i> <?php echo $yesBtn; ?></button>
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
										<i class="fa fa-ban"></i>
									</div>
									<?php echo $noDocsFound; ?>
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