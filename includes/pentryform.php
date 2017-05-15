<?php

 $actIp = $_SERVER['REMOTE_ADDR'];



if ($_POST['fromMonth'] != '') {

$monthpost  = $_POST['fromMonth'];
$monthNum  = date('m', strtotime($monthpost));
$postyear = date('Y', strtotime($monthpost));
}
else{
    $monthNum  =  htmlspecialchars($_POST['monthNum']);
     $postyear = htmlspecialchars($_POST['yearn']);
}
if ($monthNum != '') {
$postmonth = date('F', mktime(0, 0, 0, $monthNum, 10));
}
else{
  $postmonth = htmlspecialchars($_POST['monthn']); 
}


$query_date = "$postyear-$monthNum-01";

    if (isset($_POST['submit']) && $_POST['submit'] == 'addmonthlyshift') {
        
        if($_POST['userId'] == '') {
            $msgBox = alertBox($selectUserReq, "<i class='fa fa-times-circle'></i>", "danger");
        }else {
            $userId = htmlspecialchars($_POST['userId']);
            $schedHours = htmlspecialchars($_POST['schedHours']);
            $schedDay = htmlspecialchars($_POST['schedDay']);
            $postDate = date('Y-m-d');
            $schedColor = '#e9e6e1';
            $monthn = htmlspecialchars($_POST['monthn']);
            $yearn = htmlspecialchars($_POST['yearn']);
            $day1 = htmlspecialchars($_POST['day1']);
            $day2 = htmlspecialchars($_POST['day2']);
            $day3 = htmlspecialchars($_POST['day3']);
            $day4 = htmlspecialchars($_POST['day4']);
            $day5 = htmlspecialchars($_POST['day5']);
            $day6 = htmlspecialchars($_POST['day6']);
            $day7 = htmlspecialchars($_POST['day7']);
            $day8 = htmlspecialchars($_POST['day8']);
            $day9 = htmlspecialchars($_POST['day9']);
            $day10 = htmlspecialchars($_POST['day10']);
            $day11 = htmlspecialchars($_POST['day11']);
            $day12 = htmlspecialchars($_POST['day12']);
            $day13 = htmlspecialchars($_POST['day13']);
            $day14 = htmlspecialchars($_POST['day14']);
            $day15 = htmlspecialchars($_POST['day15']);
            $day16 = htmlspecialchars($_POST['day16']);
            $day17 = htmlspecialchars($_POST['day17']);
            $day18 = htmlspecialchars($_POST['day18']);
            $day19 = htmlspecialchars($_POST['day19']);
            $day20 = htmlspecialchars($_POST['day20']);
            $day21 = htmlspecialchars($_POST['day21']);
            $day22 = htmlspecialchars($_POST['day22']);
            $day23 = htmlspecialchars($_POST['day23']);
            $day24 = htmlspecialchars($_POST['day24']);
            $day25 = htmlspecialchars($_POST['day25']);
            $day26 = htmlspecialchars($_POST['day26']);
            $day27 = htmlspecialchars($_POST['day27']);
            $day28 = htmlspecialchars($_POST['day28']);
            $day29 = htmlspecialchars($_POST['day29']);
            $day30 = htmlspecialchars($_POST['day30']);
            $day31 = htmlspecialchars($_POST['day31']);
$totalHrs2 = htmlspecialchars($_POST['day1'] + $_POST['day2'] + $_POST['day3'] + $_POST['day4']+ $_POST['day5']+ $_POST['day6']+ $_POST['day7'] + $_POST['day8']+ $_POST['day9'] + $_POST['day10']+ $_POST['day11']+ $_POST['day12']+ $_POST['day13'] + $_POST['day14'] + $_POST['day15'] + $_POST['day16'] + $_POST['day17']  + $_POST['day18']  + $_POST['day19'] + $_POST['day20'] + $_POST['day21']+ $_POST['day22'] + $_POST['day23'] + $_POST['day24'] + $_POST['day25'] + $_POST['day26']+ $_POST['day27'] + $_POST['day28']+ $_POST['day29'] + $_POST['day30'] + $_POST['day31'] );

            $projectCode1 = htmlspecialchars($_POST['projectCode1']);
            $projectCode2 = htmlspecialchars($_POST['projectCode2']);
            $projectCode3 = htmlspecialchars($_POST['projectCode3']);
            $projectCode4 = htmlspecialchars($_POST['projectCode4']);
            $notes = htmlspecialchars($_POST['notes']);
            
function explode_time($time) { //explode time and convert into seconds
        $time = explode(':', $time);
        $time = $time[0] * 3600 + $time[1] * 60;
        return $time;
}

function second_to_hhmm($time) { //convert seconds to hh:mm
        $hour = floor($time / 3600);
        $minute = strval(floor(($time % 3600) / 60));
        if ($minute == 0) {
            $minute = "00";
        } else {
            $minute = $minute;
        }
        $time = $hour . ":" . $minute;
        return $time;
}

$time = 0;
$time_arr =  array($day1,$day2,$day3,$day4,$day5,$day6,$day7,$day8,$day9,$day10,$day11,$day12,$day13,$day14,$day15,$day16,$day17,$day18,$day19,$day20,$day21,$day22,$day23,$day24,$day25,$day26,$day27,$day28,$day29,$day30,$day31);
 foreach ($time_arr as $time_val) {
    $time +=explode_time($time_val); // this fucntion will convert all hh:mm to seconds
}

$totalHrs = second_to_hhmm($time); // this function will  convert all seconds to HH:MM.





            $usrName = "SELECT CONCAT(users.userFirst,' ',users.userLast) AS theUser FROM users WHERE userId = ".$userId;
            $usrres = mysqli_query($mysqli, $usrName) or die('-1' . mysqli_error());
            $usrsName = mysqli_fetch_assoc($usrres);
            $theUser = $usrsName['theUser'];

            

            $stmt = $mysqli->prepare("
                                INSERT INTO
                                    schedule(
                                        userId,
                                        userName,
                                        createdBy,
                                        startDate,
                                        postDate,
                                        endDate,
                                        schedTitle,
                                        schedHours,
                                        schedColor,
                                        lastUpdated,
                                        ipAddress,
                                        monthn,
                                        yearn,
                                        day1,
                                        day2,
                                        day3,
                                        day4,
                                        day5,
                                        day6,
                                        day7,
                                        day8,
                                        day9,
                                        day10,
                                        day11,
                                        day12,
                                        day13,
                                        day14,
                                        day15,
                                        day16,
                                        day17,
                                        day18,
                                        day19,
                                        day20,
                                        day21,
                                        day22,
                                        day23,
                                        day24,
                                        day25,
                                        day26,
                                        day27,
                                        day28,
                                        day29,
                                        day30,
                                        day31,
                                        totalHrs,
                                        projectCode1,
                                        projectCode2,
                                        projectCode3,
                                        projectCode4,
                                        notes
                                    ) VALUES (
                                        ?,
                                        ?,
                                        ?,
                                        ?,
                                        ?,
                                        ?,
                                        ?,
                                        ?,
                                        ?,
                                        NOW(),
                                        ?,
                                        ?,
                                        ?,
                                        ?,
                                        ?,
                                        ?,
                                        ?,
                                        ?,
                                        ?,
                                        ?,
                                        ?,
                                        ?,
                                        ?,
                                        ?,
                                        ?,
                                        ?,
                                        ?,
                                        ?,
                                        ?,
                                        ?,
                                        ?,
                                        ?,
                                        ?,
                                        ?,
                                        ?,
                                        ?,
                                        ?,
                                        ?,
                                        ?,
                                        ?,
                                        ?,
                                        ?,
                                        ?,
                                        ?,
                                        ?,
                                        ?,
                                        ?,
                                        ?,
                                        ?,
                                        ?
                                    )
            ");
            $stmt->bind_param('sssssssssssssssssssssssssssssssssssssssssssssssss',
                                $userId,
                                $theUser,
                                $tz_userId,
                                $postDate,
                                $postDate,
                                $schedDay,
                                $schedTitle,
                                $schedHours,
                                $schedColor,
                                $actIp,
                                $monthn,
                                $yearn,
                                $day1,
                                        $day2,
                                        $day3,
                                        $day4,
                                        $day5,
                                        $day6,
                                        $day7,
                                        $day8,
                                        $day9,
                                        $day10,
                                        $day11,
                                        $day12,
                                        $day13,
                                        $day14,
                                        $day15,
                                        $day16,
                                        $day17,
                                        $day18,
                                        $day19,
                                        $day20,
                                        $day21,
                                        $day22,
                                        $day23,
                                        $day24,
                                        $day25,
                                        $day26,
                                        $day27,
                                        $day28,
                                        $day29,
                                        $day30,
                                        $day31,
                                        $totalHrs,
                                        $projectCode1,
                                        $projectCode2,
                                        $projectCode3,
                                        $projectCode4,
                                        $notes
            );
            $stmt->execute();
            echo $stmt->error;
            $stmt->close();

            
            $activityType = '11';
            $activityTitle = $tz_userFull.' '.$newShiftAct.' '.$theUser;
            updateActivity($tz_userId,$activityType,$activityTitle);



            $msgBox = alertBox($newShiftMsg1." ".$totalHrs, "<i class='fa fa-check-square'></i>", "success");




            
            $_POST['schedHours'] = $_POST['schedDay'] = $_POST['day1'] = $_POST['day2'] = $_POST['day3'] = $_POST['day4'] = $_POST['day5'] = $_POST['day6'] = $_POST['day7'] = $_POST['day8'] = $_POST['day9'] = $_POST['day10'] = $_POST['day11'] = $_POST['day12'] = $_POST['day13'] = $_POST['day14'] = $_POST['day15'] = $_POST['day16'] = $_POST['day17'] = $_POST['day18'] = $_POST['day19'] = $_POST['day20'] = $_POST['day21'] = $_POST['day22'] = $_POST['day23'] = $_POST['day24'] = $_POST['day25'] = $_POST['day26'] = $_POST['day27'] = $_POST['day28'] = $_POST['day29'] = $_POST['day30'] = $_POST['day31'] ='';
        }
    }



 $maxUpload = (int)(ini_get('upload_max_filesize'));

    $userDocsPath = $set['userDocsPath'];

    $filesAllowed = $set['fileTypesAllowed'];
    $fileTypesAllowed = preg_replace('/,/', ', ', $filesAllowed);

    
    $fldr = "SELECT userFolder FROM users WHERE userId = ".$tz_userId;
    $fldrres = mysqli_query($mysqli, $fldr) or die('-1' . mysqli_error());
    $usrFldr = mysqli_fetch_assoc($fldrres);
    $userFolder = $usrFldr['userFolder'];

    
    if (isset($_POST['submit']) && $_POST['submit'] == 'uploadFile') {
        
error_reporting(0);    



if(isset($_FILES['documents']['name'])):
  define ("MAX_SIZE","2000");
  for($i=0; $i<count($_FILES['documents']['name']); $i++)  {
  $size=filesize($_FILES['documents']['tmp_name'][$i]);    
  if($size < (MAX_SIZE*100024)):    
   $path = "uploads/";

   $filename = $_FILES['documents']['name'][$i];
   $monthn = htmlspecialchars($_POST['monthn']);
   $yearn = htmlspecialchars($_POST['yearn']);

   $uploadDate = date('Y-m-d H:i:s');


$fName = clean(strip($filename));
   $newName = str_replace(' ', '-', $fName);
                $newFName = strtolower($newName);

                
                $randomHash = uniqid(rand());
                
                $randHash = substr($randomHash, 0, 8);

                $fullName = $newFName.'-'.$randHash;

                
                $fileUrl = basename($_FILES['documents']['name'][$i]);

                
                $extension = explode(".", $fileUrl);
                $extension = end($extension);

                $monthn = htmlspecialchars($_POST['monthn']);
                 $yearn = htmlspecialchars($_POST['yearn']);
                
                $newFileName = $fullName.'.'.$extension;
                $movePath = $userDocsPath.$userFolder.'/'.$newFileName;


   $size = $_FILES['documents']['size'][$i];

   list($txt, $ext) = explode(".", $name);


    if(move_uploaded_file($_FILES['documents']['tmp_name'][$i], $movePath)) :
       $fetch=$mysqli->query("INSERT INTO userdocs(userId,uploadedBy,docTitle,docUrl,uploadDate,ipAddress,monthn,yearn) VALUES('$tz_userId','$tz_userId','$filename','$newFileName','$uploadDate','$actIp','$monthn','$yearn')");
       if($fetch):
         $msgBox = alertBox('Upload is Done',"<i class='fa fa-check-square'></i>", "success");
       else :
        $msgBox = alertBox($uplDocErrMsg, "<i class='fa fa-times-circle'></i>", "danger");
       endif;
    else :
        $error = 'File moving unsuccessful';
    endif;
  else:
     $error = 'You have exceeded the size limit!';
  endif;      
  }
else:
  $error = 'File not found!';
endif;          
    }


$Monthname1 = "SELECT userdocs.monthn AS Monthname FROM userdocs WHERE userId = '".$tz_userId."' ORDER BY docId DESC LIMIT 1" ;
$Monthname2 = mysqli_query($mysqli, $Monthname1) or die('-1' . mysqli_error());
$Monthname3 = mysqli_fetch_assoc($Monthname2);
$Monthname = $Monthname3['Monthname']; 


$daysel = "SELECT 
        CONCAT(schedule.day1)  AS daynum1,
        CONCAT(schedule.day2)  AS daynum2,
        CONCAT(schedule.day3)  AS daynum3,
        CONCAT(schedule.day4)  AS daynum4,
        CONCAT(schedule.day5)  AS daynum5,
        CONCAT(schedule.day6)  AS daynum6,
        CONCAT(schedule.day7)  AS daynum7,
        CONCAT(schedule.day8)  AS daynum8,
        CONCAT(schedule.day9)  AS daynum9,
        CONCAT(schedule.day10)  AS daynum10,
        CONCAT(schedule.day11)  AS daynum11,
        CONCAT(schedule.day12)  AS daynum12,
        CONCAT(schedule.day13)  AS daynum13,
        CONCAT(schedule.day14)  AS daynum14,
        CONCAT(schedule.day15)  AS daynum15,
        CONCAT(schedule.day16)  AS daynum16,
        CONCAT(schedule.day17)  AS daynum17,
        CONCAT(schedule.day18)  AS daynum18,
        CONCAT(schedule.day19)  AS daynum19,
        CONCAT(schedule.day20)  AS daynum20,
        CONCAT(schedule.day21)  AS daynum21,
        CONCAT(schedule.day22)  AS daynum22,
        CONCAT(schedule.day23)  AS daynum23,
        CONCAT(schedule.day24)  AS daynum24,
        CONCAT(schedule.day25)  AS daynum25,
        CONCAT(schedule.day26)  AS daynum26,
        CONCAT(schedule.day27)  AS daynum27,
        CONCAT(schedule.day28)  AS daynum28,
        CONCAT(schedule.day29)  AS daynum29,
        CONCAT(schedule.day30)  AS daynum30,
        CONCAT(schedule.day31)  AS daynum31
         FROM schedule WHERE (monthn = '" . $postmonth . "' AND yearn = '" . $postyear . "') AND userId = '" . $tz_userId . "' ORDER BY schedId DESC LIMIT 1";
$dayssel3 = mysqli_query($mysqli, $daysel) or die('-3' . mysqli_error());
$daysnum = mysqli_fetch_assoc($dayssel3);

$daynum1  = $daysnum['daynum1'];
$daynum2  = $daysnum['daynum2'];
$daynum3  = $daysnum['daynum3'];
$daynum4  = $daysnum['daynum4'];
$daynum5  = $daysnum['daynum5'];
$daynum6  = $daysnum['daynum6'];
$daynum7  = $daysnum['daynum7'];
$daynum8  = $daysnum['daynum8'];
$daynum9  = $daysnum['daynum9'];
$daynum10 = $daysnum['daynum10'];
$daynum11 = $daysnum['daynum11'];
$daynum12 = $daysnum['daynum12'];
$daynum13 = $daysnum['daynum13'];
$daynum14 = $daysnum['daynum14'];
$daynum15 = $daysnum['daynum15'];
$daynum16 = $daysnum['daynum16'];
$daynum17 = $daysnum['daynum17'];
$daynum18 = $daysnum['daynum18'];
$daynum19 = $daysnum['daynum19'];
$daynum20 = $daysnum['daynum20'];
$daynum21 = $daysnum['daynum21'];
$daynum22 = $daysnum['daynum22'];
$daynum23 = $daysnum['daynum23'];
$daynum24 = $daysnum['daynum24'];
$daynum25 = $daysnum['daynum25'];
$daynum26 = $daysnum['daynum26'];
$daynum27 = $daysnum['daynum27'];
$daynum28 = $daysnum['daynum28'];
$daynum29 = $daysnum['daynum29'];
$daynum30 = $daysnum['daynum30'];
$daynum31 = $daysnum['daynum31'];

$month = date('t');
if ($month == "30") {
    $hide31 = "hide";
}
?>