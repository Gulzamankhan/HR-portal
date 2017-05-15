<?php
	$rptPage = 'true';
	$pageTitle = $timeReportsPageTitle;
	$addCss = '<link rel="stylesheet" type="text/css" href="css/datetimepicker.css" />';
	$datePicker = 'true';
	$jsFile = 'timeReports';

	include 'includes/header.php';
?>
	<div class="container-fluid">
		<div class="container">
			<div class="row pageCont">
				<div class="col-md-12 pb-20">
				<?php $monthcur = date('F'); ?>
				
					<?php
						if ((checkArray('TIMERPT', $auths)) || $tz_superUser != '') {
							if ($msgBox) { echo $msgBox; }
					?>
							<h3 class="head-title mb-10"><?php echo $timeReportsH3; ?></h3>
							<div class="row">
								<div class="col-md-3">
									</div>

							<div class="col-md-6">
									<form action="index.php?page=attRptM" method="post">
										<fieldset class="default">
											<legend class="default">TIME LOGS BY USER & MONTHS</legend>


														<?php
															$usrqry = "SELECT userId, CONCAT(userFirst,' ',userLast) AS user FROM users WHERE userId != 1";
															$usrres = mysqli_query($mysqli, $usrqry) or die('-1'.mysqli_error());
														?>
														<label for="users"><?php echo $slctUsersField; ?></label>
														<select id="users" multiple class="form-control selectall" size="4" name="userId[]">
															<option value="all" selected><?php echo $allUsersOpt; ?></option>
															<?php
																while ($u = mysqli_fetch_assoc($usrres)) {
																	echo '<option value="'.$u['userId'].'">'.$u['user'].'</option>';
																}
															?>
														</select>
														<div class="form-group col-md-6">
															<label for="recFromMonth"><?php echo Month; ?></label>

															<select class="form-control" name="fromMonth">

															<?php
															if ($monthcur == "January") {
																$selected1 = 'selected="selected"';
															} 
															else if ($monthcur == "February") {
																$selected2 = 'selected="selected"';
															} 
															else if ($monthcur == "March") {
																$selected3 = 'selected="selected"';
															} 
															else if ($monthcur == "April") {
																$selected4 = 'selected="selected"';
															} 
															else if ($monthcur == "May") {
																$selected5 = 'selected="selected"';
															} 
															else if ($monthcur == "June") {
																$selected6 = 'selected="selected"';
															} 
															else if ($monthcur == "July") {
																$selected7 = 'selected="selected"';
															} 
															else if ($monthcur == "August") {
																$selected8 = 'selected="selected"';
															} 
															else if ($monthcur == "September") {
																$selected9 = 'selected="selected"';
															} 
															else if ($monthcur == "October") {
																$selected10 = 'selected="selected"';
															} 
															else if ($monthcur == "November") {
																$selected11 = 'selected="selected"';
															} 
															else if ($monthcur == "December") {
																$selected12 = 'selected="selected"';
															} 
														?>
														


														<option value="january" <?php echo $selected1; ?>>Jan</option>
														<option value="february" <?php echo $selected2; ?> >Feb</option>
														<option value="march"<?php echo $selected3; ?> >Mar</option>
														<option value="april"<?php echo $selected4; ?> >Apr</option>
														<option value="may"<?php echo $selected5; ?>>May</option>
														<option value="june"<?php echo $selected6; ?>>June</option>
														<option value="july"<?php echo $selected7; ?>>July</option>
														<option value="august"<?php echo $selected8; ?>>Aug</option>
														<option value="september"<?php echo $selected9; ?>>Sep</option>
														<option value="october"<?php echo $selected10; ?>>Oct</option>
														<option value="november"<?php echo $selected11; ?>>Nov</option>
														<option value="december"<?php echo $selected12; ?>>Dec</option>
													</select>

														</div>

														<div class="form-group col-md-6">
															<label for="recFromYear"><?php echo Year; ?></label>

															<select class="form-control" name="fromYear">

														<option value="2016">2016</option>
															</select>

														</div>
														<div class="col-md-4">
									</div>
									<div class="col-md-4">
									<button type="input" name="submit" value="runRpt" class="btn btn-success btn-sm btn-icon-alt"><?php echo $runRptBtn; ?> <i class="fa fa-long-arrow-right"></i></button>
									</div>
									<div class="col-md-4">
									</div>


												
										</fieldset>
									</form>
								</div>
								
								<div class="col-md-3 hide">
									<form action="index.php?page=attRpt" method="post">
										<fieldset class="default">
											<legend class="default"><?php echo $timeLogsLegend; ?></legend>
														<?php
															$usrqry = "SELECT userId, CONCAT(userFirst,' ',userLast) AS user FROM users WHERE userId != 1";
															$usrres = mysqli_query($mysqli, $usrqry) or die('-1'.mysqli_error());
														?>
														<label for="users"><?php echo $slctUsersField; ?></label>
														<select id="users" multiple class="form-control selectall" size="4" name="userId[]">
															<option value="all" selected><?php echo $allUsersOpt; ?></option>
															<?php
																while ($u = mysqli_fetch_assoc($usrres)) {
																	echo '<option value="'.$u['userId'].'">'.$u['user'].'</option>';
																}
															?>
														</select>
														<div class="form-group mt-20">
															<label for="recFromDate"><?php echo $showRecsFromField; ?></label>
															<input type="text" class="form-control" name="fromDate" id="recFromDate" required="required" value="" />
															<span class="help-block"><?php echo $showRecsFromFieldHelp; ?></span>
														</div>

														<div class="form-group">
															<label for="recToDate"><?php echo $showRecsToField; ?></label>
															<input type="text" class="form-control" name="toDate" id="recToDate" required="required" value="" />
															<span class="help-block"><?php echo $showRecsToFieldHelp; ?></span>
														</div>

												<button type="input" name="submit" value="runRpt" class="btn btn-success btn-sm btn-icon-alt"><?php echo $runRptBtn; ?> <i class="fa fa-long-arrow-right"></i></button>
										</fieldset>
									</form>
								</div>

								
							</div>
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