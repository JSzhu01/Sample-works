<?php

require_once("connection.php");
require "../modules/models/user.php";
require_once "../common.php";

if(isset($_POST['submit']))
{
    $id = $_GET['Id'];
    $fname = $_POST['first_name'];
    $lname = $_POST['last_name'];
    $email = $_POST['email'];
    $pwd = password_hash($_POST['adminpassword'], PASSWORD_DEFAULT);
    $query = "update users set id = '".$id."', first_name = '".$fname."', last_name = '".$lname."', email = '".$email."', password_digest = '".$pwd."' where id = '".$id."' ";

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