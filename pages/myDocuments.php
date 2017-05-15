<?php
	$actIp = $_SERVER['REMOTE_ADDR'];


 $maxUpload = (int)(ini_get('upload_max_filesize'));

	$userDocsPath = $set['userDocsPath'];

	$filesAllowed = $set['fileTypesAllowed'];
	$fileTypesAllowed = preg_replace('/,/', ', ', $filesAllowed);

	
	$fldr = "SELECT userFolder FROM users WHERE userId = ".$tz_userId;
	$fldrres = mysqli_query($mysqli, $fldr) or die('-1' . mysqli_error());
	$usrFldr = mysqli_fetch_assoc($fldrres);
	$userFolder = $usrFldr['userFolder'];

	
	if (isset($_POST['submit']) && $_POST['submit'] == 'uploadFile') {
		
		if($_POST['fileName'] == 'qqqqqq') {
			$msgBox = alertBox($fileTitleReq, "<i class='fa fa-times-circle'></i>", "danger");
		} else {
			for($i=0; $i<count($_FILES['file']['name']); $i++) {
			$fileName = htmlspecialchars($_FILES['file']['name']);
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
				$monthn = date('F');
				$newFileName = $fullName.'.'.$extension;
				$movePath = $userDocsPath.$userFolder.'/'.$newFileName;
				$stmt = $mysqli->prepare("
				INSERT INTO userdocs( userId,	uploadedBy,	docTitle,	docUrl,	uploadDate,	ipAddress,	monthn	) 
				VALUES (	?,	?,	?,	?,	NOW(),	?,	?	)");
				$stmt->bind_param('ssssss',
					$tz_userId,
					$tz_userId,
					$fileName,
					$newFileName,
					$actIp,
					$monthn
				);

				if (move_uploaded_file($_FILES['file']['tmp_name'], $movePath)) {
					$stmt->execute();
					$msgBox = alertBox($uplDocMsg." \"".$fileName."\" ".$uplDocMsg1,"<i class='fa fa-check-square'></i>", "success");
					$stmt->close();

					
					$activityType = '12';
					$activityTitle = $tz_userFull.' '.$uplDocAct.' '.$newFileName;
					updateActivity($tz_userId,$activityType,$activityTitle);
				} else {
					$msgBox = alertBox($uplDocErrMsg, "<i class='fa fa-times-circle'></i>", "danger");

					
					$activityType = '0';
					$activityTitle = $uplDocActErr.' "'.$tz_userFull.'" '.$uplDocActErr1;
					updateActivity($tz_userId,$activityType,$activityTitle);
				}
				
				$_POST['fileName'] = '';
			}}
		}
	}

	
	if (isset($_POST['submit']) && $_POST['submit'] == 'deleteDoc') {
		$docTitle = htmlspecialchars($_POST['docTitle']);
		$docUrl = htmlspecialchars($_POST['docUrl']);
		$deleteId = htmlspecialchars($_POST['deleteId']);

		
		$filePath = $userDocsPath.$userFolder.'/'.$docUrl;

		
		if (file_exists($filePath)) {
			unlink($filePath);

			$stmt = $mysqli->prepare("DELETE FROM userdocs WHERE docId = ".$deleteId);
			$stmt->execute();
			$stmt->close();

			$msgBox = alertBox($uplDocAct." \"".$docTitle."\" ".$mngTaskDelFileAct2, "<i class='fa fa-check-square'></i>", "success");

			
			$activityType = '10';
			$activityTitle = $tz_userFull.' '.$delDocAct.' "'.$docUrl.'"';
			updateActivity($tz_userId,$activityType,$activityTitle);
		} else {
			$msgBox = alertBox($delDocErrMsg, "<i class='fa fa-warning'></i>", "warning");

			
			$activityType = '0';
			$activityTitle = $uplDocMsg.' "'.$docUrl.'" '.$mngTaskFileDelAct1;
			updateActivity($tz_userId,$activityType,$activityTitle);
		}
	}

	
	$qry = "SELECT
				userdocs.*,
				CONCAT(users.userFirst,' ',users.userLast) AS savedBy
			FROM
				userdocs
				LEFT JOIN users ON userdocs.uploadedBy = users.userId
			WHERE userdocs.userId = ".$tz_userId;
	$res = mysqli_query($mysqli, $qry) or die('-2' . mysqli_error());


	$acctPage = 'true';
	$pageTitle = $myDocumentsPageTitle;
	$addCss = '<link href="css/dataTables.css" rel="stylesheet">';
	$dataTables = 'true';
	$jsFile = 'myDocuments';

	include 'includes/header.php';
