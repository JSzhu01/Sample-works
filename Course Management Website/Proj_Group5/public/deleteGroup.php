<?php

require_once("connection.php");

if( isset($_GET['del']))
{
    $id = $_GET['del'];
    //$name = $_GET['name'];
    //echo $id."".$name;
    $query = "delete from course_group where group_id = '".$id."' ";
    $result = mysqli_query($con,$query);

    if ($result){
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
    header("location:course_list.php");
}

?>