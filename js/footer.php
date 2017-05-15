<?php
	$openTasks = "SELECT
					*,
					UNIX_TIMESTAMP(TaskDate) AS orderDate
				FROM
					tasks
				WHERE
					assignedTo = ".$tz_userId." AND
					isClosed = 0
				ORDER BY orderDate
				LIMIT 4";
	$tasksres = mysqli_query($mysqli, $openTasks) or die('Error getting Footer Tasks Count' . mysqli_error());
?>
	
	<div class="container-fluid footerBar">
		<div class="container">
			<div class="row">
				<div class="col-md-6">
					<div class="copyright">
						<p>
							<i class="fa fa-copyright"></i> <?php echo date('Y'); ?> <?php echo $footertext; ?>
						</p>
					</div>
				</div>
				<div class="col-md-6" style="margin-left: -16px;">
					<div class="pull-right">
						<ul class="list-inline footer-nav">
							<li><a href="index.php"><?php echo $footerNav1; ?></a></li>
							<li><a href="index.php?page=messages"><?php echo $footerNav4; ?></a></li>
							<li><a href="index.php?page=myProfile"><?php echo $footerNav5; ?></a></li>
							<li><a data-toggle="modal" href="#signOut"><?php echo $footerNav6; ?></a></li>
						</ul>
					</div>
				</div>
			</div>
		</div>
	</div>

	<?php if (isset($fullCalendar)) { echo '<script type="text/javascript" src="js/moment.min.js"></script>'; } ?>
	<script type="text/javascript" src="js/jquery.min.js"></script>

	<script type="text/javascript" src="js/jquery.min.1.11"></script>

	
	<script type="text/javascript" src="js/bootstrap.min.js"></script>
	<?php
		if (isset($chosen)) {
			echo '
					<script type="text/javascript" src="js/chosen.jquery.min.js"></script>
					<script type="text/javascript" src="js/chosen.js"></script>
				';
		}
	?>
	<?php
		if (isset($fullCalendar)) {
			echo '
				<script type="text/javascript" src="js/fullcalendar.js"></script>
				<script type="text/javascript" src="js/lang-all.js"></script>
			';
		}
	?>
	<script type="text/javascript" src="js/simpleCalendar.js"></script>
	<script type="text/javascript" src="js/custom.js"></script>
	<script type="text/javascript" src="js/timeclock.js"></script>

	<?php
		if (isset($dataTables)) {
			echo '
				<script type="text/javascript" src="js/dataTables.js"></script>
				<script type="text/javascript" src="js/tableTools.js"></script>
			';
			include('js/tableTools.php');
		}
	?>
	<?php if (isset($datePicker)) { echo '<script type="text/javascript" src="js/datetimepicker.js"></script>'; } ?>
	<?php if (isset($jsFile)) { echo '<script type="text/javascript" src="js/includes/'.$jsFile.'.js"></script>'; } ?>
</body>
</html>