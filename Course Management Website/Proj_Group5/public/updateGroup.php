<?php

require_once("connection.php");  //reserve for group info update

if(isset($_POST['update']))
{
    $gid = $_GET['Gid']; 
    $name = trim(strtolower($_POST['group_name']));

    //echo $id . $name . $semester . $year.$credit_hours.$room.$offering_dept;

    $query = "update course_group set group_name = '".$name."' where group_id = '".$gid."' ";
    $result = mysqli_query($con,$query);

    if ($result)
    {
        $previousPage = $_SERVER["HTTP_REFERER"];
        header('Location: '.$previousPage);
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