?>
	<div class="container-fluid">
		<div class="container">
			<div class="row pageCont">
				<div class="col-md-12 pb-20">
					<?php if ($msgBox) { echo $msgBox; } ?>

					<h3 class="head-title"><?php echo $myDocsH3; ?></h3>
					<p class="text-right mb-20 "><a href="#upload" data-toggle="modal" class="btn btn-hover btn-info btn-xs"><i class="fa fa-upload"></i><span><?php echo $uplDocBtn; ?></span></a></p>
					<div class="modal fade" id="upload" tabindex="-1" role="dialog" aria-hidden="true">
						<div class="modal-dialog">
							<div class="modal-content">
								<div class="modal-header">
									<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"><i class="fa fa-times"></i></span></button>
									<h4 class="modal-title">Upload Client Approved Timesheet</h4>
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
											<label for="file"><?php echo $selFileField; ?></label>
											<input type="file" id="file" name="file" multiple="multiple" />
										</div>
									</div>
									<div class="modal-footer">
										<button type="button" class="btn btn-default btn-sm btn-icon" data-dismiss="modal"><i class="fa fa-times-circle-o"></i> <?php echo $cancelBtn; ?></button>
										<button type="input" name="submit" value="uploadFile" class="btn btn-success btn-sm btn-icon"><i class="fa fa-check-square-o"></i> <?php echo $uplDocumentBtn; ?></button>
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
									<th class="text-center"><?php echo $uploadedBtTh; ?></th>
									<th class="text-center"><?php echo $dateUplTh; ?></th>
									<th></th>
								</tr>
							</thead>

							<tbody>
								<?php while ($row = mysqli_fetch_assoc($res)) { ?>
									<tr>
										<td>
											<a href="<?php echo $userDocsPath.$userFolder.'/'.$row['docUrl']; ?>" target="_blank" data-toggle="tooltip" data-placement="right" title="<?php echo $viewDocTooltip; ?>">
												<?php echo clean($row['docTitle']); ?>
											</a>
										</td>
										<td class="text-center"><?php echo clean($row['savedBy']); ?></td>
										<td class="text-center"><?php echo dateFormat($row['uploadDate']); ?></td>
										<td class="text-right">
											<?php if ($row['uploadedBy'] == $tz_userId) { ?>
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
																	<input type="hidden" name="docUrl" value="<?php echo clean($row['docUrl']); ?>" />
																	<button type="button" class="btn btn-default btn-sm btn-icon" data-dismiss="modal"><i class="fa fa-times-circle-o"></i> <?php echo $cancelBtn; ?></button>
																	<button type="input" name="submit" value="deleteDoc" class="btn btn-success btn-sm btn-icon"><i class="fa fa-check-square-o"></i> <?php echo $yesBtn; ?></button>
																</div>
															</form>
														</div>
													</div>
												</div>
											<?php } else { ?>
												<i class="fa fa-trash text-muted" data-toggle="tooltip" data-placement="left" title="<?php echo $disabledTooltip; ?>"></i>
											<?php } ?>
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
							<?php echo $noDocsMsg; ?>
						</div>
					<?php } ?>
				</div>
			</div>
		</div>
	</div>