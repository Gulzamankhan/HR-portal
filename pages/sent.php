<?php
	$sql = "SELECT
				privatemessages.*,
				CONCAT(users.userFirst,' ',users.userLast) AS msgFrom
			FROM
				privatemessages
				LEFT JOIN users ON privatemessages.toId = users.userId
			WHERE
				privatemessages.fromId = ".$tz_userId." AND
				privatemessages.fromDeleted = 0";
	$res = mysqli_query($mysqli, $sql) or die('-1' . mysqli_error());

	$msgPage = 'true';
	$pageTitle = $sentPageTitle;
	$addCss = '<link href="css/dataTables.css" rel="stylesheet">';
	$dataTables = 'true';
	$jsFile = 'sent';

	include 'includes/header.php';
?>
	<div class="container-fluid">
		<div class="container">
			<div class="row pageCont">
				<div class="col-md-12 pb-20">
					<?php if ($msgBox) { echo $msgBox; } ?>

					<div class="tabbed-panel">
						<div class="tabbed-line">
							<ul class="nav nav-tabs ">
								<li><a href="index.php?page=messages"><?php echo $inboxNav; ?></a></li>
								<li class="active"><a href="#sent" data-toggle="tab"><?php echo $sentNav; ?></a></li>
								<li><a href="index.php?page=archived"><?php echo $archivedNav; ?></a></li>
								<li><a href="index.php?page=compose"><?php echo $composeNav; ?></a></li>
							</ul>
							<div class="tab-content">
								<div class="clearfix mt-20"></div>
								<h4 class="head-title mt-10 mb-20"><?php echo $sentPageTitle; ?></h4>
								<?php if(mysqli_num_rows($res) > 0) { ?>
									<table id="sentMsg" class="display" cellspacing="0" width="100%">
										<thead>
											<tr>
												<th></th>
												<th><?php echo $subjectTh; ?></th>
												<th><?php echo $messageTh; ?></th>
												<th class="text-center"><?php echo $sentToTxt; ?></th>
												<th class="text-center"><?php echo $dateTh; ?></th>
												<th><?php echo $orderByTh; ?></th>
											</tr>
										</thead>

										<tbody>
											<?php
												while ($row = mysqli_fetch_assoc($res)) {
													if ($row['toRead'] == '0') {
														$readIcon = '<i class="fa fa-envelope text-info msg-status" data-toggle="tooltip" data-placement="top" title="'.$unreadText.'"></i>';
													} else {
														$readIcon = '<i class="fa fa-envelope-o text-success msg-status" data-toggle="tooltip" data-placement="top" title="'.$readText.'"></i>';
													}
											?>
													<tr>
														<td class="text-center"><?php echo $readIcon; ?></td>
														<td>
															<a href="index.php?page=viewSent&messageId=<?php echo $row['messageId']; ?>" class="linkMsg" data-toggle="tooltip" data-placement="right" title="<?php echo $viewMsgTooltip; ?>">
																<?php echo clean($row['messageTitle']); ?>
															</a>
														</td>
														<td><?php echo ellipsis($row['messageText'],75); ?></td>
														<td class="text-center"><?php echo clean($row['msgFrom']); ?></td>
														<td class="text-center"><?php echo shortMonthFormat($row['messageDate']); ?></td>
														<td><?php echo dbDateTimeFormat($row['messageDate']); ?></td>
													</tr>
											<?php } ?>
										</tbody>
									</table>
								<?php } else { ?>
									<div class="alertMsg default">
										<div class="msgIcon pull-left">
											<i class="fa fa-envelope"></i>
										</div>
										<?php echo $noSentMsgFound; ?>
									</div>
								<?php } ?>
							</div>
						</div>
					</div>

				</div>
			</div>
		</div>
	</div>