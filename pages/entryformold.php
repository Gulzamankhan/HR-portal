<?php


$addCss = '<link rel="stylesheet" type="text/css" href="css/datetimepicker.css" />';
  $datePicker = 'true';
  $jsFile = 'mngTimecards';

include 'includes/pentryform.php';
include 'includes/header.php';
?>




<div class="container-fluid">
  <div class="container">
    <div class="row pageCont">
      <div class="col-md-12 pb-20">






        <?php
        if ($msgBox) {  echo $msgBox;  } 

        ?>
        <div class="col-md-12">



<div class="col-md-4 ">
<p><a href="#upload" data-toggle="modal" class="btn btn-info btn-xs"><i class="fa fa-upload"></i><span>Upload Client Approved Timesheet</span></a></p>

 </div>
 <div class="col-md-4 ">
</div>
  <div class="col-md-4" style=" text-align: -webkit-center;font-size: 15px;font-weight: 500;">
 <label >Enter Monthly Work Hours(Eg: 8)</label>
            </div>

 <div class="col-md-12">
         <div class="month"> 
  <ul>

    <li style="text-align: center;">
    <?php echo "$postyear-$postmonth" ?>
    </li>
  </ul>
</div>
<ul class="weekdays">
  <li>Sun</li>
  <li>Mon</li>
  <li>Tue</li>
  <li>Wed</li>
  <li>Thu</li>
  <li>Fri</li>
  <li>Sat</li>
</ul>
          <form action="" method="post" class="mt-20">
            <div class="row" class="days" >
              <input name="monthn" id="monthn" value="<?php echo $postmonth;?>" class="hidden"></input>
              <input name="yearn" id="yearn" value="<?php echo $postyear;?>" class="hidden"></input>
              <input name="monthNum" id="monthNum" value="<?php echo $monthNum;?>" class="hidden"></input>  
<?php 

$firstday = date('D', strtotime($query_date));

if($firstday == "Sat")
{
    echo '<div class="col-md-1 col-xs-2"> </div>';
    echo '<div class="col-md-1 col-xs-2"> </div>';
    echo '<div class="col-md-1 col-xs-2"> </div>';
    echo '<div class="col-md-1 col-xs-2"> </div>';
    echo '<div class="col-md-1 col-xs-2"> </div>';
    echo '<div class="col-md-1 col-xs-2"> </div>';
}  

if($firstday == "Fri")
{
    echo '<div class="col-md-1 col-xs-2"> </div>';
    echo '<div class="col-md-1 col-xs-2"> </div>';
    echo '<div class="col-md-1 col-xs-2"> </div>';
    echo '<div class="col-md-1 col-xs-2"> </div>';
    echo '<div class="col-md-1 col-xs-2"> </div>';
}  
if($firstday == "Thu")
{
    echo '<div class="col-md-1 col-xs-2"> </div>';
    echo '<div class="col-md-1 col-xs-2"> </div>';
    echo '<div class="col-md-1 col-xs-2"> </div>';
    echo '<div class="col-md-1 col-xs-2"> </div>';
}  
if($firstday == "Wed")
{
    echo '<div class="col-md-1 col-xs-2"> </div>';
    echo '<div class="col-md-1 col-xs-2"> </div>';
    echo '<div class="col-md-1 col-xs-2"> </div>';
}  
if($firstday == "Tue")
{
    echo '<div class="col-md-1 col-xs-2"> </div>';
    echo '<div class="col-md-1 col-xs-2"> </div>';
}  
if($firstday == "Mon")
{
    echo '<div class="col-md-1 col-xs-2"> </div>';
}  


 ?>
              




        <input type="hidden" name="userId" id="editSchedText" value="<?php
        echo $tz_userId;
?>" />
              <div class="col-md-1 col-xs-2" style="text-align: -webkit-center;">
                <label for="day1"> 1</label>
                 <input type="text" class="form-control" name="day1" id="day1" value=" <?php  if($daynum1 != "") { echo $daynum1; } ?>" />
