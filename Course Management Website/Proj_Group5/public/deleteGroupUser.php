<?php

require_once("connection.php");

if( isset($_GET['del']))
{
    $ugid = $_GET['del'];

    echo $ugid;
    $query = "delete from user_group where user_group_id = '".$ugid."' ";
    
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