<?php

require_once("connection.php");

if( isset($_GET['del']))
{
    $id = $_GET['del'];
    $name = $_GET['name'];
    //echo $id."".$name;
    $query = "delete from course_section where section_id = '".$id."' ";
    $result = mysqli_query($con,$query);

    if ($result){
        header("location:editSection.php?GetId=$name");
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