</textarea>
              </div>
              <div class="col-md-1 col-xs-2" style="text-align: -webkit-center;">
                <label for="day2"> 2</label>
               <input type="text" class="form-control" name="day2" id="day2" value=" <?php if ($daynum2 != "") { echo $daynum2; } ?>" />
              </div>
              <div class="col-md-1 col-xs-2" style="text-align: -webkit-center;">
                <label for="day3"> 3</label>
                <input type="text" class="form-control" name="day3" id="day3" value=" <?php if ($daynum3 != "") { echo $daynum3; } ?>" />
              </div>
              <div class="col-md-1 col-xs-2" style="text-align: -webkit-center;">
                <label for="day4"> 4</label>
                <input type="text" class="form-control" name="day4" id="day4" value=" <?php if ($daynum4 != "") { echo $daynum4; } ?>" />
              </div>
              <div class="col-md-1 col-xs-2" style="text-align: -webkit-center;">
                <label for="day5"> 5</label>
                <input type="text" class="form-control" name="day5" id="day5" value=" <?php if ($daynum5 != "") { echo $daynum5; } ?>" />
              </div>
              <div class="col-md-1 col-xs-2" style="text-align: -webkit-center;">
                <label for="day6"> 6</label>
               <input type="text" class="form-control" name="day6" id="day6" value=" <?php if ($daynum6 != "") { echo $daynum6; } ?>" />
              </div>
              <div class="col-md-1 col-xs-2" style="text-align: -webkit-center;">
                <label for="day7"> 7</label>
                <input type="text" class="form-control" name="day7" id="day7" value=" <?php if ($daynum7 != "") { echo $daynum7; } ?>" />
              </div>
              <div class="col-md-1 col-xs-2" style="text-align: -webkit-center;">
                <label for="day8"> 8</label>
               <input type="text" class="form-control" name="day8" id="day8" value=" <?php if ($daynum8 != "") { echo $daynum8; } ?>" />
              </div>
              <div class="col-md-1 col-xs-2" style="text-align: -webkit-center;">
                <label for="day9"> 9</label>
               <input type="text" class="form-control" name="day9" id="day9" value=" <?php if ($daynum9 != "") { echo $daynum9; } ?>" />
              </div>
              <div class="col-md-1 col-xs-2" style="text-align: -webkit-center;">
                <label for="day10"> 10</label>
                <input type="text" class="form-control" name="day10" id="day10" value=" <?php if ($daynum10 != "") { echo $daynum10; } ?>" />
              </div>
              <div class="col-md-1 col-xs-2" style="text-align: -webkit-center;">
                <label for="day11"> 11</label>
                <input type="text" class="form-control" name="day11" id="day11" value=" <?php if ($daynum11 != "") { echo $daynum11; } ?>" />
              </div>
              <div class="col-md-1 col-xs-2" style="text-align: -webkit-center;">
                <label for="day12"> 12</label>
                <input type="text" class="form-control" name="day12" id="day12" value=" <?php if ($daynum12 != "") { echo $daynum12; } ?>" />
              </div>
              <div class="col-md-1 col-xs-2" style="text-align: -webkit-center;">
                <label for="day13"> 13</label>
               <input type="text" class="form-control" name="day13" id="day13" value=" <?php if ($daynum13 != "") { echo $daynum13; } ?>" />
              </div>
              <div class="col-md-1 col-xs-2" style="text-align: -webkit-center;">
                <label for="day14"> 14</label>
                <input type="text" class="form-control" name="day14" id="day14" value=" <?php if ($daynum14 != "") { echo $daynum14; } ?>" />
              </div>
              <div class="col-md-1 col-xs-2" style="text-align: -webkit-center;">
                <label for="day15"> 15</label>
               <input type="text" class="form-control" name="day15" id="day15" value=" <?php if ($daynum15 != "") { echo $daynum15; } ?>" />
              </div>
              <div class="col-md-1 col-xs-2" style="text-align: -webkit-center;">
                <label for="day16"> 16</label>
               <input type="text" class="form-control" name="day16" id="day16" value=" <?php if ($daynum16 != "") { echo $daynum16; } ?>" />
              </div>
              <div class="col-md-1 col-xs-2" style="text-align: -webkit-center;">
                <label for="day17"> 17</label>
                <input type="text" class="form-control" name="day17" id="day17" value=" <?php if ($daynum17 != "") { echo $daynum17; } ?>" />
              </div>
              <div class="col-md-1 col-xs-2" style="text-align: -webkit-center;">
                <label for="day18"> 18</label>
               <input type="text" class="form-control" name="day18" id="day18" value=" <?php if ($daynum18 != "") { echo $daynum18; } ?>" />
              </div>
              <div class="col-md-1 col-xs-2" style="text-align: -webkit-center;">
                <label for="day19"> 19</label>
                <input type="text" class="form-control" name="day19" id="day19" value=" <?php if ($daynum19 != "") { echo $daynum19; } ?>" />
              </div>
              <div class="col-md-1 col-xs-2" style="text-align: -webkit-center;">
                <label for="day20"> 20</label>
                <input type="text" class="form-control" name="day20" id="day20" value=" <?php if ($daynum20 != "") { echo $daynum20; } ?>" />
              </div>
              <div class="col-md-1 col-xs-2" style="text-align: -webkit-center;">
                <label for="day21"> 21</label>
               <input type="text" class="form-control" name="day21" id="day21" value=" <?php if ($daynum21 != "") { echo $daynum21; } ?>" />
              </div>
              <div class="col-md-1 col-xs-2" style="text-align: -webkit-center;">
                <label for="day22"> 22</label>
               <input type="text" class="form-control" name="day22" id="day22" value=" <?php if ($daynum22 != "") { echo $daynum22; } ?>" />
              </div>
              <div class="col-md-1 col-xs-2" style="text-align: -webkit-center;">
                <label for="day23"> 23</label>
               <input type="text" class="form-control" name="day23" id="day23" value=" <?php if ($daynum23 != "") { echo $daynum23; } ?>" />
              </div>
              <div class="col-md-1 col-xs-2" style="text-align: -webkit-center;">
                <label for="day24"> 24</label>
                <input type="text" class="form-control" name="day24" id="day24" value=" <?php if ($daynum24 != "") { echo $daynum24; } ?>" />
              </div>
              <div class="col-md-1 col-xs-2" style="text-align: -webkit-center;">
                <label for="day25"> 25</label>
                <input type="text" class="form-control" name="day25" id="day25" value=" <?php if ($daynum25 != "") { echo $daynum25; } ?>" />
              </div>
              <div class="col-md-1 col-xs-2" style="text-align: -webkit-center;">
                <label for="day26"> 26</label>
               <input type="text" class="form-control" name="day26" id="day26" value=" <?php if ($daynum26 != "") { echo $daynum26; } ?>" />
              </div>
              <div class="col-md-1 col-xs-2" style="text-align: -webkit-center;">
                <label for="day27"> 27</label>
                <input type="text" class="form-control" name="day27" id="day27" value=" <?php if ($daynum27 != "") { echo $daynum27; } ?>" />
              </div>
              <div class="col-md-1 col-xs-2" style="text-align: -webkit-center;">
                <label for="day28"> 28</label>
               <input type="text" class="form-control" name="day28" id="day28" value=" <?php if ($daynum28 != "") { echo $daynum28; } ?>" />
              </div>



