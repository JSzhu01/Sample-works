<?php

require_once("connection.php");

/*$query = "select course_num, course_name, section_id from project5531.courses where course_num = '".$id."'";
$old_result = mysqli_query($con,$query); 
$row = mysqli_fetch_assoc($old_result);*/

if(isset($_POST['update']))
{
    $old_id = $_GET['Id'];
    $id = $_POST['course_num'];   
    $name = $_POST['course_name'];
    $semester = $_POST['semester'];
    $year = $_POST['year'];
    $credit_hours = $_POST['credit_hours'];
    $room = $_POST['room'];
    $offering_dept = $_POST['offering_dept']; 

    //echo $id . $name . $semester . $year.$credit_hours.$room.$offering_dept;

    $query = "update courses set course_num = '".$id."' ,course_name = '".$name."' ,
            semester = '".$semester."', year= '".$year."', credit_hours= '".$credit_hours."', 
            room= '".$room."', offering_dept = '".$offering_dept."' where course_num ='".$old_id."' ";
    $result = mysqli_query($con,$query);

    if ($result)
    {
        header("location:modifyCourse.php");
    }
    else
    {
        echo 'Please check your query';
    }
}
else
{
    header("location:modifyCourse.php");
}


?>