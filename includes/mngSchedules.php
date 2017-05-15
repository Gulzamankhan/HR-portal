<?php
	if(!isset($_SESSION)) session_start();

	include('../config.php');

	$sql = "SELECT
				schedule.*,
				CONCAT(users.userFirst,' ',users.userLast) AS theUser,
				CONCAT(usr.userFirst,' ',usr.userLast) AS createdBy
			FROM
				schedule
				LEFT JOIN users ON schedule.userId = users.userId
				LEFT JOIN users AS usr ON schedule.createdBy = usr.userId
			WHERE users.isActive = 1";
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

		$shiftStart = preg_replace('/(\d{2}:\d{2}):00/', '${1}', $row['startTime']);
		$shiftEnd = preg_replace('/(\d{2}:\d{2}):00/', '${1}', $row['endTime']);

		$schedules[] = array(
			'schedId' => $row['schedId'],
			'userId' => $row['userId'],
			'usersName' => $row['theUser'],
			'allDay' => $allDay,
			'title' => $row['theUser']."\r\n".$row['schedTitle'],
			'startDate' => $row['startDate'],
			'startTime' => $row['startTime'],
			'shiftStart' => $shiftStart,
			'start' => $start,
			'endDate' => $row['endDate'],
			'endTime' => $row['endTime'],
			'shiftEnd' => $shiftEnd,
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