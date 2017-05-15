<?php
	$actIp = $_SERVER['REMOTE_ADDR'];

	if (isset($_POST['submit']) && $_POST['submit'] == 'glbSettings') {
	
		if($_POST['installUrl'] == '') {
			$msgBox = alertBox($installUrlReq, "<i class='fa fa-times-circle'></i>", "danger");
		} else if($_POST['siteName'] == '') {
			$msgBox = alertBox($siteNameReq, "<i class='fa fa-times-circle'></i>", "danger");
		} else if($_POST['businessName'] == '') {
			$msgBox = alertBox($busiNameReq, "<i class='fa fa-times-circle'></i>", "danger");
		} else if($_POST['siteEmail'] == '') {
			$msgBox = alertBox($siteEmailReq, "<i class='fa fa-times-circle'></i>", "danger");
		} else if($_POST['businessPhone'] == '') {
			$msgBox = alertBox($busiPhoneReq, "<i class='fa fa-times-circle'></i>", "danger");
		} else if($_POST['businessAddress'] == '') {
			$msgBox = alertBox($busiAddyReq, "<i class='fa fa-times-circle'></i>", "danger");
		} else {
			$installUrl = htmlspecialchars($_POST['installUrl']);
			if(substr($installUrl, -1) != '/') {
				$install = $installUrl.'/';
			} else {
				$install = $installUrl;
			}

			$siteName = htmlspecialchars($_POST['siteName']);
			$businessName = htmlspecialchars($_POST['businessName']);
			$siteEmail = htmlspecialchars($_POST['siteEmail']);
			$businessPhone = htmlspecialchars($_POST['businessPhone']);
			$contactPhone = htmlspecialchars($_POST['contactPhone']);
			$businessAddress = htmlspecialchars($_POST['businessAddress']);
			$enableSchedule = htmlspecialchars($_POST['enableSchedule']);
			$enableTimeEdits = htmlspecialchars($_POST['enableTimeEdits']);

			$stmt = $mysqli->prepare("UPDATE
										sitesettings
									SET
										installUrl = ?,
										siteName = ?,
										siteEmail = ?,
										businessName = ?,
										businessAddress = ?,
										businessPhone = ?,
										contactPhone = ?,
										enableSchedule = ?,
										enableTimeEdits = ?"
			);
			$stmt->bind_param('sssssssss',
									$install,
									$siteName,
									$siteEmail,
									$businessName,
									$businessAddress,
									$businessPhone,
									$contactPhone,
									$enableSchedule,
									$enableTimeEdits
			);
			$stmt->execute();
			$stmt->close();

			$activityType = '18';
			$activityTitle = $tz_userFull.' '.$siteSetUpdAct;
			updateActivity($tz_userId,$activityType,$activityTitle);

			$msgBox = alertBox($siteSetUpdMsg, "<i class='fa fa-check-square'></i>", "success");

			$set['enableSchedule'] = $enableSchedule;
		}
    }

	if (isset($_POST['submit']) && $_POST['submit'] == 'lclSettings') {

		if($_POST['weatherLocation'] == '') {
			$msgBox = alertBox($weatherLocReq, "<i class='fa fa-times-circle'></i>", "danger");
		} else {
			$localization = htmlspecialchars($_POST['localization']);
			$calLocalization = htmlspecialchars($_POST['calLocalization']);
			$weekStart = htmlspecialchars($_POST['weekStart']);
			$weatherLocation = htmlspecialchars($_POST['weatherLocation']);

			$stmt = $mysqli->prepare("UPDATE
										sitesettings
									SET
										localization = ?,
										calLocalization = ?,
										weekStart = ?,
										weatherLoc = ?"
			);
			$stmt->bind_param('ssss',
									$localization,
									$calLocalization,
									$weekStart,
									$weatherLocation
			);
			$stmt->execute();
			$stmt->close();

	
			$activityType = '18';
			$activityTitle = $tz_userFull.' '.$locSetUpdAct;
			updateActivity($tz_userId,$activityType,$activityTitle);

			$msgBox = alertBox($locSetUpdMsg, "<i class='fa fa-check-square'></i>", "success");

			$set['weekStart'] = $weekStart;
		}
    }

	if (isset($_POST['submit']) && $_POST['submit'] == 'uplSettings') {
		
		if($_POST['fileTypesAllowed'] == '') {
			$msgBox = alertBox($fileTypesReq, "<i class='fa fa-times-circle'></i>", "danger");
		} else if($_POST['avatarTypesAllowed'] == '') {
			$msgBox = alertBox($avatarTypesReq, "<i class='fa fa-times-circle'></i>", "danger");
		} else {
			$fileTypesAllowed = htmlspecialchars($_POST['fileTypesAllowed']);
			$avatarTypesAllowed = htmlspecialchars($_POST['avatarTypesAllowed']);

			$stmt = $mysqli->prepare("UPDATE
										sitesettings
									SET
										fileTypesAllowed = ?,
										avatarTypesAllowed = ?"
			);
			$stmt->bind_param('ss',
									$fileTypesAllowed,
									$avatarTypesAllowed
			);
			$stmt->execute();
			$stmt->close();

			
			$activityType = '18';
			$activityTitle = $tz_userFull.' '.$uplSetAct;
			updateActivity($tz_userId,$activityType,$activityTitle);

			$msgBox = alertBox($uplSetMsg, "<i class='fa fa-check-square'></i>", "success");
		}
    }

	
	$qry = "SELECT * FROM sitesettings";
	$res = mysqli_query($mysqli, $qry) or die('-1' . mysqli_error());
	$row = mysqli_fetch_assoc($res);

	if ($row['weekStart'] == '1') { 			$startWk = 'selected'; 		} else { $startWk = ''; }
	if ($row['localization'] == 'custom') { 	$custom = 'selected'; 		} else { $custom = ''; }
	if ($row['enableSchedule'] == '0') { 		$sched = 'selected'; 		} else { $sched = ''; }
	if ($row['enableTimeEdits'] == '0') { 		$edits = 'selected'; 		} else { $edits = ''; }

	if ($row['calLocalization'] == 'en') { 		$enSel = 'selected'; 		} else { $enSel = ''; }
	if ($row['calLocalization'] == 'ar-ma') { 	$ar_maSel = 'selected'; 	} else { $ar_maSel = ''; }
	if ($row['calLocalization'] == 'ar-sa') { 	$ar_saSel = 'selected'; 	} else { $ar_saSel = ''; }
	if ($row['calLocalization'] == 'ar-tn') { 	$ar_tnSel = 'selected'; 	} else { $ar_tnSel = ''; }
	if ($row['calLocalization'] == 'ar') { 		$arSel = 'selected'; 		} else { $arSel = ''; }
	if ($row['calLocalization'] == 'bg') { 		$bgSel = 'selected'; 		} else { $bgSel = ''; }
	if ($row['calLocalization'] == 'ca') { 		$caSel = 'selected'; 		} else { $caSel = ''; }
	if ($row['calLocalization'] == 'cs') { 		$csSel = 'selected'; 		} else { $csSel = ''; }
	if ($row['calLocalization'] == 'da') { 		$daSel = 'selected'; 		} else { $daSel = ''; }
	if ($row['calLocalization'] == 'de-at') { 	$de_atSel = 'selected'; 	} else { $de_atSel = ''; }
	if ($row['calLocalization'] == 'de') { 		$deSel = 'selected'; 		} else { $deSel = ''; }
	if ($row['calLocalization'] == 'el') { 		$elSel = 'selected'; 		} else { $elSel = ''; }
	if ($row['calLocalization'] == 'en-au') { 	$en_auSel = 'selected'; 	} else { $en_auSel = ''; }
	if ($row['calLocalization'] == 'en-ca') { 	$en_caSel = 'selected'; 	} else { $en_caSel = ''; }
	if ($row['calLocalization'] == 'en-gb') { 	$en_gbSel = 'selected'; 	} else { $en_gbSel = ''; }
	if ($row['calLocalization'] == 'es') { 		$esSel = 'selected'; 		} else { $esSel = ''; }
	if ($row['calLocalization'] == 'fa') { 		$faSel = 'selected'; 		} else { $faSel = ''; }
	if ($row['calLocalization'] == 'fi') { 		$fiSel = 'selected'; 		} else { $fiSel = ''; }
	if ($row['calLocalization'] == 'fr-ca') { 	$fr_caSel = 'selected'; 	} else { $fr_caSel = ''; }
	if ($row['calLocalization'] == 'fr') { 		$frSel = 'selected'; 		} else { $frSel = ''; }
	if ($row['calLocalization'] == 'he') { 		$heSel = 'selected'; 		} else { $heSel = ''; }
	if ($row['calLocalization'] == 'hi') { 		$hiSel = 'selected'; 		} else { $hiSel = ''; }
	if ($row['calLocalization'] == 'hr') { 		$hrSel = 'selected'; 		} else { $hrSel = ''; }
	if ($row['calLocalization'] == 'hu') { 		$huSel = 'selected'; 		} else { $huSel = ''; }
	if ($row['calLocalization'] == 'id') { 		$idSel = 'selected'; 		} else { $idSel = ''; }
	if ($row['calLocalization'] == 'is') { 		$isSel = 'selected'; 		} else { $isSel = ''; }
	if ($row['calLocalization'] == 'it') { 		$itSel = 'selected'; 		} else { $itSel = ''; }
	if ($row['calLocalization'] == 'ja') { 		$jaSel = 'selected'; 		} else { $jaSel = ''; }
	if ($row['calLocalization'] == 'ko') { 		$koSel = 'selected'; 		} else { $koSel = ''; }
	if ($row['calLocalization'] == 'lt') { 		$ltSel = 'selected'; 		} else { $ltSel = ''; }
	if ($row['calLocalization'] == 'lv') { 		$lvSel = 'selected'; 		} else { $lvSel = ''; }
	if ($row['calLocalization'] == 'nb') { 		$nbSel = 'selected'; 		} else { $nbSel = ''; }
	if ($row['calLocalization'] == 'nl') { 		$nlSel = 'selected'; 		} else { $nlSel = ''; }
	if ($row['calLocalization'] == 'pl') { 		$plSel = 'selected'; 		} else { $plSel = ''; }
	if ($row['calLocalization'] == 'pt-br') { 	$pt_brSel = 'selected'; 	} else { $pt_brSel = ''; }
	if ($row['calLocalization'] == 'pt') { 		$ptSel = 'selected'; 		} else { $ptSel = ''; }
	if ($row['calLocalization'] == 'ro') { 		$roSel = 'selected'; 		} else { $roSel = ''; }
	if ($row['calLocalization'] == 'ru') { 		$ruSel = 'selected'; 		} else { $ruSel = ''; }
	if ($row['calLocalization'] == 'sk') { 		$skSel = 'selected'; 		} else { $skSel = ''; }
	if ($row['calLocalization'] == 'sl') { 		$slSel = 'selected'; 		} else { $slSel = ''; }
	if ($row['calLocalization'] == 'sr-cyrl') { $sr_cyrlSel = 'selected'; 	} else { $sr_cyrlSel = ''; }
	if ($row['calLocalization'] == 'sr') { 		$srSel = 'selected'; 		} else { $srSel = ''; }
	if ($row['calLocalization'] == 'sv') { 		$svSel = 'selected'; 		} else { $svSel = ''; }
	if ($row['calLocalization'] == 'th') { 		$thSel = 'selected'; 		} else { $thSel = ''; }
	if ($row['calLocalization'] == 'tr') { 		$trSel = 'selected'; 		} else { $trSel = ''; }
	if ($row['calLocalization'] == 'uk') { 		$ukSel = 'selected'; 		} else { $ukSel = ''; }
	if ($row['calLocalization'] == 'vi') { 		$viSel = 'selected'; 		} else { $viSel = ''; }
	if ($row['calLocalization'] == 'zh-cn') { 	$zh_cnSel = 'selected'; 	} else { $zh_cnSel = ''; }
	if ($row['calLocalization'] == 'zh-tw') { 	$zh_twSel = 'selected'; 	} else { $zh_twSel = ''; }

	$mngPage = 'true';
	$pageTitle = $mngSettingsPageTitle;
	$addCss = '<link href="css/chosen.css" rel="stylesheet">';
	$chosen = 'true';

	include 'includes/header.php';
