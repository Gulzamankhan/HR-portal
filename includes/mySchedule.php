<?php
	if(!isset($_SESSION)) session_start();

	$tz_userId   = $_SESSION['tz']['userId'];
	$tz_userFull = $_SESSION['tz']['userFirst'].' '.$_SESSION['tz']['userLast'];

	include('../config.php');

	$sql = "SELECT
				schedule.*,
				CONCAT(users.userFirst,' ',users.userLast) AS createdBy
			FROM
				schedule
				LEFT JOIN users ON schedule.createdBy = users.userId
			WHERE schedule.userId = ".$tz_userId;
	$result = mysqli_query($mysqli, $sql) or die('-1' . mysqli_error());

	while ($row = mysqli_fetch_assoc($result)) {
		$allDay = false;

		if (is_null($row['startTime']) || $row['startTime'] == '') {
			$start = $row['startDate'];
		} else {
			$start = $row['startDate'].'T'.$row['startTime'];
		}

		if (is_null($row['endTime']) || $row['endTime'] == '') {
			$end = $row['endDate'];
		} else {
			$end = $row['endDate'].'T'.$row['endTime'];
		}

		$schedules[] = array(
			'schedId' => $row['schedId'],
			'userId' => $row['userId'],
			'usersName' => $tz_userFull,
			'allDay' => $allDay,
			'title' => $row['schedTitle'],
			'startDate' => $row['startDate'],
			'startTime' => $row['startTime'],
			'start' => $start,
			'endDate' => $row['endDate'],
			'endTime' => $row['endTime'],
			'end' => $end,
			'description' => $row['schedHours'],
			'color' => $row['schedColor'],
			'lastUpdated' => $row['lastUpdated'],
			'ipAddress' => $row['ipAddress'],
			'createdBy' => $row['createdBy']
		);
	}
	if(mysqli_num_rows($result) > 0) {
		echo json_encode($schedules);
	} else {
		echo '0';
	}