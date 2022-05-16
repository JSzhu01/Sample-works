<?php
include "templates/header.php"; 
require_once("connection.php");
echo "<br>";

if( isset($_GET['del']))
{
    $sid = $_GET['del'];
    $current_userId = $_SESSION["current_user"]-> id;
    
    //drop course
    $query = "delete from enrollment where (user_id = '".$current_userId."' and section_id = '".$sid."') ";
    $result = mysqli_query($con,$query);

    
    //quit the group asscoate with course
    $query1 = "delete u.* from user_group u left join course_group c on u.group_id = c.group_id where user_id = '".$current_userId."' and section_id = '$sid' ";
    $result1 = mysqli_query($con,$query1);
    
    if ($result){
        echo("You have successfully dropped and quit all groups associated with this course.");
    }
    else
    {
        echo 'Please check your Query';
    }
}
else
{
    echo("Something went wrong! Please try again.");
}

?>
<br>
<a href="joinCourse.php">Back to Last Page</a>