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

	
	if ($tz_superUser != '') {
		
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
					users.managerId = ".$tz_userId." OR
					userdocs.userId = ".$tz_userId;
		$res = mysqli_query($mysqli, $sql) or die('-3' . mysqli_error());
	}

	$mngPage = 'true';
	$pageTitle = $mngDocumentsPageTitle;
	$addCss = '<link href="css/dataTables.css" rel="stylesheet">';
	$dataTables = 'true';
	$jsFile = 'mngDocuments';

	include 'includes/header.php';
?>
	<div class="container-fluid">
		<div class="container">
			<div class="row pageCont">
				<div class="col-md-12 pb-20">
					<?php
						if ((checkArray('MNGUSRDOCS', $auths)) || $tz_superUser != '') {
							if ($msgBox) { echo $msgBox; }
					?>
							<h3 class="head-title"><?php echo $mngDocumentsPageQuip; ?></h3>
							<p class="text-right mb-20"><a href="#upload" data-toggle="modal" class="btn btn-info btn-xs btn-icon"><i class="fa fa-upload"></i> <?php echo $uplDocLink; ?></a></p>
							<div class="modal fade" id="upload" tabindex="-1" role="dialog" aria-hidden="true">
								<div class="modal-dialog modal-lg">
									<div class="modal-content">
										<div class="modal-header">
											<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"><i class="fa fa-times"></i></span></button>
											<h4 class="modal-title"><?php echo $uplDocH4; ?></h4>
										</div>
										<form action="" method="post" enctype="multipart/form-data">
											<div class="modal-body">
												<p>
													<small>
														<?php echo $uplFileTypesQuip; ?>: <?php echo $fileTypesAllowed; ?><br />
														<?php echo $maxFileSizeQuip; ?>: <?php echo $maxUpload; ?> <?php echo $mbText; ?>.
													</small>
												</p>

												<div class="row">
													<div class="col-md-6">
														<div class="form-group">
															<label for="fileName"><?php echo $fileTypeField; ?></label>
															<input type="text" class="form-control" name="fileName" id="fileName" required="required" maxlength="50" value="<?php echo isset($_POST['fileName']) ? $_POST['fileName'] : ''; ?>" />
															<span class="help-block"><?php echo $fileTypeFieldHelp; ?></span>
														</div>
													</div>
													<div class="col-md-6">
														<div class="form-group">
															<label for="usrId"><?php echo $selectUserField; ?></label>
															<?php
																if ($tz_superUser != '') {
																	
																	$usrqry = "SELECT userId, userFirst, userLast FROM users WHERE isActive = 1 AND userId != ".$tz_userId;
																	$usrres = mysqli_query($mysqli, $usrqry) or die('-4'.mysqli_error());
																} else {
																	
																	$usrqry = "SELECT userId, userFirst, userLast FROM users WHERE managerId = ".$tz_userId." AND isActive = 1 AND userId != ".$tz_userId;
																	$usrres = mysqli_query($mysqli, $usrqry) or die('-5'.mysqli_error());
																}
															?>
															<select class="form-control" name="usrId" id="usrId">
																<option value="..."><?php echo $selectOption; ?></option>
																<?php
																	while ($usr = mysqli_fetch_assoc($usrres)) {
																		echo '<option value="'.$usr['userId'].'">'.$usr['userFirst'].' '.$usr['userLast'].'</option>';
																	}
																?>
															</select>
															<span class="help-block"><?php echo $selectUserFieldHelp; ?></span>
														</div>
													</div>
												</div>
												<div class="form-group">
													<label for="file"><?php echo $selFileField; ?></label>
													<input type="file" id="file" name="file" />
												</div>
											</div>
											<div class="modal-footer">
												<button type="button" class="btn btn-default btn-sm btn-icon" data-dismiss="modal"><i class="fa fa-times-circle-o"></i> <?php echo $cancelBtn; ?></button>
												<button type="input" name="submit" value="uploadFile" class="btn btn-success btn-sm btn-icon"><i class="fa fa-check-square-o"></i> <?php echo $uplDocBtn; ?></button>
											</div>
										</form>
									</div>
								</div>
							</div>

							<?php if(mysqli_num_rows($res) > 0) { ?>
								<table id="docs" class="display" cellspacing="0" width="100%">
									<thead>
										<tr>
											<th><?php echo $docTitleTh; ?></th>
											<th><?php echo $userTh; ?></th>
											<th><?php echo $uploadedBtTh; ?></th>
											<th><?php echo $fileLocTh; ?></th>
											<th class="text-center"><?php echo $dateUplTh; ?></th>
											<th></th>
										</tr>
									</thead>

									<tbody>
										<?php while ($row = mysqli_fetch_assoc($res)) { ?>
											<tr>
												<td>
													<a href="<?php echo $userDocsPath.$row['usrFolder'].'/'.$row['docUrl']; ?>" target="_blank" data-toggle="tooltip" data-placement="right" title="<?php echo $viewDocTooltip; ?>">
														<?php echo clean($row['docTitle']); ?>
													</a>
												</td>
												<td><?php echo clean($row['TheUser']); ?></td>
												<td><?php echo clean($row['uplBy']); ?></td>
												<td><?php echo $userDocsPath.clean($row['usrFolder']); ?></td>
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