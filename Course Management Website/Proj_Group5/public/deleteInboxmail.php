<?php

require_once("connection.php");
require_once "../modules/models/mail.php";
require_once "../common.php";

if( isset($_GET['del']) && ($mail = Mail::find_by_id($_GET["del"])))
{
    $id = $_GET['del'];
    $current_flag = $mail->delete_flag;
    $new_flag = intdiv($current_flag, 2) * 2;
    $query = "update mails set delete_flag = '".$new_flag."' where id = '".$id."' ";

    $result = mysqli_query($con,$query);
    
    if ($result){
        header("location:inbox.php");
    }
    else
    {
        echo 'Please check your Query';
    }
}
else
{
    header("location:inbox.php");
}

?>