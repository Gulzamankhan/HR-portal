<?php
	$taskId = htmlspecialchars($_GET['taskId']);

	
    $maxUpload = (int)(ini_get('upload_max_filesize'));

	
	$userDocsPath = $set['userDocsPath'];

	
	$filesAllowed = $set['fileTypesAllowed'];
	
	$fileTypesAllowed = preg_replace('/,/', ', ', $filesAllowed);

	
	$actIp = $_SERVER['REMOTE_ADDR'];

	
	if (isset($_POST['submit']) && $_POST['submit'] == 'editTask') {
		
		if($_POST['taskTitle'] == '') {
			$msgBox = alertBox($taskTitleReq, "<i class='fa fa-times-circle'></i>", "danger");
		} else if($_POST['taskPriority'] == '') {
			$msgBox = alertBox($taskPriorityReq, "<i class='fa fa-times-circle'></i>", "danger");
		} else if($_POST['taskStatus'] == '') {
			$msgBox = alertBox($taskStatusReq, "<i class='fa fa-times-circle'></i>", "danger");
		} else if($_POST['TaskDate'] == '') {
			$msgBox = alertBox($TaskDateDateReq, "<i class='fa fa-times-circle'></i>", "danger");
		} else if($_POST['taskDesc'] == '') {
			$msgBox = alertBox($taskDescReq, "<i class='fa fa-times-circle'></i>", "danger");
		} else {
			$taskTitle = htmlspecialchars($_POST['taskTitle']);
			$taskPriority = htmlspecialchars($_POST['taskPriority']);
			$taskStatus = htmlspecialchars($_POST['taskStatus']);
			$TaskDate = htmlspecialchars($_POST['TaskDate']);
			$taskDesc = htmlspecialchars($_POST['taskDesc']);
			$taskNotes = htmlspecialchars($_POST['taskNotes']);

			$stmt = $mysqli->prepare("UPDATE
										tasks
									SET
										taskTitle = ?,
										taskPriority = ?,
										taskStatus = ?,
										TaskDate = ?,
										taskDesc = ?,
										taskNotes = ?,
										lastUpdated = NOW(),
										ipAddress = ?
									WHERE
										taskId = ?"
			);
			$stmt->bind_param('ssssssss',
									$taskTitle,
									$taskPriority,
									$taskStatus,
									$TaskDate,
									$taskDesc,
									$taskNotes,
									$actIp,
									$taskId
			);
			$stmt->execute();
			$stmt->close();

			
			$activityType = '9';
			$activityTitle = $tz_userFull.' '.$mngTaskUpdAct.' "'.$taskTitle.'"';
			updateActivity($tz_userId,$activityType,$activityTitle);

			$msgBox = alertBox($delTaskMsg1." \"".$taskTitle."\" ".$editEventMsg, "<i class='fa fa-check-square'></i>", "success");
		}
    }

	
	if (isset($_POST['submit']) && $_POST['submit'] == 'reopen') {
		$taskTitle = htmlspecialchars($_POST['taskTitle']);
		$dateClosed = '0000-00-00 00:00:00';

		$stmt = $mysqli->prepare("UPDATE
									tasks
								SET
									isClosed = 0,
									dateClosed = ?,
									lastUpdated = NOW(),
									ipAddress = ?
								WHERE
									taskId = ?"
		);
		$stmt->bind_param('sss',
								$dateClosed,
								$actIp,
								$taskId
		);
		$stmt->execute();
		$stmt->close();

		
		$activityType = '9';
		$activityTitle = $tz_userFull.' '.$mngTaskReopnAct.' "'.$taskTitle.'"';
		updateActivity($tz_userId,$activityType,$activityTitle);

		$msgBox = alertBox($delTaskMsg1." \"".$taskTitle."\" ".$mngTaskMarkedReopnMsg, "<i class='fa fa-check-square'></i>", "success");
    }

	
	if (isset($_POST['submit']) && $_POST['submit'] == 'uploadFile') {
		
		if($_POST['fileName'] == '') {
			$msgBox = alertBox($fileTitleReq, "<i class='fa fa-times-circle'></i>", "danger");
		} else {
			
			$assignedId = htmlspecialchars($_POST['assignedId']);
			$fldr1 = "SELECT userFolder FROM users WHERE userId = ".$assignedId;
			$fldrres1 = mysqli_query($mysqli, $fldr1) or die('-1' . mysqli_error());
			$usrFldr1 = mysqli_fetch_assoc($fldrres1);
			$usersFolder = $usrFldr1['userFolder'];

			$fileName = htmlspecialchars($_POST['fileName']);
			$taskTitle = htmlspecialchars($_POST['taskTitle']);

			
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
										taskfiles(
											taskId,
											uploadedBy,
											fileName,
											fileUrl,
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
					$taskId,
					$tz_userId,
					$fileName,
					$newFileName,
					$actIp
				);

				if (move_uploaded_file($_FILES['file']['tmp_name'], $movePath)) {
					$stmt->execute();
					$msgBox = alertBox($theFileText." \"".$fileName."\" ".$mngTaskFileUplMsg,"<i class='fa fa-check-square'></i>", "success");
					$stmt->close();

					
					$activityType = '10';
					$activityTitle = $tz_userFull.' '.$mngTaskFileUplAct.' '.$taskTitle;
					updateActivity($tz_userId,$activityType,$activityTitle);
				} else {
					$msgBox = alertBox($mngTaskFileUplMsg1, "<i class='fa fa-times-circle'></i>", "danger");

					
					$activityType = '0';
					$activityTitle = $mngTaskFileUplAct1.' "'.$taskTitle.'" '.$mngTaskFileUplAct2;
					updateActivity($tz_userId,$activityType,$activityTitle);
				}
			}
		}
	}

	
	if (isset($_POST['submit']) && $_POST['submit'] == 'deleteFile') {
		
		$assignedId = htmlspecialchars($_POST['assignedId']);
		$fldr2 = "SELECT userFolder FROM users WHERE userId = ".$assignedId;
		$fldrres2 = mysqli_query($mysqli, $fldr2) or die('-2' . mysqli_error());
		$usrFldr2 = mysqli_fetch_assoc($fldrres2);
		$usersFolder2 = $usrFldr2['userFolder'];

		$fileName = htmlspecialchars($_POST['fileName']);
		$taskTitle = htmlspecialchars($_POST['taskTitle']);
		$fileUrl = htmlspecialchars($_POST['fileUrl']);
		$deleteId = htmlspecialchars($_POST['deleteId']);

		
		$filePath = $userDocsPath.$usersFolder2.'/'.$fileUrl;

		
		if (file_exists($filePath)) {
			unlink($filePath);

			$stmt = $mysqli->prepare("DELETE FROM taskfiles WHERE fileId = ".$deleteId);
			$stmt->execute();
			$stmt->close();

			$msgBox = alertBox($mngTaskDelFileAct1." \"".$fileName."\" ".$mngTaskDelFileAct2, "<i class='fa fa-check-square'></i>", "success");

			
			$activityType = '10';
			$activityTitle = $tz_userFull.' '.$mngTaskFileDelAct.' '.$taskTitle;
			updateActivity($tz_userId,$activityType,$activityTitle);
		} else {
			$msgBox = alertBox($mngTaskFileDelErr, "<i class='fa fa-warning'></i>", "warning");

			
			$activityType = '0';
			$activityTitle = $mngTaskFileUplAct1.' "'.$taskTitle.'" '.$mngTaskFileDelAct1;
			updateActivity($tz_userId,$activityType,$activityTitle);
		}
	}

	
	$qry = "SELECT
				tasks.*,
				CONCAT(users.userFirst,' ',users.userLast) AS createdBy,
				(SELECT userId FROM users WHERE userId = tasks.assignedTo) AS assignedId,
				(SELECT CONCAT(users.userFirst,' ',users.userLast) FROM users WHERE userId = tasks.assignedTo) AS assignedUser
			FROM
				tasks
				LEFT JOIN users ON tasks.userId = users.userId
			WHERE
				tasks.taskId = ".$taskId;
	$res = mysqli_query($mysqli, $qry) or die('-3' . mysqli_error());
	$row = mysqli_fetch_assoc($res);

	
	$sql = "SELECT
				taskfiles.*,
				CONCAT(users.userFirst,' ',users.userLast) AS savedBy
			FROM
				taskfiles
				LEFT JOIN users ON taskfiles.uploadedBy = users.userId
			WHERE
				taskfiles.taskId = ".$taskId;
	$result = mysqli_query($mysqli, $sql) or die('-4' . mysqli_error());

	
	$fldr = "SELECT userFolder FROM users WHERE userId = ".$row['assignedId'];
	$fldrres = mysqli_query($mysqli, $fldr) or die('-5' . mysqli_error());
	$usrFldr = mysqli_fetch_assoc($fldrres);
	$userFolder = $usrFldr['userFolder'];

	$mngPage = 'true';
	$pageTitle = $manageTaskPageTitle;
	$addCss = '<link rel="stylesheet" type="text/css" href="css/datetimepicker.css" />';
	$datePicker = 'true';
	$jsFile = 'manageTask';

	include 'includes/header.php';
