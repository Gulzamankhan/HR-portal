<?php
	
	
	$query = "SELECT
				privatemessages.*,
				UNIX_TIMESTAMP(privatemessages.messageDate) AS orderDate,
				CONCAT(users.userFirst,' ',users.userLast) AS sentFrom
			FROM
				privatemessages
				LEFT JOIN users ON privatemessages.fromId = users.userId
			WHERE
				privatemessages.toId = ".$tz_userId." AND
				privatemessages.toRead = 0 AND
				privatemessages.toArchived = 0 AND
				privatemessages.toDeleted = 0
			ORDER BY orderDate DESC";
	$results = mysqli_query($mysqli, $query) or die('-2' . mysqli_error());

	include 'includes/header.php';
?>

<div class="container-fluid">
  <div class="container">
    <div class="row pageCont">
      <div class="col-md-12 pb-20">

        <?php
						if ($enableSched = '1') {
							if ((checkArray('MNGSCHED', $auths)) || $tz_userId > '1') {
								if ($msgBox) { echo $msgBox; }
					?>





         <div class="col-md-12">
         <?php $monthcur = date('F'); ?>
							<div class="row">
								<div class="col-md-3">
									</div>

							<div class="col-md-6">
									<form action="index.php?page=entryform" method="post">
											<h4 style="background: grey;text-align: center; color: white; font-size: 20px; font-weight: 500;"><?php echo $dashuserh4 ?></h4>

											




														<div class="form-group col-md-12" style="text-align: center;">


															<select class="form-control" name="fromMonth">

															<?php 



															$timeNow = time();

															$curmonthnum = date('n'); 
															if($curmonthnum== 3)
															 {
															$timePrevMonth = $timeNow - (86400 * 28);
															$timePrevMonth2 = $timeNow - (86400 * 59);
															 }
															 else{

															 $timePrevMonth = $timeNow - (86400 * 31);
															$timePrevMonth2 = $timeNow - (86400 * 61);

															 }


																function incrementDate($startDate, $monthIncrement = 0) {

															    $startingTimeStamp = $startDate->getTimestamp();
															    $monthString = date('Y-m', $startingTimeStamp);
															    $safeDateString = "first day of $monthString";
															    $incrementedDateString = "$safeDateString $monthIncrement month";
															    $newTimeStamp = strtotime($incrementedDateString);
															    $newDate = DateTime::createFromFormat('U', $newTimeStamp);
															    return $newDate;
															}

															$currentDate = new DateTime();
															$oneMonthAgo = incrementDate($currentDate, -1);
															$twoMonthsAgo = incrementDate($currentDate, -2);


															echo '
															        <option value="' .$twoMonthsAgo->format('F Y') . '">' . $twoMonthsAgo->format('F Y') . '</option>
															        <option value="' . $oneMonthAgo->format('F Y') . '">' . $oneMonthAgo->format('F Y') . '</option>
															        <option value="' . $currentDate->format('F Y') . '" selected/>' . $currentDate->format('F Y') . '</option>
															        

															'; ?>
															
															        </select>

															   <select class="form-control hidden" name="fromMonthall">
															<?php

															$curmonth = date("F");



															for($i = 1 ; $i <= 12; $i++)
															{
															$allmonth = date("F",mktime(0,0,0,$i,1,date("Y")))
															?>
															<option value="<?php 
															       echo $i; ?>"
															<?php 
															       if($curmonth==$allmonth)
															       {
															       echo 'selected';
															       }
															       ?>" 
															       >
															        <?php
															        echo date("F",mktime(0,0,0,$i,1,date("Y")));

															        }
															        ?>
															        </option>

															        </select>

														</div>

														<div class="form-group col-md-6 hidden">
															<label for="recFromYear"><?php echo Year; ?></label>

															<select class="form-control" name="fromyear">

															<?php 



															$timeNow = time();
															$timePrevMonth = $timeNow - (86400 * 32);
															$timePrevMonth2 = $timeNow - (86400 * 63);

															echo '
															        <option value="' . date("Y", $timePrevMonth) . '">' . date("Y", $timePrevMonth) . '</option>
															        <option value="' . date("Y", $timeNow) . '">' . date("Y", $timeNow) . '</option>
															        

															'; ?>
															
															        </select>


															<?php 

															$already_selected_value = date('Y') ;
															$earliest_year = 2016;

															echo '<select class="form-control hidden" name="fromyearall">';
															foreach (range(date('Y'), $earliest_year) as $x) {
															    echo '<option value="'.$x.'"'.($x === $already_selected_value ? ' selected="selected"' : '').'>'.$x.'</option>';
															}
															echo '</select>'; ?>
														</div>
														<div class="col-md-4">
									</div>
									<div class="col-md-4">
									<button type="input" name="submit" value="runRpt" class="btn btn-success btn-sm btn-icon-alt"><?php echo $runEntryBtn; ?> <i class="fa fa-long-arrow-right"></i></button>

									</div>
									<div class="col-md-4">
									</div>

									</form>
								</div>
															
							</div>
       </div>


       
        <?php } else { ?>
         <div class="col-md-12">
         <?php $monthcur = date('F'); ?>
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

															$curmonth = date("F");

															for($i = 1 ; $i <= 12; $i++)
															{
															$allmonth = date("F",mktime(0,0,0,$i,1,date("Y")))
															?>
															<option value="<?php 
															       echo $i; ?>"
															<?php 
															       if($curmonth==$allmonth)
															       {
															       echo 'selected';
															       }
															       ?>" 
															       >
															        <?php
															        echo date("F",mktime(0,0,0,$i,1,date("Y")));

															        }
															        ?>
															        </option>

														
														

													</select>

														</div>

														<div class="form-group col-md-6">
															<label for="recFromYear"><?php echo Year; ?></label>
															<?php 

															$already_selected_value = date('Y') ;
															$earliest_year = 2016;

															echo '<select class="form-control" name="fromyear">';
															foreach (range(date('Y'), $earliest_year) as $x) {
															    echo '<option value="'.$x.'"'.($x === $already_selected_value ? ' selected="selected"' : '').'>'.$x.'</option>';
															}
															echo '</select>'; ?>
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
       </div>
        <?php
							}
						} else {
					?>
        <div class="alertMsg danger">
          <div class="msgIcon pull-left"> <i class="fa fa-ban"></i> </div>
          <?php echo $accessErrorMsg; ?> </div>
        <?php } ?>

        <!-- Column1 end -->
      </div>

      <!-- First Four Div -->
    </div>
  </div>
</div>

 <script type="text/javascript">
function minmax(value, min, max) 
{
if(parseInt(value) > max) 
        return 24; 
    else return value;
}
</script>
</div>
