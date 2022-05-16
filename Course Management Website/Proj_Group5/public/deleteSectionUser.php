<?php
include "templates/header.php"; 
require_once("connection.php");
echo "<br>";

if( isset($_GET['userId']))
{
    $sid = $_GET['sectionId'];
    $uid = $_GET['userId'];
    
    //drop course
    $query = "delete from enrollment where (user_id = '$uid' and section_id = '".$sid."') ";
    $result = mysqli_query($con,$query);

    
    //quit the group asscoate with course
    $query1 = "delete u.* from user_group u left join course_group c on u.group_id = c.group_id where user_id = '$uid' and section_id = '$sid' ";
    $result1 = mysqli_query($con,$query1);
    
    if ($result){
        echo '<center>Deleted and quit all groups assciate with this course. </center>';
    }
    else
    {
        echo 'Please check your Query';
    }
}
else
{
    echo("Something is going wrong, please try it again");
}

?>
<br>
<button onclick="history.go(-1);">Back </button>