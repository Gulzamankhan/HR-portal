<?php
	include('../config.php');

	if(!isset($_SESSION))session_start();

	include('../includes/functions.php');

	$tz_userId = $_SESSION['tz']['userId'];
	$tz_userFull = $_SESSION['tz']['userFirst'].' '.$_SESSION['tz']['userLast'];

	$timeId = htmlspecialchars($_POST['timeId']);
	$weekNo = htmlspecialchars($_POST['weekNo']);
	$clockYear = htmlspecialchars($_POST['clockYear']);
	$running = htmlspecialchars($_POST['running']);

	$actIp = $_SERVER['REMOTE_ADDR'];
	
	$curDateTime = date("Y-m-d H:i:s");

	if ($timeId == '') {
		$stmt = $mysqli->prepare("
							INSERT INTO
								timelogs(
									userId,
									weekNo,
									clockYear,
									entryDate,
									startTime,
									entryType,
									lastUpdated,
									ipAddress
								) VALUES (
									?,
									?,
									?,
									?,
									?,
									1,
									?,
									?
								)
		");
		$stmt->bind_param('sssssss',
							$tz_userId,
							$weekNo,
							$clockYear,
							$curDateTime,
							$curDateTime,
							$curDateTime,
							$actIp
		);
		$stmt->execute();
		$stmt->close();

		$activityType = '4';
		$activityTitle = $tz_userFull.' Clocked In';
		updateActivity($tz_userId,$activityType,$activityTitle);

		$datares = 1;
		echo json_encode($datares);
	} else {
		$stmt = $mysqli->prepare("UPDATE timelogs SET endTime = ? WHERE timeId = ?");
		$stmt->bind_param('ss',$curDateTime,$timeId);
		$stmt->execute();
		$stmt->close();

		$activityType = '5';
		$activityTitle = $tz_userFull.' Clocked Out';
		updateActivity($tz_userId,$activityType,$activityTitle);

		$datares = 0;
		echo json_encode($datares);
	}