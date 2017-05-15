<?php
	include('../config.php');

	$sql = "SELECT
				events.*,
				CONCAT(users.userFirst,' ',users.userLast) AS savedBy
			FROM
				events
				LEFT JOIN users ON events.createdBy = users.userId
			WHERE events.isPublic = 1";
	$result = mysqli_query($mysqli, $sql) or die('-1' . mysqli_error());

	while ($row = mysqli_fetch_assoc($result)) {
		if ($row['allDay'] == '0') {
			$allDay = false;
		} else {
			$allDay = true;
		}

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

		$events[] = array(
			'eventId' => $row['eventId'],
			'userId' => $row['userId'],
			'public' => $row['isPublic'],
			'allDay' => $allDay,
			'id' => $row['repeatId'],
			'title' => $row['eventTitle'],
			'startDate' => $row['startDate'],
			'startTime' => $row['startTime'],
			'start' => $start,
			'endDate' => $row['endDate'],
			'endTime' => $row['endTime'],
			'end' => $end,
			'description' => $row['eventDesc'],
			'color' => $row['eventColor'],
			'lastUpdated' => $row['lastUpdated'],
			'ipAddress' => $row['ipAddress'],
			'createdBy' => $row['savedBy']
		);
	}
	if(mysqli_num_rows($result) > 0) {
		echo json_encode($events);
	} else {
		echo '0';
	}