<?php

require_once("connection.php");
require "../modules/models/user.php";
require_once "../common.php";

if(isset($_POST['submit']))
{
    $id = $_GET['Id'];
    $pwd = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $query = "update users set password_digest = '".$pwd."' where id = '".$id."' ";

    $result = mysqli_query($con,$query);
    
    if ($result){
        header("Location:account_settings.php");
    }
    else
    {
        echo 'Please check your Query';
    }
}
else
{
    header("Location:account_settings.php");
}

?>