<?php
        $totaldays = date('t', strtotime($monthpost));

        
        if ($totaldays == "30") {
            
            $hide31 = "hidden";
            
        }
        
        if ($totaldays == "28") {
            
            $hide28 = "hidden";
            
        }
        
?>

              <div class="col-md-1 col-xs-2 <?php  echo $hide28; ?>" style="text-align: -webkit-center;">
                <label for="day29"> 29</label>
               <input type="text" class="form-control" name="day29" id="day29" value=" <?php if ($daynum29 != "") { echo $daynum29; } ?>" />
              </div>


              <div class="col-md-1 col-xs-2 <?php echo $hide28;?>" style="text-align: -webkit-center;">
                <label for="day30"> 30</label>
                <input type="text" class="form-control" name="day30" id="day30" value=" <?php if ($daynum30 != "") { echo $daynum30; } ?>" />
              </div>


              <div class="col-md-1 col-xs-2  <?php  echo $hide28;?><?php  echo $hide31;?>"  style="text-align: -webkit-center;">
                <label for="day31"> 31</label>
                <input type="text" class="form-control" name="day31" id="day31" value=" <?php if ($daynum31 != "") { echo $daynum31; } ?>" />
              </div>



            </div>
            <div class="row" style="margin-top: 15px; text-align: right;">
              <div class="col-md-8">
                <button type="input" name="submit" value="addmonthlyshift" class="btn btn-success btn-sm btn-icon" style="padding: .8em 1.5em; font-size: 1em;"><i class="fa fa-check-square-o"></i> Save Hours</button>
              </div>
            </div>
          </form>
        </div>
         <!-- This Month Div End -->
        



        <div class="modal fade" id="upload" tabindex="-1" role="dialog" aria-hidden="true">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"><i class="fa fa-times"></i></span></button>
                <h4 class="modal-title">Upload Client Approved Timesheet</h4>
              </div>
              <form action="" method="post" enctype="multipart/form-data">
                <div class="modal-body">
                <h5 style="font-weight: 600;">NOTE: Before Uploading Document Make sure you saved the data entered in Work Hour Entry Form</h5>
                  <p> <small> <?php
        echo $uplFileTypesQuip;
