<?php
	$reportType = $showStatus = $showTrmDate = $rptError = '';
	$reportName = $timeRptPageTitle;
	$where = array();

	$notimelogsTab = 'active';
	$nodocumentsTab = $allusersTab =  '';


	if (!empty($_POST['userId']) && is_array($_POST['userId']) && !in_array('all',$_POST['userId'])) {
		$uids = array();
		foreach ($_POST['userId'] as $userId) {
		  $uids[] = $mysqli->real_escape_string($userId);
		}
		$userIds = '"'.implode('", "', $uids).'"';
		$where[] = 'schedule.userId IN ('.$userIds.')';

		$userIdss = ''.implode('", "', $uids).'';
		$where[] = 'schedule.userId IN ('.$userIdss.')';
	}



	if (!empty($_POST['fromMonth'])) {

$monthNum  = $mysqli->real_escape_string($_POST['fromMonth']);
$fromMonth = date('F', mktime(0, 0, 0, $monthNum, 10));


$fromyear  = $_POST['fromyear'];

$userIdrpt = $rows['userId'];

$where[] = 'schedule.monthn = "'.$fromMonth.'" AND schedule.yearn = "'.$fromyear.'" group by userId';

$fdate = dateFormat($fromMonth);
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

	$userDocsPath = $set['userDocsPath'];
		$sqlrpt = "SELECT userdocs.*,
		(SELECT usrs.userFolder FROM users AS usrs WHERE usrs.userId = userdocs.userId) AS usrFolder 
		FROM userdocs LEFT JOIN users ON userdocs.userId = users.userId";
					
	$resrpt = mysqli_query($mysqli, $sqlrpt) or die('-2' . mysqli_error()); 

	$alluserssql = "SELECT * FROM users WHERE isActive = '1'  " ;

	$allusersres = mysqli_query($mysqli, $alluserssql) or die('-1' . mysqli_error());

	$notimelogssql = "SELECT * FROM users WHERE users.userId NOT IN(SELECT schedule.userId FROM schedule where monthn='".$fromMonth."' AND yearn = '".$fromyear."' ) AND  isActive = '1'" ;
	$notimelogsres = mysqli_query($mysqli, $notimelogssql) or die('-1' . mysqli_error());

	$nodocssql = "SELECT * FROM users WHERE users.userId NOT IN(SELECT userdocs.userId FROM userdocs where monthn='".$fromMonth."' AND yearn = '".$fromyear."' ) AND  isActive = '1' " ;
	$nodocsres = mysqli_query($mysqli, $nodocssql) or die('-1' . mysqli_error());





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



       $totaldays = date('t', mktime(0, 0, 0, $monthNum, 10));
		$date = new DateTime("$fromyear-$monthNum-01");
						$now = new DateTime();

							if($date > $now) {

									echo"<div class='alertMsg warning'>
								<div class='msgIcon pull-left'>
									<i class='fa fa-warning'></i>
								</div> $noRptDateFound
							</div>" ;						
							}
					?>

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
												<?php 

												if ($totaldays == "28") {
												}

												elseif ($totaldays == "30")  {
														echo "<th > 29</th>
														<th > 30</th>";
													}
												else {
														echo "<th > 29</th>
														<th > 30</th>
														<th > 31</th>";
													}
												 ?>


												<th style="font-weight: bold;">Total Hrs</th>
												<th style="font-weight: bold;">Documents</th>

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
														<td>
														<a href="index.php?page=viewUser&userId=<?php echo $rows['userNum']; ?>" data-toggle="tooltip" data-placement="right" title="<?php echo $viewUserTooltip; ?>">
																<?php echo clean($rows['theUser']); ?>
															</a>
															</td>
														<td style="font-weight: bold;">Hrs</td>
														<?php
												$daysel = "SELECT CONCAT(schedule.day1) AS daynum1,
																	CONCAT(schedule.day2) AS daynum2,
																	CONCAT(schedule.day3) AS daynum3,
																	CONCAT(schedule.day4) AS daynum4,
																	CONCAT(schedule.day5) AS daynum5,
																	CONCAT(schedule.day6) AS daynum6,
																	CONCAT(schedule.day7) AS daynum7,
																	CONCAT(schedule.day8) AS daynum8,
																	CONCAT(schedule.day9) AS daynum9,
																	CONCAT(schedule.day10) AS daynum10,
																	CONCAT(schedule.day11) AS daynum11,
																	CONCAT(schedule.day12) AS daynum12,
																	CONCAT(schedule.day13) AS daynum13,
																	CONCAT(schedule.day14) AS daynum14,
																	CONCAT(schedule.day15) AS daynum15,
																	CONCAT(schedule.day16) AS daynum16,
																	CONCAT(schedule.day17) AS daynum17,
																	CONCAT(schedule.day18) AS daynum18,
																	CONCAT(schedule.day19) AS daynum19,
																	CONCAT(schedule.day20) AS daynum20,
																	CONCAT(schedule.day21) AS daynum21,
																	CONCAT(schedule.day22) AS daynum22,
																	CONCAT(schedule.day23) AS daynum23,
																	CONCAT(schedule.day24) AS daynum24,
																	CONCAT(schedule.day25) AS daynum25,
																	CONCAT(schedule.day26) AS daynum26,
																	CONCAT(schedule.day27) AS daynum27,
																	CONCAT(schedule.day28) AS daynum28,
																	CONCAT(schedule.day29) AS daynum29,
																	CONCAT(schedule.day30) AS daynum30,
																	CONCAT(schedule.day31) AS daynum31
																	FROM schedule WHERE monthn ='".$fromMonth."' AND userId = '".$rows['userId']."' ORDER BY schedId DESC LIMIT 1";
												$dayssel = mysqli_query($mysqli, $daysel) or die('-1' . mysqli_error());
												$daysnum2 = mysqli_fetch_assoc($dayssel);
												$daynum1 = $daysnum2['daynum1']; 
												$daynum2 = $daysnum2['daynum2'];
												$daynum3 = $daysnum2['daynum3'];
												$daynum4 = $daysnum2['daynum4'];
												$daynum5 = $daysnum2['daynum5'];
												$daynum6 = $daysnum2['daynum6'];
												$daynum7 = $daysnum2['daynum7'];
												$daynum8 = $daysnum2['daynum8'];
												$daynum9 = $daysnum2['daynum9'];
												$daynum10 = $daysnum2['daynum10'];
												$daynum11 = $daysnum2['daynum11'];
												$daynum12 = $daysnum2['daynum12'];
												$daynum13 = $daysnum2['daynum13'];
												$daynum14 = $daysnum2['daynum14'];
												$daynum15 = $daysnum2['daynum15'];
												$daynum16 = $daysnum2['daynum16'];
												$daynum17 = $daysnum2['daynum17'];
												$daynum18 = $daysnum2['daynum18'];
												$daynum19 = $daysnum2['daynum19'];
												$daynum20 = $daysnum2['daynum20'];
												$daynum21 = $daysnum2['daynum21'];
												$daynum22 = $daysnum2['daynum22'];
												$daynum23 = $daysnum2['daynum23'];
												$daynum24 = $daysnum2['daynum24'];
												$daynum25 = $daysnum2['daynum25'];
												$daynum26 = $daysnum2['daynum26'];
												$daynum27 = $daysnum2['daynum27'];
												$daynum28 = $daysnum2['daynum28'];
												$daynum29 = $daysnum2['daynum29'];
												$daynum30 = $daysnum2['daynum30'];
												$daynum31 = $daysnum2['daynum31'];



												 ?>
														
														<td><?php echo $daynum1; ?></td>
														
														<td><?php echo $daynum2; ?></td>
														
														<td><?php echo $daynum3; ?></td>
														
														<td><?php echo $daynum4; ?></td>
														
														<td><?php echo $daynum5; ?></td>
														
														<td><?php echo $daynum6; ?></td>
														
														<td><?php echo $daynum7; ?></td>
														
														<td><?php echo $daynum8; ?></td>
														
														<td><?php echo $daynum9; ?></td>
														
														<td><?php echo $daynum10; ?></td>
														
														<td><?php echo $daynum11; ?></td>
														
														<td><?php echo $daynum12; ?></td>
														
														<td><?php echo $daynum13; ?></td>
														
														<td><?php echo $daynum14; ?></td>
														
														<td><?php echo $daynum15; ?></td>
														
														<td><?php echo $daynum16; ?></td>
														
														<td><?php echo $daynum17; ?></td>
														
														<td><?php echo $daynum18; ?></td>
														
														<td><?php echo $daynum19; ?></td>
														
														<td><?php echo $daynum20; ?></td>
														
														<td><?php echo $daynum21; ?></td>
														
														<td><?php echo $daynum22; ?></td>
														
														<td><?php echo $daynum23; ?></td>
														
														<td><?php echo $daynum24; ?></td>
														
														<td><?php echo $daynum25; ?></td>
														
														<td><?php echo $daynum26; ?></td>
														
														<td><?php echo $daynum27; ?></td>
														
														<td><?php echo $daynum28; ?></td>

														<?php 

												if ($totaldays == "28") {
												}

												elseif ($totaldays == "30")  {
													?>
														

														<td > <?php echo $daynum29; ?></td>
														<td > <?php echo $daynum30; ?></td>
													<?php }
												else { ?>
														<td > <?php echo $daynum29; ?></td>
														<td > <?php echo $daynum30; ?></td>
														<td > <?php echo $daynum31; ?></td>
												<?php	}
												 ?>

												
														
														
														<td style="font-weight: bold;text-align: center;">

														<?php 

														if (strlen($daynum1) <'3') {

															$daynum1 = "$daynum1:00";
														};

														if (strlen($daynum2) <'3') {

															$daynum2 = "$daynum2:00";
														};

														if (strlen($daynum3) <'3') {

															$daynum3 = "$daynum3:00";
														};

														if (strlen($daynum4) <'3') {

															$daynum4 = "$daynum4:00";
														};

														if (strlen($daynum5) <'3') {

															$daynum5 = "$daynum5:00";
														};

														if (strlen($daynum6) <'3') {

															$daynum6 = "$daynum6:00";
														};

														if (strlen($daynum7) <'3') {

															$daynum7 = "$daynum7:00";
														};

														if (strlen($daynum8) <'3') {

															$daynum8 = "$daynum8:00";
														};

														if (strlen($daynum9) <'3') {

															$daynum9 = "$daynum9:00";
														};

														if (strlen($daynum10) <'3') {

															$daynum10 = "$daynum10:00";
														};

														if (strlen($daynum11) <'3') {

															$daynum11 = "$daynum11:00";
														};

														if (strlen($daynum12) <'3') {

															$daynum12 = "$daynum12:00";
														};

														if (strlen($daynum13) <'3') {

															$daynum13 = "$daynum13:00";
														};

														if (strlen($daynum14) <'3') {

															$daynum14 = "$daynum14:00";
														};

														if (strlen($daynum15) <'3') {

															$daynum15 = "$daynum15:00";
														};

														if (strlen($daynum16) <'3') {

															$daynum16 = "$daynum16:00";
														};

														if (strlen($daynum17) <'3') {

															$daynum17 = "$daynum17:00";
														};

														if (strlen($daynum18) <'3') {

															$daynum18 = "$daynum18:00";
														};

														if (strlen($daynum19) <'3') {

															$daynum19 = "$daynum19:00";
														};

														if (strlen($daynum20) <'3') {

															$daynum20 = "$daynum20:00";
														};

														if (strlen($daynum21) <'3') {

															$daynum21 = "$daynum21:00";
														};

														if (strlen($daynum22) <'3') {

															$daynum22 = "$daynum22:00";
														};

														if (strlen($daynum23) <'3') {

															$daynum23 = "$daynum23:00";
														};

														if (strlen($daynum24) <'3') {

															$daynum24 = "$daynum24:00";
														};

														if (strlen($daynum25) <'3') {

															$daynum25 = "$daynum25:00";
														};

														if (strlen($daynum26) <'3') {

															$daynum26 = "$daynum26:00";
														};

														if (strlen($daynum27) <'3') {

															$daynum27 = "$daynum27:00";
														};

														if (strlen($daynum28) <'3') {

															$daynum28 = "$daynum28:00";
														};

														if (strlen($daynum29) <'3') {

															$daynum29 = "$daynum29:00";
														};

														if (strlen($daynum30) <'3') {

															$daynum30 = "$daynum30:00";
														};

														if (strlen($daynum31) <'3') {

															$daynum31 = "$daynum31:00";
														};

														


														//hour to seconds



														$seconds1 = strtotime("1970-01-01 $daynum1 UTC");

														$seconds2 = strtotime("1970-01-01 $daynum2 UTC");		

														$seconds3 = strtotime("1970-01-01 $daynum3 UTC");

														$seconds4 = strtotime("1970-01-01 $daynum4 UTC");

														$seconds5 = strtotime("1970-01-01 $daynum5 UTC");

														$seconds6 = strtotime("1970-01-01 $daynum6 UTC");

														$seconds7 = strtotime("1970-01-01 $daynum7 UTC");

														$seconds8 = strtotime("1970-01-01 $daynum8 UTC");

														$seconds9 = strtotime("1970-01-01 $daynum9 UTC");

														$seconds10 = strtotime("1970-01-01 $daynum10 UTC");

														$seconds11 = strtotime("1970-01-01 $daynum11 UTC");

														$seconds12 = strtotime("1970-01-01 $daynum12 UTC");

														$seconds13 = strtotime("1970-01-01 $daynum13 UTC");

														$seconds14 = strtotime("1970-01-01 $daynum14 UTC");

														$seconds15 = strtotime("1970-01-01 $daynum15 UTC");

														$seconds16 = strtotime("1970-01-01 $daynum16 UTC");

														$seconds17 = strtotime("1970-01-01 $daynum17 UTC");

														$seconds18 = strtotime("1970-01-01 $daynum18 UTC");

														$seconds19 = strtotime("1970-01-01 $daynum19 UTC");

														$seconds20 = strtotime("1970-01-01 $daynum20 UTC");

														$seconds21 = strtotime("1970-01-01 $daynum21 UTC");

														$seconds22 = strtotime("1970-01-01 $daynum22 UTC");

														$seconds23 = strtotime("1970-01-01 $daynum23 UTC");

														$seconds24 = strtotime("1970-01-01 $daynum24 UTC");

														$seconds25 = strtotime("1970-01-01 $daynum25 UTC");

														$seconds26 = strtotime("1970-01-01 $daynum26 UTC");

														$seconds27 = strtotime("1970-01-01 $daynum27 UTC");

														$seconds28 = strtotime("1970-01-01 $daynum28 UTC");

														$seconds29 = strtotime("1970-01-01 $daynum29 UTC");

														$seconds30 = strtotime("1970-01-01 $daynum30 UTC");

														$seconds31 = strtotime("1970-01-01 $daynum31 UTC");	

														$totalsecs = ($seconds1 + $seconds2 + $seconds3 + $seconds4 + $seconds5 + $seconds6 + $seconds7 + $seconds8 + $seconds9 + $seconds10 + $seconds11 + $seconds12 + $seconds13 + $seconds14 + $seconds15 + $seconds16 + $seconds17 + $seconds18 + $seconds19 + $seconds20 + $seconds21 + $seconds22 + $seconds23 + $seconds24 + $seconds25 + $seconds26 + $seconds27 + $seconds28 + $seconds29 + $seconds30 + $seconds31);

														//seconds to hour
														$init = $totalsecs;
														$hours = floor($init / 3600);
														$minutes = floor(($init / 60) % 60);
														$seconds = $init % 60;

														echo "$hours:$minutes" ;
														?>
															
															
														</td>
									<?php
									

			$userdocsUrl1 = "SELECT userdocs.docUrl AS userdocsUrl FROM userdocs WHERE monthn ='".$fromMonth."' AND yearn ='".$fromyear."' AND userId = '".$rows['userId']."' ORDER BY docId DESC LIMIT 1";
			$userdocsUrl2 = mysqli_query($mysqli, $userdocsUrl1) or die('-31' . mysqli_error());
			$userdocsUrl3 = mysqli_fetch_assoc($userdocsUrl2);
			$userdocsUrl = $userdocsUrl3['userdocsUrl'];

			$userdocsUrlc1 = "SELECT count(userdocs.docUrl) AS userdocsUrlc FROM userdocs WHERE monthn ='".$fromMonth."' AND yearn ='".$fromyear."' AND userId = '".$rows['userId']."'";
			$userdocsUrlc2 = mysqli_query($mysqli, $userdocsUrlc1) or die('-31' . mysqli_error());
			$userdocsUrlc3 = mysqli_fetch_assoc($userdocsUrlc2);
			$userdocsUrlc = $userdocsUrlc3['userdocsUrlc'];


			$userDoctitle1 = "SELECT userdocs.docTitle AS userDoctitle FROM userdocs WHERE monthn ='".$fromMonth."' AND yearn ='".$fromyear."' AND userId = '".$rows['userId']."' ORDER BY docId DESC LIMIT 1";
			$userDoctitle2 = mysqli_query($mysqli, $userDoctitle1) or die('-31' . mysqli_error());
			$userDoctitle3 = mysqli_fetch_assoc($userDoctitle2);
			$userDoctitle = $userDoctitle3['userDoctitle'];

			

			$userFolder1 = "SELECT users.userFolder AS userFolder FROM users WHERE userId = '".$rows['userId']."' ";
			$userFolder2 = mysqli_query($mysqli, $userFolder1) or die('-31' . mysqli_error());
			$userFolder3 = mysqli_fetch_assoc($userFolder2);
			$userFolder = $userFolder3['userFolder'];


							?>



														<td class="text-center">



														<?php if ($userdocsUrlc == '1') { ?>
														<a href="<?php echo ($userDocsPath.$userFolder.'/'.$userdocsUrl); ?>" target="_blank" data-toggle="tooltip" data-placement="right" title="<?php echo $viewDocTooltip; ?>">
														<?php echo $userDoctitle; ?>
													</a>
														<?php }

														else { ?>

															<a href="index.php?page=viewDocument&userId=<?php echo $rows['userId']; ?>&userMonth=<?php echo $fromMonth; ?>" data-toggle="tooltip" data-placement="right" title="Download Document">
																No of uploads : <?php echo $userdocsUrlc;	?>
															</a>
																						
														<?php	} ?>

													
												</td>
													</tr>
											<?php } ?>
										</tbody>
									</table>



									<?php
									} else {
								?>



										<?php
										if($date < $now) {

											echo"<div class='alertMsg warning'>
								<div class='msgIcon pull-left'>
									<i class='fa fa-warning'></i>
								</div> $noRptDataFoundMsg
							</div>" ;		
										}  ?>
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


				<div class="col-md-12 pb-20">

				<script language="JavaScript">


    function checkAll(checkname, bx) {
        for (i = checkname.length; i--; )
            checkname[i].checked = bx.checked;
    }
        


    function checkPage(bx){                    
        for (var tbls = document.getElementsByTagName("table"),i=tbls.length; i--; )
            for (var bxs=tbls[i].getElementsByTagName("input"),j=bxs.length; j--; )
               if (bxs[j].type=="checkbox")
                   bxs[j].checked = bx.checked;
    }

      


    function checkPageuwT(bx){                    
        for (var tbls = document.getElementsByTagName("table"),i=tbls.length; i--; )
            for (var bxs=tbls[i].getElementsByTagName("input"),j=bxs.length; j--; )
               if (bxs[j].type=="checkbox")
                   bxs[j].checked = bx.checked;
    }


    function checkPageuwD(bx){                    
        for (var tbls = document.getElementsByTagName("table"),i=tbls.length; i--; )
            for (var bxs=tbls[i].getElementsByTagName("input"),j=bxs.length; j--; )
               if (bxs[j].type=="checkbox")
                   bxs[j].checked = bx.checked;
    }




</script>


<script type="text/javascript" src="js/jquery.min.js"></script>
<script language="JavaScript">

$(function() {
        $('.checkbox').click(function() {
            if ($('.checkbox:checked').length > 0) {
                $('#offwt').removeAttr('disabled');
            } else {
                $('#offwt').attr('disabled', 'disabled');
            }
        });
    });

$(function() {
        $('.checkbox').click(function() {
            if ($('.checkbox:checked').length > 0) {
                $('#offwd').removeAttr('disabled');
            } else {
                $('#offwd').attr('disabled', 'disabled');
            }
        });
    });

$(function() {
        $('.checkbox').click(function() {
            if ($('.checkbox:checked').length > 0) {
                $('#offall').removeAttr('disabled');
            } else {
                $('#offall').attr('disabled', 'disabled');
            }
        });
    });
</script>
<legend class="default"> <?php echo $attrpTh4 ?></legend>




								

							<div class="tab-pane <?php echo $allusersTab; ?>" id="allusers">



								<form action="index.php?page=mailedusersall" method="post">

							<?php if(mysqli_num_rows($allusersres) > 0) { ?>
								
								<table id="actUsers" class="display" cellspacing="0" width="100%">
									<thead>
										<tr>
										<th class="text-center"><input type="checkbox" name="pageCheck" class="checkbox" value="yes" onclick="checkPage(this)"></th>
											<th class="text-left"><?php echo $usersNameTh; ?></th>
											<th><?php echo $emailTxhome; ?></th>
											<th><?php echo $phoneTh; ?></th>
											<th class="text-center"><?php echo $lastSignInTh; ?></th>
										</tr>
									</thead>

									<tbody>
										<?php
											while ($row = mysqli_fetch_assoc($allusersres)) {
												if ($row['superUser'] == '1') { $suser = $superUserText.', '; } else { $suser = ''; }
												if ($row['isAdmin'] == '1') { $admUser = $managerText; } else { $admUser = $employeeText; }
												if ($row['userPhone1'] != '') { $userPhone1 = decodeIt($row['userPhone1']); } else { $userPhone1 = '';  }
												if ($row['lastVisited'] != '0000-00-00 00:00:00') { $lastVisited = dateFormat($row['lastVisited']); } else { $lastVisited = '';  }
										?>
												<tr>

												<td class="text-center">
													<?php echo "<input type='checkbox' name='num[]' value='$row[userEmail] ' class='checkbox'>"; ?>
														
													</td>

													<td class="text-left">
														<?php if ($row['userId'] != '1' || $tz_userId == '1') { ?>
															<a href="index.php?page=viewUser&userId=<?php echo $row['userId']; ?>" data-toggle="tooltip" data-placement="right" title="<?php echo $viewUserTooltip; ?>">
																<?php echo clean($row['userFirst']).' '.clean($row['userLast']); ?>
															</a>
														<?php } else { ?>
															<strong><?php echo clean($row['userFirst']).' '.clean($row['userLast']); ?></strong>
														<?php } ?>
													</td>
													<td class="text-center"><?php echo clean($row['userEmail']); ?></td>
													<td class="text-center"><?php echo $userPhone1; ?></td>
													<td class="text-center"><?php echo $lastVisited; ?></td>
												</tr>
										<?php } ?>
									</tbody>
								</table>

								<button type="submit" name="submit"  id="offall" value="submit" class="btn btn-success btn-sm btn-icon-alt"  style=" float: right;" disabled><?php echo 'Send Mail Reminder'; ?> <i class="fa fa-long-arrow-right"></i></button>

								</form>
									<?php } ?>
								</div>





				</div>
			</div>
		</div>
	</div>







