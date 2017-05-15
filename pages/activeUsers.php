<?php
	
	if ($tz_superUser != '') {
		$sql = "SELECT
					users.*,
					CONCAT(usr.userFirst,' ',usr.userLast) AS theManager
				FROM
					users
					LEFT JOIN users AS usr ON users.managerId = usr.userId
				WHERE users.isActive = 1";
		$res = mysqli_query($mysqli, $sql) or die('-1' . mysqli_error());

		$userPhone1 = decodeIt($row['userPhone1']);
	} else {
		$sql = "SELECT
					users.*
				FROM
					users
				WHERE users.isActive = 1 AND users.managerId = ".$tz_userId;
		$res = mysqli_query($mysqli, $sql) or die('-2' . mysqli_error());
	}



	
	if (isset($_POST['submit']) && $_POST['submit'] == 'deleteDoc') {

		$deleteId = htmlspecialchars($_POST['deleteId']);
		$userFirst = htmlspecialchars($_POST['userFirst']);

		
		$stmt = $mysqli->prepare("DELETE FROM users WHERE userId = ".$deleteId);
		$stmt->execute();
		$stmt->close();

		$msgBox = alertBox(" The user\"".$userFirst."\" ".$mngTaskDelFileAct2, "<i class='fa fa-check-square'></i>", "success");

	}

	

	$mngPage = 'true';
	$pageTitle = $mngusersPageTitle;
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

									<ul class="nav nav-tabs">
			<li class="active"><a href="#home" data-toggle="tab"><i class="fa fa-group"></i> Active Users</a></li>
			<li><a href="index.php?page=inactiveUsers"><i class="fa fa-ban"></i> Inactive Users</a></li>
			<li class="pull-right"><a href="index.php?page=newUser" class="bg-success"><i class="fa fa-plus-square"></i> New User</a></li>

		</ul>


							<?php if(mysqli_num_rows($res) > 0) { ?>
								<table id="docs" class="display" cellspacing="0" width="100%">
									<thead>
										<tr>
											<th><?php echo $usersNameTh; ?></th>
											<th><?php echo $positionTh; ?></th>
											<th><?php echo $emailTh; ?></th>
											<th class="text-center"><?php echo $hireDtTh; ?></th>
											<th class="text-center"><?php echo $lastSignInTh; ?></th>
											<th></th>
										</tr>
									</thead>

									<tbody>
										<?php while ($row = mysqli_fetch_assoc($res)) { ?>
											<tr>
												<td>
														<?php if ($row['userId'] != '1' || $tz_userId == '1') { ?>
															<a href="index.php?page=viewUser&userId=<?php echo $row['userId']; ?>" data-toggle="tooltip" data-placement="right" title="<?php echo $viewUserTooltip; ?>">
																<?php echo clean($row['userFirst']).' '.clean($row['userLast']); ?>
															</a>
														<?php } else { ?>
															<strong><?php echo clean($row['userFirst']).' '.clean($row['userLast']); ?></strong>
														<?php } ?>
													</td>
													<td><?php echo clean($row['userPosition']); ?></td>
													<td><?php echo clean($row['userEmail']); ?></td>
													<td class="text-center"><?php echo dateFormat($row['userHireDate']); ?></td>
													<td class="text-center"><?php echo $lastVisited; ?></td>
												<td class="text-right">
													<a href="#delete<?php echo $row['userId']; ?>" data-toggle="modal">
														<i class="fa fa-trash text-danger" data-toggle="tooltip" data-placement="left" title="<?php echo $delUserTooltip; ?>"></i>
													</a>

													<div class="modal fade" id="delete<?php echo $row['userId']; ?>" tabindex="-1" role="dialog" aria-hidden="true">
														<div class="modal-dialog">
															<div class="modal-content">
																<form action="" method="post">
																	<div class="modal-body">
																		<p class="lead mt-0">Do you want to delete the user "<?php echo clean($row['userFirst']); ?>"?</p>
																		<input type="hidden" name="userFirst" value="<?php echo clean($row['userFirst']); ?>" />
																	</div>
																	<div class="modal-footer">
																		<input type="hidden" name="deleteId" value="<?php echo $row['userId']; ?>" />
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
									No Users Found
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