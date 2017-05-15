<?php
	
	$actIp = $_SERVER['REMOTE_ADDR'];

	
	if (isset($_POST['submit']) && $_POST['submit'] == 'sendMsg') {
		if($_POST['messageTitle'] == '') {
			$msgBox = alertBox($msgSubjectReq, "<i class='fa fa-times-circle'></i>", "danger");
		} else if($_POST['usersId'] == '...') {
			$msgBox = alertBox($msgToReq, "<i class='fa fa-times-circle'></i>", "danger");
		} else if($_POST['messageText'] == '') {
			$msgBox = alertBox($msgTextReq, "<i class='fa fa-times-circle'></i>", "danger");
		} else {
			$messageTitle = htmlspecialchars($_POST['messageTitle']);
			$usersId = htmlspecialchars($_POST['usersId']);
			$messageText = htmlspecialchars($_POST['messageText']);

			
			$qry = "SELECT CONCAT(userFirst,' ',userLast) AS sentTo FROM users WHERE userId = ".$usersId;
			$result = mysqli_query($mysqli, $qry) or die('-2' . mysqli_error());
			$rows = mysqli_fetch_assoc($result);
			$sentTo = clean($rows['sentTo']);

			$stmt = $mysqli->prepare("
								INSERT INTO
									privatemessages(
										fromId,
										toId,
										origId,
										messageTitle,
										messageText,
										messageDate,
										toRead,
										toArchived,
										toDeleted,
										fromDeleted,
										lastUpdated,
										ipAddress
									) VALUES (
										?,
										?,
										0,
										?,
										?,
										NOW(),
										0,
										0,
										0,
										0,
										NOW(),
										?
									)
			");
			$stmt->bind_param('sssss',
								$tz_userId,
								$usersId,
								$messageTitle,
								$messageText,
								$actIp
			);
			$stmt->execute();
			$stmt->close();

			
			$activityType = '13';
			$activityTitle = $tz_userFull.' '.$composeMsgAct.' "'.$messageTitle.'" '.$toText.' '.$sentTo;
			updateActivity($tz_userId,$activityType,$activityTitle);

			$msgBox = alertBox($composeMsgMsg." ".$composeMsgMsg2." ".$sentTo.".", "<i class='fa fa-check-square'></i>", "success");

			
			$_POST['messageTitle'] = $_POST['messageText'] = '';
		}
    }

	$msgPage = 'true';
	$pageTitle = $composePageTitle;
	$addCss = '<link href="css/chosen.css" rel="stylesheet">';
	$chosen = 'true';

	include 'includes/header.php';
?>
	<div class="container-fluid">
		<div class="container">
			<div class="row pageCont">
				<div class="col-md-12 pb-20">
					<?php if ($msgBox) { echo $msgBox; } ?>

					<div class="tabbed-panel">
						<div class="tabbed-line">
							<ul class="nav nav-tabs ">
								<li><a href="index.php?page=messages"><?php echo $inboxNav; ?></a></li>
								<li><a href="index.php?page=sent"><?php echo $sentNav; ?></a></li>
								<li><a href="index.php?page=archived"><?php echo $archivedNav; ?></a></li>
								<li class="active"><a href="#compose" data-toggle="tab"><?php echo $composeNav; ?></a></li>
							</ul>
							<div class="tab-content">
								<div class="tab-pane active pt-20" id="compose">
									<h4 class="head-title mt-10 mb-20"><?php echo $pageTitle; ?></h4>
									<form action="" method="post">
										<div class="row">
											<div class="col-md-6">
												<div class="form-group">
													<label for="messageTitle"><?php echo $msgSubjectField; ?></label>
													<input type="text" class="form-control" name="messageTitle" id="messageTitle" required="required" maxlength="50" value="<?php echo isset($_POST['messageTitle']) ? $_POST['messageTitle'] : ''; ?>" />
												</div>
											</div>
											<div class="col-md-6">
												<div class="form-group">
													<?php
														$usrqry = "SELECT userId, userFirst, userLast FROM users WHERE isActive = 1 AND userId != ".$tz_userId;
														$usrres = mysqli_query($mysqli, $usrqry) or die('-1'.mysqli_error());
													?>
													<label for="usersId"><?php echo $sendToField; ?></label>
													<select class="form-control chosen-select" name="usersId" id="usersId">
														<option value="..."><?php echo $selectOption; ?></option>
														<?php
															while ($usr = mysqli_fetch_assoc($usrres)) {
																echo '<option value="'.$usr['userId'].'">'.$usr['userFirst'].' '.$usr['userLast'].'</option>';
															}
														?>
													</select>
												</div>
											</div>
										</div>
										<div class="form-group">
											<label for="messageText"><?php echo $msgTextField; ?></label>
											<textarea class="form-control" name="messageText" id="messageText" required="required" rows="8"><?php echo isset($_POST['messageText']) ? $_POST['messageText'] : ''; ?></textarea>
										</div>
										<button type="input" name="submit" value="sendMsg" class="btn btn-success btn-sm btn-icon"><i class="fa fa-send"></i> <?php echo $sendMsgBtn; ?></button>
									</form>
								</div>
							</div>
						</div>
					</div>

				</div>
			</div>
		</div>
	</div>