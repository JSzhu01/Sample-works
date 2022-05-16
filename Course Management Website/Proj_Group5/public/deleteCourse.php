<?php

require_once("connection.php");


if( isset($_GET['del']))
{
    $id = $_GET['del'];

    //delete course from course list(without anyother info)
    $query2 = "delete from courses where courses.course_num ='".$id."' ";
    $result2 = mysqli_query($con,$query2);

    if ($result2){
        header('Location: ' . $_SERVER["HTTP_REFERER"] );
        exit;
    }
    else
    {
        echo 'Please check your Query';
    }
}
else
{
    header("location:modifyCourse.php");
}

?>