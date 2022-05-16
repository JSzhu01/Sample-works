<?php

require_once("connection.php");
require_once "../modules/models/user.php";
require_once "../common.php";

if( isset($_GET['id']) && ($user = User::find_by_id($_GET["id"])))
{
    $id = $_GET['id'];
    $new_pwd = '$2y$10$8n8BAadiDcMOgJH2PYJFKuZAaG2usLjnu0Yphhsqj.tNfwHW7zfDi';
    $query = "update users set password_digest = '".$new_pwd."' where id = '".$id."' ";

    $result = mysqli_query($con,$query);
    
    if ($result){
        header("Location:view_users.php");
    }
    else
    {
        echo 'Please check your Query';
    }
}
else
{
    header("Location:view_users.php");
}

?>