?>
	<div class="container-fluid">
		<div class="container">
			<div class="row pageCont">
				<div class="col-md-12 pb-20">
					<?php
						if ((checkArray('SITESET', $auths)) || $tz_superUser != '') {
							if ($msgBox) { echo $msgBox; }
					?>
							<h4 class="head-title mt-20 mb-20"><?php echo $mngSettingsH4; ?></h4>
							<form action="" method="post">
								<div class="row">
									<div class="col-md-6">
										<div class="form-group">
											<label for="installUrl"><?php echo $installUrlField; ?></label>
											<input type="text" class="form-control" name="installUrl" id="installUrl" required="required" value="<?php echo clean($row['installUrl']); ?>" />
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<label for="siteName"><?php echo $siteNameField; ?></label>
											<input type="text" class="form-control" name="siteName" id="siteName" required="required" value="<?php echo clean($row['siteName']); ?>" />
										
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-md-6">
										<div class="form-group">
											<label for="businessName"><?php echo $busiNameField; ?></label>
											<input type="text" class="form-control" name="businessName" id="businessName" required="required" value="<?php echo clean($row['businessName']); ?>" />
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<label for="siteEmail"><?php echo $siteEmailField; ?></label>
											<input type="text" class="form-control" name="siteEmail" id="siteEmail" required="required" value="<?php echo clean($row['siteEmail']); ?>" />
										
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-md-6">
										<div class="form-group">
											<label for="businessPhone"><?php echo $busiPhoneField; ?></label>
											<input type="text" class="form-control" name="businessPhone" id="businessPhone" required="required" value="<?php echo clean($row['businessPhone']); ?>" />
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<label for="contactPhone"><?php echo $contactPhoneField; ?></label>
											<input type="text" class="form-control" name="contactPhone" id="contactPhone" value="<?php echo clean($row['contactPhone']); ?>" />
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-md-6">
										<div class="form-group">
											<label for="businessAddress"><?php echo $busiAddyField; ?></label>
											<textarea class="form-control" required="required" name="businessAddress" id="businessAddress" rows="3"><?php echo $row['businessAddress']; ?></textarea>
										</div>
									</div>
								</div>
								<button type="input" name="submit" value="glbSettings" class="btn btn-success btn-sm btn-icon"><i class="fa fa-check-square-o"></i> Save Settings</button>
							</form>

							<hr />

							

							<h4 class="head-title mt-20 mb-20"><?php echo $mngUplSettingsH4; ?></h4>
							<form action="" method="post">
								<div class="row">
									<div class="col-md-6">
										<div class="form-group">
											<label for="fileTypesAllowed"><?php echo $uplFileTypesField; ?></label>
											<input type="text" class="form-control" name="fileTypesAllowed" id="fileTypesAllowed" required="required" value="<?php echo clean($row['fileTypesAllowed']); ?>" />
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<label for="avatarTypesAllowed"><?php echo $avatarFileTypesField; ?></label>
											<input type="text" class="form-control" name="avatarTypesAllowed" id="avatarTypesAllowed" required="required" value="<?php echo clean($row['avatarTypesAllowed']); ?>" />
										</div>
									</div>
								</div>
								<button type="input" name="submit" value="uplSettings" class="btn btn-success btn-sm btn-icon"><i class="fa fa-check-square-o"></i> <?php echo $saveUplSetBtn; ?></button>
							</form>
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