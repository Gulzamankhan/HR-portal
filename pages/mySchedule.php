<?php
	$acctPage = 'true';
	$pageTitle = $pageNotFoundHeader;
	if ($set['enableSchedule'] == '1') {
		$pageTitle = $mySchedulePageTitle;
		$addCss = '<link rel="stylesheet" type="text/css" href="css/fullcalendar.css" />
<style type="text/css">
	.fc-content { text-align: center !important; }
	.fc-time { display: none; }
	.fc-title { color: #555555 !important; }
</style>';
		$fullCalendar = 'true';
		$calInclude = 'true';
		$jsFile = 'mySchedule';
	}

	$activityType = '11';
	$activityTitle = $tz_userFull.' '.$mySchedPageViewAct;
	updateActivity($tz_userId,$activityType,$activityTitle);

	include 'includes/header.php';

?>
	<div class="container-fluid">
		<div class="container">
			<div class="row pageCont">
				<div class="col-md-12 pb-20">
					<?php if ($enableSched == '1') { ?>
						<?php if ($msgBox) { echo $msgBox; } ?>

						<input type="hidden" id="langCode" value="<?php echo $calLanguage; ?>" />
						<input type="hidden" id="schedForText" value="<?php echo $chedForText; ?>" />
						<input type="hidden" id="addedByText" value="<?php echo $addedByText; ?>" />

						<div id="loading">
							<span class="loader"></span><span class="loader"></span><span class="loader"></span><span class="loader"></span><span class="loader"></span>
						</div>

						<div id="calendar"></div>

						<div id="fullCalModal" class="modal fade">
							<div class="modal-dialog">
								<div class="modal-content">
									<div class="modal-header">
										<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"><i class="fa fa-times"></i></span></button>
										<h4 id="modalTitle" class="modal-title"></h4>
									</div>
									<div id="modalBody" class="modal-body">
										<span id="schedTimes" class="label"></span>
										<span id="createdBy" class="label"></span>
										<p id="schedHoursription" class="lead mt-10 mb-0"></p>
									</div>
									<div class="modal-footer">
										<button type="button" class="btn btn-default btn-sm btn-icon" data-dismiss="modal"><i class="fa fa-times-circle-o"></i> <?php echo $closeBtn; ?></button>
									</div>
								</div>
							</div>
						</div>
					<?php } else { ?>
						<div class="alertMsg warning">
							<div class="msgIcon pull-left">
								<i class="fa fa-warning"></i>
							</div>
							<?php echo $pageNotFoundQuip; ?>
						</div>
					<?php
							$activityType = '0';
							$activityTitle = $tz_userFull.' '.$mySchedPageViewErrAct;
							updateActivity($tz_userId,$activityType,$activityTitle);
						}
					?>
				</div>
			</div>
		</div>
	</div>