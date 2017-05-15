<?php
	$userId = htmlspecialchars($_GET['userId']);

	$userMonth = htmlspecialchars($_GET['userMonth']);

	$actIp = $_SERVER['REMOTE_ADDR'];


 $maxUpload = (int)(ini_get('upload_max_filesize'));

	$userDocsPath = $set['userDocsPath'];

	$filesAllowed = $set['fileTypesAllowed'];
	$fileTypesAllowed = preg_replace('/,/', ', ', $filesAllowed);

	
	$fldr = "SELECT userFolder FROM users WHERE userId = ".$userId;
	$fldrres = mysqli_query($mysqli, $fldr) or die('-1' . mysqli_error());
	$usrFldr = mysqli_fetch_assoc($fldrres);
	$userFolder = $usrFldr['userFolder'];


	
	$qry = "SELECT
				userdocs.*,
				CONCAT(users.userFirst,' ',users.userLast) AS savedBy
			FROM
				userdocs
				LEFT JOIN users ON userdocs.uploadedBy = users.userId
			WHERE monthn = '".$userMonth."' AND userdocs.userId = ".$userId;
	$res = mysqli_query($mysqli, $qry) or die('-2' . mysqli_error());


	$userfirst1 = "SELECT userFirst FROM users WHERE userId = ".$userId;
	$userfirst12 = mysqli_query($mysqli, $userfirst1) or die('-1' . mysqli_error());
	$userfirst13 = mysqli_fetch_assoc($userfirst12);
	$userFirst = $userfirst13['userFirst'];


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

					<h3 class="head-title">Documents uploaded by <?php echo $userFirst ?></h3>
					


					<?php if(mysqli_num_rows($res) > 0) { ?>
						<table id="docs" class="display" cellspacing="0" width="100%">
							<thead>
								<tr>
									<th><?php echo $docTitleTh; ?></th>
									<th class="text-center"><?php echo $uploadedBtTh; ?></th>
									<th class="text-center"><?php echo $dateUplTh; ?></th>
								</tr>
							</thead>

							<tbody>
								<?php while ($row = mysqli_fetch_assoc($res)) { ?>
									<tr>
										<td>
											<a href="<?php echo $userDocsPath.$userFolder.'/'.$row['docUrl']; ?>" target="_blank" data-toggle="tooltip" data-placement="right" title="Download Document">
												<?php echo clean($row['docTitle']); ?>
											</a>
										</td>
										<td class="text-center"><?php echo clean($row['savedBy']); ?></td>
										<td class="text-center"><?php echo dateFormat($row['uploadDate']); ?></td>
										
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
							<?php 
							$noDocsMessage = 'No uploaded Documents.';
							echo $noDocsMessage; ?>
						</div>
					<?php } ?>
				</div>
			</div>
		</div>
	</div>