<?php
	include('../config.php');

	$usersId = htmlspecialchars($_POST['usersId']);

	$datasql = "SELECT userId, userEmail, userFirst, userLast, userPosition, superUser, isAdmin FROM users WHERE userId = ".$usersId." LIMIT 1";
	$authres = mysqli_query($mysqli, $datasql) or die('User not found '.mysqli_error());
	$hasData = mysqli_num_rows($authres);

	if ($hasData > 0) {
		$dataqry = "SELECT
						appauths.*,
						appauthdesc.flagDesc,
						users.userEmail,
						users.userFirst,
						users.userLast,
						users.userPosition,
						users.superUser,
						users.isAdmin
					FROM
						appauths
						LEFT JOIN appauthdesc ON appauths.authFlag = appauthdesc.authFlag
						LEFT JOIN users ON appauths.userId = users.userId
					WHERE appauths.userId = ".$usersId." AND users.isActive = 1";
		$datares = mysqli_query($mysqli, $dataqry) or die('Error: Retrieving User Authorization Info '.mysqli_error());
		$hasRes = mysqli_num_rows($datares);

		if ($hasRes > 0) {
			while($datarow = mysqli_fetch_assoc($datares)) {
				$datarows = array_map(null, $datarow);
				$admindata[] = $datarows;
			}

			echo json_encode($admindata);
		} else {
			while($datarow = mysqli_fetch_assoc($authres)) {
				$datarows = array_map(null, $datarow);
				$admindata[] = $datarows;
			}

			echo json_encode($admindata);
		}
	}
?>