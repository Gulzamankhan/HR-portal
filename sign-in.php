<?php
		if(!isset($_SESSION)) session_start();

		include('config.php');

		include ('includes/settings.php');
		$set = mysqli_fetch_assoc($setRes);

		include('includes/functions.php');

		include('includes/sessions.php');

		if ((isset($_SESSION['tz']['userId'])) && ($_SESSION['tz']['userId'] != '')) {
			header('Location: index.php');
		}

		$msgBox = '';
		$installUrl	= $set['installUrl'];
		$siteName	= $set['siteName'];
		$siteEmail	= $set['siteEmail'];

		if (isset($_POST['submit']) && $_POST['submit'] == 'signIn') {
			if($_POST['emailAddy'] == '') {
				$msgBox = alertBox($accEmailReq, "<i class='fa fa-times-circle'></i>", "danger");
			} else if($_POST['password'] == '') {
				$msgBox = alertBox($accPassReq, "<i class='fa fa-times-circle'></i>", "danger");
			} else {
				$usrEmail = htmlspecialchars($_POST['emailAddy']);

				$check = "SELECT userId, userFirst, userLast, isActive FROM users WHERE userEmail = '".$usrEmail."'";
				$res = mysqli_query($mysqli, $check) or die('-1' . mysqli_error());
				$row = mysqli_fetch_assoc($res);
				$count = mysqli_num_rows($res);

				if ($count > 0) {
					if ($row['isActive'] == '1') {
						$userEmail = htmlspecialchars($_POST['emailAddy']);
						$password = encodeIt($_POST['password']);

						if($stmt = $mysqli -> prepare("
												SELECT
													userId,
													userEmail,
													userFirst,
													userLast,
													location,
													superUser,
													isAdmin
												FROM
													users
												WHERE
													userEmail = ?
													AND password = ?
						")) {
							$stmt -> bind_param("ss",
												$userEmail,
												$password
							);
							$stmt -> execute();
							$stmt -> bind_result(
										$userId,
										$userEmail,
										$userFirst,
										$userLast,
										$location,
										$superUser,
										$isAdmin
							);
							$stmt -> fetch();
							$stmt -> close();

							if (!empty($userId)) {
								if(!isset($_SESSION))session_start();
								$_SESSION['tz']['userId']		= $userId;
								$_SESSION['tz']['userEmail'] 	= $userEmail;
								$_SESSION['tz']['userFirst']	= $userFirst;
								$_SESSION['tz']['userLast']		= $userLast;
								$_SESSION['tz']['location']		= $location;
								$_SESSION['tz']['superUser']	= $superUser;
								$_SESSION['tz']['isAdmin']		= $isAdmin;

								$activityType = '1';
								$tz_uid = $userId;
								$activityTitle = $userFirst.' '.$userLast.' '.$accSignInAct;
								updateActivity($tz_uid,$activityType,$activityTitle);

								$sqlStmt = $mysqli->prepare("UPDATE users SET lastVisited = NOW() WHERE userId = ?");
								$sqlStmt->bind_param('s', $userId);
								$sqlStmt->execute();
								$sqlStmt->close();

								header('Location: index.php');
							} else {
								$activityType = '0';
								$tz_uid = '0';
								$activityTitle = $accSignInErrAct;
								updateActivity($tz_uid,$activityType,$activityTitle);

								$msgBox = alertBox($accSignInErrMsg, "<i class='fa fa-warning'></i>", "warning");
							}
						}
					} else {
						$activityType = '0';
						$tz_uid = $row['userId'];
						$activityTitle = $row['userFirst'].' '.$row['userLast'].' '.$signInUsrErrAct;
						updateActivity($tz_uid,$activityType,$activityTitle);

						$msgBox = alertBox($inactAccMsg, "<i class='fa fa-warning'></i>", "warning");
					}
				} else {
					$activityType = '0';
					$tz_uid = '0';
					$activityTitle = $noAccSignInErrAct;
					updateActivity($tz_uid,$activityType,$activityTitle);

					$msgBox = alertBox($noAccSignInErrMsg, "<i class='fa fa-times-circle'></i>", "danger");
				}
			}
		}

		if (isset($_POST['submit']) && $_POST['submit'] == 'resetPass') {
		
			if ($_POST['accountEmail'] == "") {
				$msgBox = alertBox($accEmailReq, "<i class='fa fa-times-circle'></i>", "danger");
			} else {
				$usrEmail = htmlspecialchars($_POST['accountEmail']);

				$query = "SELECT userEmail FROM users WHERE userEmail = ?";
				$stmt = $mysqli->prepare($query);
				$stmt->bind_param("s",$usrEmail);
				$stmt->execute();
				$stmt->bind_result($emailUser);
				$stmt->store_result();
				$numrows = $stmt->num_rows();

				if ($numrows == 1) {
					$randomPassword = uniqid(rand());

					$emailPassword = substr($randomPassword, 0, 8);

					$newpassword = encodeIt($emailPassword);

					$updatesql = "UPDATE users SET password = ? WHERE userEmail = ?";
					$update = $mysqli->prepare($updatesql);
					$update->bind_param("ss",
											$newpassword,
											$usrEmail
										);
					$update->execute();

					$qry = "SELECT userId, userFirst, userLast, isAdmin FROM users WHERE userEmail = '".$usrEmail."'";
					$results = mysqli_query($mysqli, $qry) or die('-2' . mysqli_error());
					$row = mysqli_fetch_assoc($results);
					$theUser = $row['userId'];
					$isAdmin = $row['isAdmin'];
					$userName = $row['userFirst'].' '.$row['userLast'];

					if ($isAdmin == '1') {
						$activityType = '3';
						$activityTitle = $userName.' '.$admPassResetAct;
						updateActivity($theUser,$activityType,$activityTitle);
					} else {
						$activityType = '3';
						$activityTitle = $userName.' '.$usrPassResetAct;
						updateActivity($theUser,$activityType,$activityTitle);
					}

					$subject = $siteName.' '.$resetPassEmailSub;

					$message = '<html><body>';
					$message .= '<h3>'.$subject.'</h3>';
					$message .= '<p>'.$resetPassEmail1.'</p>';
					$message .= '<hr>';
					$message .= '<p>'.$emailPassword.'</p>';
					$message .= '<hr>';
					$message .= '<p>'.$resetPassEmail2.'</p>';
					$message .= '<p>'.$resetPassEmail3.' '.$installUrl.'sign-in.php</p>';
					$message .= '<p>'.$emailTankYouTxt.'<br>'.$siteName.'</p>';
					$message .= '</body></html>';

					$headers = "From: ".$siteName." <".$siteEmail.">\r\n";
					$headers .= "Reply-To: ".$siteEmail."\r\n";
					$headers .= "MIME-Version: 1.0\r\n";
					$headers .= "Content-Type: text/html; charset=UTF-8\r\n";

					mail($usrEmail, $subject, $message, $headers);

					$msgBox = alertBox($resetPassMsg1, "<i class='fa fa-check-square'></i>", "success");
					$stmt->close();
				} else {
					$activityType = '1';
					$tz_uid = '0';
					$activityTitle = $resetPassMsgAct;
					updateActivity($tz_uid,$activityType,$activityTitle);

					$msgBox = alertBox($resetPassMsg2, "<i class='fa fa-times-circle'></i>", "danger");
				}
			}
		}

?>
		<!DOCTYPE html>
		<html lang="en">
		<head>
			<meta charset="utf-8">
			<meta http-equiv="X-UA-Compatible" content="IE=edge">
			<meta name="viewport" content="width=device-width, initial-scale=1">
			<meta name="description" content="<?php echo $metaDesc; ?>">
			<meta name="author" content="<?php echo $metaAuthor; ?>">

			<title><?php echo $set['siteName']; ?> &middot; <?php echo $signInPageTitle; ?></title>

			<link href="css/font-awesome.css" rel="stylesheet" type="text/css" />
			<link href="css/bootstrap.css" rel="stylesheet">
			<link href="css/style.css" rel="stylesheet">

			<!--[if lt IE 9]>
				<script src="js/html5shiv.min.js"></script>
				<script src="js/respond.min.js"></script>
			<![endif]-->
		</head>

		<body>
			<div class="container signin">
				<div class="row">
					<div class="col-md-8 col-md-offset-2">
						<p class="text-center mt-0"><a href="sign-in.php"><img src="images/signin.png" alt="<?php echo $set['siteName'].' '.$signInPageTitle; ?>" /></a></p>
						<?php if ($msgBox) { echo $msgBox; } ?>
					</div>
				</div>

				<div class="row">
					<div class="col-md-4 col-md-offset-4">
						<form action="" method="post" class="signin-form">
							<div class="form-group" data-toggle="tooltip" data-placement="top" title="<?php echo $accEmailField; ?>">
								<input type="email" class="form-control" name="emailAddy" required="required" placeholder="<?php echo $accEmailField; ?>">
							</div>
							<div class="form-group" data-toggle="tooltip" data-placement="top" title="<?php echo $accPassField; ?>">
								<input type="password" class="form-control" name="password" required="required" placeholder="<?php echo $accPassField; ?>">
							</div>
							<button type="submit" name="submit" value="signIn" class="btn btn-danger btn-lg btn-block btn-icon-alt mt-20"><?php echo $signInBtnText; ?> <i class="fa fa-long-arrow-right"></i></button>
						</form>
						<p class="text-center"><small><a data-toggle="modal" href="#resetPassword"><?php echo $lostPassText; ?></a></small></p>

						<div class="modal fade" id="resetPassword" tabindex="-1" role="dialog" aria-hidden="true">
							<div class="modal-dialog">
								<div class="modal-content">
									<div class="modal-header">
										<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"><i class="fa fa-times"></i></span></button>
										<h4 class="modal-title"><?php echo $resetPassH4; ?></h3>
									</div>
									<form action="" method="post">
										<div class="modal-body">
											<div class="form-group">
												<label for="accountEmail"><?php echo $accEmailField; ?></label>
												<input type="email" class="form-control" required="required" name="accountEmail" value="" />
												<span class="help-block"><?php echo $resetPassHelp; ?></span>
											</div>
										</div>
										<div class="modal-footer">
											<button type="button" class="btn btn-default btn-sm btn-icon" data-dismiss="modal"><i class="fa fa-times"></i> <?php echo $closeBtn; ?></button>
											<button type="input" name="submit" value="resetPass" class="btn btn-success btn-sm btn-icon"><i class="fa fa-check-square-o"></i> <?php echo $resetPassBtnText; ?></button>
										</div>
									</form>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>

			<script type="text/javascript" src="js/jquery.min.js"></script>
			<script type="text/javascript" src="js/bootstrap.min.js"></script>
			<script type="text/javascript">
				jQuery(document).ready(function($) {
					$("[data-toggle='tooltip']").tooltip();

					$('.msgClose').click(function(e){
						e.preventDefault();
						$(this).closest('.alertMsg').fadeOut("slow", function() {
							$(this).addClass('hidden');
						});
					});

					var placehold = {
						init: function(){
							$('input[type="text"], input[type="email"], input[type="password"], textarea').each(placehold.replace);
						},
						replace: function(){
							var txt = $(this).data('placeholder');
							if (txt) {
								if ($(this).val()=='') {
									$(this).val(txt);
								}
								$(this).focus(function(){
									if ($(this).val() == txt){
										$(this).val('');
									}
								}).blur(function(){
									if ($(this).val() == ''){
										$(this).val(txt);
									}
								});
							}
						}
					}
					placehold.init();

					$("form :input[required='required']").blur(function() {
						if (!$(this).val()) {
							$(this).addClass('hasError');
						} else {
							if ($(this).hasClass('hasError')) {
								$(this).removeClass('hasError');
							}
						}
					});
					$("form :input[required='required']").change(function() {
						if ($(this).hasClass('hasError')) {
							$(this).removeClass('hasError');
						}
					});
				});
			</script>
		</body>
		</html>