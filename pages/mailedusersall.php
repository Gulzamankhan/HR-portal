<?php
	
	$actIp = $_SERVER['REMOTE_ADDR'];

	
	$addCss = '<link href="css/chosen.css" rel="stylesheet">';
	$chosen = 'true';

	include 'includes/header.php';
?>
	<div class="container-fluid">
		<div class="container">
			<div class="row pageCont">
				<div class="col-md-12 pb-20">
					

<div class="titleRight">
						<div class="title">
							<h2>Mailed Users List</h2>
						</div>
					</div>


<style type="text/css">

.selector span:after {
    content: ",";
}
.selector span:last-child:after {
    content: "";
}

</style>



<?php


	if (!empty($_POST['userId']) && is_array($_POST['userId']) && !in_array('all',$_POST['userId'])) {
		$uids = array();
		foreach ($_POST['userId'] as $userId) {
		  $uids[] = $mysqli->real_escape_string($userId);
		}
		$userIds = '"'.implode('", "', $uids).'"';
		$where[] = 'schedule.userId IN ('.$userIds.')';

		$userIdss = ''.implode('", "', $uids).'';
		$where[] = 'schedule.userId IN ('.$userIdss.')';
	}

	

?>


<?php 

if(isset($_POST['submit'])){
if(!empty($_POST['num'])){


$array = array();
foreach($_POST['num']  as $result){ 
    $array[] = $result;  
} 
echo implode($array, ",");


$to =  '';
$subject = "Reminder";
$message = '
<p><strong>HI,&nbsp;</strong></p>

<p><strong>You have received this email because either you have not entered the&nbsp;</strong><strong><u>Working Hours</u></strong><strong>&nbsp;or you have not&nbsp;</strong><strong><u>uploaded the approved Timesheet</u></strong><strong>.</strong></p>

<p><strong>If you have any questions, please feel free to contact us at&nbsp;</strong><a href="tel:404%20410%201441%20x%20103" target="_blank"><strong>404 410 1441 x 103</strong></a></p>

<p>&nbsp;</p>

<p><strong>Note: Please do not respond to this message. It comes from an unattended mailbox.</strong></p>';
$headers = 'From: accounts@syrainfotek.com' . "\r\n" ;
$headers .= 'Reply-To: accounts@syrainfotek.com' . "\r\n";
$headers .= "Bcc:  " .implode(',', $array) ."\r\n";
$headers .= "Content-Type: text/html; charset=UTF-8\r\n";

mail($to, $subject, $message, $headers);



	  }
	else
	{
		echo "No Users Selected";
	}
}
?>


  






				</div>
			</div>
		</div>
	</div>