?>
	<div class="container-fluid">
		<div class="container">
			<div class="row pageCont">
				<?php
					if ((checkArray('MNGTASKS', $auths)) || $row['userId'] == $tz_userId || $tz_superUser != '') {
				?>
						<div class="col-md-3 no-padding">
							<div class="sideBar pt-20">
								<?php if ($row['isClosed'] == '1') { ?>
									<div class="alert alert-success mb-10" role="alert"><?php echo $taskClosedAlert; ?></div>
								<?php } ?>
								<ul class="list-group">
									<li class="list-group-item task-lists"><strong><?php echo $dateCreatedTh; ?>:</strong><br /><?php echo dateFormat($row['taskStart']); ?></li>
									<li class="list-group-item task-lists"><strong><?php echo $createdBtText; ?>:</strong><br /><?php echo clean($row['createdBy']); ?></li>
									<li class="list-group-item task-lists"><strong><?php echo $assignedToText; ?>:</strong><br /><?php echo clean($row['assignedUser']); ?></li>
									<li class="list-group-item task-lists"><strong><?php echo $dueByText; ?>:</strong><br /><?php echo dateFormat($row['TaskDate']); ?></li>
									<li class="list-group-item task-lists"><strong><?php echo $lastUpdatedTh; ?>:</strong><br /><?php echo dateTimeFormat($row['lastUpdated']); ?></li>
								</ul>

								<h3 class="head-title mb-5"><?php echo $taskFilesH3; ?></h3>
								<?php if(mysqli_num_rows($result) > 0) { ?>
									<ul class="list-group">
										<?php
											while ($rows = mysqli_fetch_assoc($result)) {
												
												$filesExt = explode(".", $rows['fileUrl']);
												$filesExt = end($filesExt);
												if (($filesExt == 'png') || ($filesExt == 'jpg') || ($filesExt == 'gif')) {
													$fileType = '<i class="fa fa-file-image-o"></i>';
													$fileTarget = 'target="_blank"';
												} else if ($filesExt == 'pdf') {
													$fileType = '<i class="fa fa-file-pdf-o"></i>';
													$fileTarget = 'target="_blank"';
												} else if (($filesExt == 'doc') || ($filesExt == 'docx')) {
													$fileType = '<i class="fa fa-file-word-o"></i>';
													$fileTarget = '';
												} else if (($filesExt == 'xls') || ($filesExt == 'xlsx') || ($filesExt == 'csv')) {
													$fileType = '<i class="fa fa-file-excel-o"></i>';
													$fileTarget = '';
												} else if (($filesExt == 'zip') || ($filesExt == 'rar')) {
													$fileType = '<i class="fa fa-file-archive-o"></i>';
													$fileTarget = '';
												} else {
													$fileType = '<i class="fa fa-file-o"></i>';
													$fileTarget = '';
												}
										?>
												<li class="list-group-item task-lists">
													<small>
														<a href="#delete<?php echo $rows['fileId']; ?>" data-toggle="modal" class="pull-right">
															<i class="fa fa-trash text-danger" data-toggle="tooltip" data-placement="left" title="<?php echo $deleteFileTooltip; ?>"></i>
														</a>
													</small>
													<a href="<?php echo $userDocsPath.$userFolder.'/'.clean($rows['fileUrl']); ?>" class="btn-icon" <?php echo $fileTarget; ?>><?php echo $fileType; ?> <?php echo clean($rows['fileName']); ?></a><br />
													<small class="text-muted"><?php echo dateFormat($rows['uploadDate']); ?> by <?php echo clean($rows['savedBy']); ?></small>

													<div class="modal fade" id="delete<?php echo $rows['fileId']; ?>" tabindex="-1" role="dialog" aria-hidden="true">
														<div class="modal-dialog">
															<div class="modal-content">
																<form action="" method="post">
																	<div class="modal-body">
																		<p class="lead"><?php echo $deleteFileConf; ?> "<?php echo clean($rows['fileName']); ?>"?</p>
																	</div>
																	<div class="modal-footer">
																		<input type="hidden" name="assignedId" value="<?php echo clean($row['assignedId']); ?>" />
																		<input type="hidden" name="fileName" value="<?php echo clean($rows['fileName']); ?>" />
																		<input type="hidden" name="taskTitle" value="<?php echo clean($row['taskTitle']); ?>" />
																		<input type="hidden" name="fileUrl" value="<?php echo clean($rows['fileUrl']); ?>" />
																		<input type="hidden" name="deleteId" value="<?php echo clean($rows['fileId']); ?>" />
																		<button type="button" class="btn btn-default btn-sm btn-icon" data-dismiss="modal"><i class="fa fa-times-circle-o"></i> <?php echo $cancelBtn; ?></button>
																		<button type="input" name="submit" value="deleteFile" class="btn btn-success btn-sm btn-icon"><i class="fa fa-check-square-o"></i> <?php echo $yesBtn; ?></button>
																	</div>
																</form>
															</div>
														</div>
													</div>
												</li>
										<?php } ?>
									</ul>
								<?php } else { ?>
									<div class="alert alert-default mt-10" role="alert"><?php echo $noTaskFilesMsg; ?></div>
								<?php } ?>

								<a href="#upload" data-toggle="modal" class="btn btn-hover btn-info btn-sm btn-icon"><i class="fa fa-upload"></i><span><?php echo $uploadFileLink; ?></span></a>
								<div class="modal fade" id="upload" tabindex="-1" role="dialog" aria-hidden="true">
									<div class="modal-dialog">
										<div class="modal-content">
											<div class="modal-header">
												<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"><i class="fa fa-times"></i></span></button>
												<h4 class="modal-title"><?php echo $uploadFileH4; ?></h4>
											</div>
											<form action="" method="post" enctype="multipart/form-data">
												<div class="modal-body">
													<p>
														<small>
															<?php echo $uplFileTypesQuip; ?>: <?php echo $fileTypesAllowed; ?><br />
															<?php echo $maxFileSizeQuip; ?>: <?php echo $maxUpload; ?> <?php echo $mbText; ?>.
														</small>
													</p>

													<div class="form-group">
														<label for="fileName"><?php echo $fileTypeField; ?></label>
														<input type="text" class="form-control" name="fileName" id="fileName" required="required" maxlength="50" value="<?php echo isset($_POST['fileName']) ? $_POST['fileName'] : ''; ?>" />
														<span class="help-block"><?php echo $fileTypeFieldHelp; ?></span>
													</div>
													<div class="form-group">
														<label for="file"><?php echo $selFileField; ?></label>
														<input type="file" id="file" name="file" />
													</div>
												</div>
												<div class="modal-footer">
													<input type="hidden" name="assignedId" value="<?php echo clean($row['assignedId']); ?>" />
													<input type="hidden" name="taskTitle" value="<?php echo clean($row['taskTitle']); ?>" />
													<button type="button" class="btn btn-default btn-sm btn-icon" data-dismiss="modal"><i class="fa fa-times-circle-o"></i> <?php echo $cancelBtn; ?></button>
													<button type="input" name="submit" value="uploadFile" class="btn btn-success btn-sm btn-icon"><i class="fa fa-check-square-o"></i> Upload File</button>
												</div>
											</form>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="col-md-9 no-padding">
							<div class="mainCont pb-20">
								<?php if ($msgBox) { echo $msgBox; } ?>

								<p class="lead mt-20"><?php echo $mngTaskQuip; ?></p>

								<form action="" method="post" class="mt-20">
									<div class="row">
										<div class="col-md-6">
											<div class="form-group">
												<label for="taskTitle"><?php echo $taskTitleField; ?></label>
												<input type="text" class="form-control" name="taskTitle" id="taskTitle" required="required" maxlength="50" value="<?php echo clean($row['taskTitle']); ?>" />
												<span class="help-block"><?php echo $taskTitleFieldHelp; ?></span>
											</div>
										</div>
										<div class="col-md-6">
											<?php if ($row['isClosed'] == '1') { ?>
												<a href="#reopen" data-toggle="modal" class="btn btn-warning btn-sm btn-icon pull-right"><i class="fa fa-share"></i> <?php echo $reopenTaskLink; ?></a>
											<?php } ?>
										</div>
									</div>
									<div class="row">
										<div class="col-md-4">
											<div class="form-group">
												<label for="taskPriority"><?php echo $priorityText; ?></label>
												<input type="text" class="form-control" name="taskPriority" id="taskPriority" required="required" value="<?php echo clean($row['taskPriority']); ?>" />
												<span class="help-block"><?php echo $priorityFieldHelp; ?></span>
											</div>
										</div>
										<div class="col-md-4">
											<div class="form-group">
												<label for="taskStatus"><?php echo $statusText; ?></label>
												<input type="text" class="form-control" name="taskStatus" id="taskStatus" required="required" value="<?php echo clean($row['taskStatus']); ?>" />
												<span class="help-block"><?php echo $statusFieldHelp; ?></span>
											</div>
										</div>
										<div class="col-md-4">
											<div class="form-group">
												<label for="TaskDate"><?php echo $dueDateField; ?></label>
												<input type="text" class="form-control" name="TaskDate" id="TaskDate" required="required" value="<?php echo dbDateFormat($row['TaskDate']); ?>" />
												<span class="help-block"><?php echo $dueDateFieldHelp; ?></span>
											</div>
										</div>
									</div>
									<div class="form-group">
										<label for="taskDesc"><?php echo $taskDescField; ?></label>
										<textarea class="form-control" name="taskDesc" id="taskDesc" required="required" rows="5"><?php echo clean($row['taskDesc']); ?></textarea>
									</div>
									<div class="form-group">
										<label for="taskNotes"><?php echo $taskNotesField; ?></label>
										<textarea class="form-control" name="taskNotes" id="taskNotes" rows="5"><?php echo clean($row['taskNotes']); ?></textarea>
									</div>

									<button type="input" name="submit" value="editTask" class="btn btn-success btn-sm btn-icon"><i class="fa fa-check-square-o"></i> <?php echo $saveChangesBtn; ?></button>
								</form>

								<?php if ($row['isClosed'] == '1') { ?>
									<div class="modal fade" id="reopen" tabindex="-1" role="dialog" aria-hidden="true">
										<div class="modal-dialog">
											<div class="modal-content">
												<div class="modal-header">
													<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"><i class="fa fa-times"></i></span></button>
													<h4 class="modal-title"><?php echo $reopenTaskLink; ?></h4>
												</div>
												<form action="" method="post">
													<div class="modal-body">
														<p class="lead"><?php echo $reopenTaskConf; ?> "<?php echo clean($row['taskTitle']); ?>"?</p>
													</div>
													<div class="modal-footer">
														<input type="hidden" name="taskTitle" value="<?php echo clean($row['taskTitle']); ?>" />
														<button type="button" class="btn btn-default btn-sm btn-icon" data-dismiss="modal"><i class="fa fa-times-circle-o"></i> <?php echo $cancelBtn; ?></button>
														<button type="input" name="submit" value="reopen" class="btn btn-success btn-sm btn-icon"><i class="fa fa-check-square-o"></i> <?php echo $yesBtn; ?></button>
													</div>
												</form>
											</div>
										</div>
									</div>
								<?php } ?>
							</div>
						</div>
				<?php } else { ?>
					<div class="col-md-12 pb-20">
						<div class="alertMsg danger">
							<div class="msgIcon pull-left">
								<i class="fa fa-ban"></i>
							</div>
							<?php echo $accessErrorMsg; ?>
						</div>
					</div>
				<?php } ?>
			</div>
		</div>
	</div>