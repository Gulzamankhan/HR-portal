<?php
	// Logged in user's IP Address
	$actIp = $_SERVER['REMOTE_ADDR'];

	// Add a New Task
	if (isset($_POST['submit']) && $_POST['submit'] == 'newTask') {
		// User Validations
		 if($_POST['TaskDate'] == '') {
			$msgBox = alertBox($TaskDateDateReq, "<i class='fa fa-times-circle'></i>", "danger");
		} else if($_POST['TotalHours'] == '') {
			$msgBox = alertBox($TotalHoursReq, "<i class='fa fa-times-circle'></i>", "danger");
		} else {
			$taskTitle = htmlspecialchars($_POST['taskTitle']);
			$taskPriority = htmlspecialchars($_POST['taskPriority']);
			$taskStatus = htmlspecialchars($_POST['taskStatus']);
			$TaskDate = htmlspecialchars($_POST['TaskDate']);
			$TotalHours = htmlspecialchars($_POST['TotalHours']);

			$stmt = $mysqli->prepare("
								INSERT INTO
									tasks(
										userId,
										assignedTo,
										taskTitle,
										TotalHours,
										yearn,
										taskPriority,
										taskStatus,
										taskStart,
										TaskDate,
										lastUpdated,
										ipAddress
									) VALUES (
										?,
										?,
										?,
										?,
										NOW(),
										?,
										?,
										NOW(),
										?,
										NOW(),
										?
									)
			");
			$stmt->bind_param('ssssssss',
								$tz_userId,
								$tz_userId,
								$taskTitle,
								$TotalHours,
								$taskPriority,
								$taskStatus,
								$TaskDate,
								$actIp
			);
			$stmt->execute();
			$stmt->close();



			if (isset($_POST['addCal']) && $_POST['addCal'] == '1') {
				$msgBox = alertBox($newTaskMsg1." \"".$taskTitle."\" ".$newTaskSavedCalAddMsg, "<i class='fa fa-check-square'></i>", "success");
			} else {
				$msgBox = alertBox($newTaskMsg1." \"".$taskTitle."\" ".$newEventMsg2, "<i class='fa fa-check-square'></i>", "success");
			}

			// Clear the Form of values
			$_POST['taskTitle'] = $_POST['TotalHours'] = $_POST['taskPriority'] = $_POST['taskStatus'] = $_POST['TaskDate'] = $_POST['day1'] ='';
		}
	}

	$taskPage = 'true';
	$pageTitle = $newTaskPageTitle;
	$addCss = '<link rel="stylesheet" type="text/css" href="css/datetimepicker.css" />';
	$datePicker = 'true';
	$jsFile = 'newTask';

	include 'includes/header.php';
?>
	<div class="container-fluid">
		<div class="container">
			<div class="row pageCont">
				<div class="col-md-12 pb-20">
					<?php if ($msgBox) { echo $msgBox; } ?>

					<div class="tabbed-panel">
						<div class="tabbed-line">
							<div class="tab-content">
								<div class="clearfix mt-20"></div>
								<h4 class="head-title mt-10 mb-20">Enter Monthly Work Hours</h4>

								<form action="" method="post" class="mt-20">
									
									<div class="row">
										<div class="col-md-4">
										<div class="form-group">
												<label for="TaskDate">Date</label>
												<input type="text" class="form-control" name="TaskDate" id="TaskDate" required="required" value="<?php echo isset($_POST['TaskDate']) ? $_POST['TaskDate'] : ''; ?>" />
												<span class="help-block"><?php echo $dueDateFieldHelp; ?></span>
											</div>
										</div>
										<div class="col-md-4">
										<label for="TotalHours">Hours</label>
										<textarea class="form-control" name="TotalHours" id="TotalHours" required="required" rows="1"></textarea>
										</div>
										<div class="col-md-4">
											
										</div>
									</div>

									<div class="row">
										<div class="col-md-6">
											<div class="form-group">
												<label for="taskTitle">Description</label>
												<input type="text" class="form-control" name="taskTitle" id="taskTitle" required="required" maxlength="50" value="<?php echo isset($_POST['taskTitle']) ? $_POST['taskTitle'] : ''; ?>" />
												<span class="help-block"><?php echo $taskTitleFieldHelp; ?></span>
											</div>
											</div>
											<div class="col-md-6">
												<div class="form-group">												
												<label for="day1">Day1</label>
												<textarea class="form-control" name="day1" id="day1" required="" rows="1"></textarea>
											</div>
											</div>
										
									</div>
									<div class="row">
										<div class="col-md-6">
									<button type="input" name="submit" value="newTask" class="btn btn-success btn-sm btn-icon"><i class="fa fa-check-square-o"></i> Save Monthly data</button>
									</div>
											</div>
								</form>
							</div>
						</div>
					</div>

				</div>
			</div>
		</div>
	</div>