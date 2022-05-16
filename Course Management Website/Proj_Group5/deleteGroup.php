<?php
require_once("connection.php");

if( isset($_GET['del']))
{
    $gid = $_GET['del'];
    $query = "delete from course_group where group_id = '".$gid."' ";
    $result = mysqli_query($con,$query);

    if ($result){
        header("deleted");
    }
    else
    {
        echo 'Please check your Query';
    }
}
else
{
    header("location:editGroup.php");
}

?>