?>: <?php
        echo $fileTypesAllowed;
?><br />
                    <?php
        echo $maxFileSizeQuip;
?>: <?php
        echo $maxUpload;
?> <?php
        echo $mbText;
?>. </small>
                    <br />  Use Ctrl to select multiple files </p>
                  <div class="form-group">
                    <label for="file"><?php
        echo $selFileField;
?></label>
                   <input name="monthn" id="monthn" value="<?php echo $postmonth;?>" class="hidden"></input>
              <input name="yearn" id="yearn" value="<?php echo $postyear;?>" class="hidden"></input>
                    <input type="file" id="file" name="documents[]"  multiple="multiple" />
                  </div>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-default btn-sm btn-icon" data-dismiss="modal"><i class="fa fa-times-circle-o"></i> <?php
        echo $cancelBtn;
?></button>
                  <button type="input" name="submit" value="uploadFile" class="btn btn-success btn-sm btn-icon"><i class="fa fa-check-square-o"></i> <?php
        echo $uplDocumentBtn;
?></button>
                </div>
              </form>
            </div>
          </div>
        </div>
          </div>
        </div>

        

      <!-- First Four Div -->
    </div>
  </div>
</div>

<script type="text/javascript">
function minmax(value, min, max) 
{
if(parseInt(value) > max) 
{
     
      var msg = "Work timing cannot be more than 24 hours";
        alert(msg);
           return 0; 
      }
    else return value;
}

function validatepaste(e) {
    var pastedata = e.clipboardData.getData('text/plain');
    if (isNaN(pastedata)) {
        e.preventDefault();
        console.log("PASTE FAIL!");
        console.log(pastedata);
        return false;
    } else {
            console.log("PASTE!");
            console.log(pastedata); 
    }
}

function validate(e) {
    var keycode = (e.which) ? e.which : e.keyCode;
    var phn = document.getElementById('textarea');
    if ((keycode < 48 || keycode > 57) && keycode !== 13) {
        e.preventDefault();
        console.log("FAIL");
        return false;
    } else {
        console.log("OK!");
    }
}


function check_length(my_form)
{
   maxLen = 1000; 
   if (my_form.my_text.value.length >= maxLen)
    {
        var msg = "You have reached your maximum limit of characters allowed";
        alert(msg);

    }

}

</script>
</div>
