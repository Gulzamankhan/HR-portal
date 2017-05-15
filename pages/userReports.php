<?php
	$rptPage = 'true';
	$pageTitle = $userReportsPageTitle;

	include 'includes/header.php';
?>
	<div class="container-fluid">
		<div class="container">
			<div class="row pageCont">
				<div class="col-md-12 pb-20">
					<?php
						if ((checkArray('USRRPT', $auths)) || $tz_superUser != '') {
							if ($msgBox) { echo $msgBox; }
					?>
							<h3 class="head-title mb-10"><?php echo $userReportsH3; ?></h3>
							<div class="row">
								<div class="col-md-6">
									<form action="index.php?page=userRpt" method="post">
										<fieldset class="default">
											<legend class="default"><?php echo $usersLegend; ?></legend>

											<div class="form-group" data-toggle="buttons">
												<label for="usrType" class="btn btn-default btn-sm toggle-checkbox active primary mt-10">
													<i class="fa fa-fw"></i>
													<input name="usrType" id="allUsrs" type="radio" value="all" checked />
													<?php echo $allUsersOpt; ?>
												</label>
												<label for="usrType" class="btn btn-default btn-sm toggle-checkbox primary mt-10">
													<i class="fa fa-fw"></i>
													<input name="usrType" id="actUsrs" type="radio" value="active" />
													<?php echo $actUsersNav; ?>
												</label>
												<label for="usrType" class="btn btn-default btn-sm toggle-checkbox primary mt-10">
													<i class="fa fa-fw"></i>
													<input name="usrType" id="inactUsrs" type="radio" value="inactive" />
													<?php echo $inactUsersNav; ?>
												</label>
												<span class="help-block"><?php echo $selAccTypesHelp; ?></span>
											</div>


											<input type="hidden" name="rptType" value="usrReport" />
											<button type="input" name="submit" value="runRpt" class="btn btn-success btn-sm btn-icon-alt"><?php echo $runRptBtn; ?> <i class="fa fa-long-arrow-right"></i></button>
										</fieldset>
									</form>
								</div>
								<div class="col-md-6">
									<form action="index.php?page=userRpt" method="post">
										<fieldset class="default">
											<legend class="default"><?php echo $admMngsLegend; ?></legend>

											<div class="form-group" data-toggle="buttons">
												<label for="mgrType" class="btn btn-default btn-sm toggle-checkbox active primary mt-10">
													<i class="fa fa-fw"></i>
													<input name="mgrType" id="allUsrs" type="radio" value="all" checked />
													<?php echo $admMngOptAll; ?>
												</label>
												<label for="mgrType" class="btn btn-default btn-sm toggle-checkbox primary mt-10">
													<i class="fa fa-fw"></i>
													<input name="mgrType" id="actUsrs" type="radio" value="active" />
													<?php echo $admMngOptActive; ?>
												</label>
												<label for="mgrType" class="btn btn-default btn-sm toggle-checkbox primary mt-10">
													<i class="fa fa-fw"></i>
													<input name="mgrType" id="inactUsrs" type="radio" value="inactive" />
													<?php echo $admMngOptInactive; ?>
												</label>
												<span class="help-block"><?php echo $selAccTypesHelp; ?></span>
											</div>

											<input type="hidden" name="rptType" value="admReport" />
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