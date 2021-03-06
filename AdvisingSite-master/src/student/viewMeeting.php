<html>
<head>
    <title>View Your Meeting</title>
    <link rel="stylesheet" type="text/css" href="../Styles/style.css">
</head>
<body>
<div id="content-container">
<div id="content">
<h1>View Meeting</h1>

<?php
session_start();

// redirect user to index.php if they haven't logged in
if($_SESSION["HAS_LOGGED_IN"] == false){
  header("Location: index.php");
}

include '../CommonMethods.php';

$debug = true;
$COMMON = new Common($debug);
$filename = "viewMeeting.php";

$studentID = $_SESSION["STUDENT_ID"];

//query to obtain meeting id corresponding to student from student meeting table
$select_Meeting_ID  = "SELECT MeetingID FROM StudentMeeting WHERE StudentID = $studentID";

//defining valuable == to meeting id returned by query
$select_results = $COMMON->executequery($select_Meeting_ID, $filename);

if(mysql_num_rows($select_results) == 0){
  echo "<p style='font-size:18px;'><br>You have not scheduled any appointments.</p><br>";
}

else{
  //fetching value from query result
  $results_row = mysql_fetch_array($select_results);
  
  //defining variable as array variable
  $currentApptIDVal = $results_row[0];
  
  $selectMeetingData = "SELECT * FROM Meeting WHERE meetingID = $currentApptIDVal";
  
  $rs = $COMMON->executequery($selectMeetingData, $filename);
  
  $meetingDict = mysql_fetch_assoc($rs);
  
  $_SESSION["CURRENT_MEETING_ID"] = $meetingDict["meetingID"];
  $_SESSION["CURRENT_START_TIME"] = $meetingDict["start"];
  $_SESSION["CURRENT_END_TIME"] = $meetingDict["end"];
  $_SESSION["CURRENT_APPT_BUILDING"] = $meetingDict["buildingName"];
  $_SESSION["CURRENT_APPT_ROOM"] = $meetingDict["roomNumber"];
      
  $weekday = date("l", strtotime($_SESSION["CURRENT_START_TIME"]));
  $year = date("Y", strtotime($_SESSION["CURRENT_START_TIME"]));
  $month = date("F", strtotime($_SESSION["CURRENT_START_TIME"]));
  $day = date("j", strtotime($_SESSION["CURRENT_START_TIME"]));
  
  //start "S" hour and minute                                                                                                
  $hourS = date("h", strtotime($_SESSION["CURRENT_START_TIME"]));
  $minS = date("i", strtotime($_SESSION["CURRENT_START_TIME"]));
 
  //end "E" hour and minute                                                                                                  
  $hourE = date("h", strtotime($_SESSION["CURRENT_END_TIME"]));
  $minE = date("i", strtotime($_SESSION["CURRENT_END_TIME"]));


  echo "<p style='font-size:18px;'><br>Your current meeting is:</p>";
  echo "<p style='font-size:18px;'>",$weekday, " ", $month, " ", $day, ", ", $year, " from ";
  echo $hourS, ":", $minS, " to ", $hourE, ":", $minE, " ";
  echo "in ", $_SESSION["CURRENT_APPT_BUILDING"], " ", $_SESSION["CURRENT_APPT_ROOM"], "</p>";
    
 echo"<br><p style='font-size:15px;'><b>There are a few things that you can do (or bring) that will go a long way toward ma\
    king your advising appointment efficient and productive:</b><br><br>                                                         
                                                                                                                             
    - Be on time for your appointment! If you need to cancel/reschedule, do so as far in advance as possible (24 hours ahead, or\
     more, is preferred), to allow other students to use the time that you leave empty. <br><br>                                 
                                                                                                                             
    - Be prepared to clarify/elaborate on any of the responses that you have submitted electronically.<br><br>                   
                                                                                                                             
    - Bring along a method of taking notes, in case your advisor has some recommendations or additional information to share wit\
    h you.<br><br>                                                                                                               
                                                                                                                             
    - Bring along any paperwork/forms that require your advisor's signature.<br><p>";


}
?>

<br>
<a href="homePage.php">Return Home</a>
<br>
</div>
</div>
</body>
</html>
