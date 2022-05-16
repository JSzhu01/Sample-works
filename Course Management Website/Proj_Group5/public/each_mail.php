<?php

require_once("connection.php");
require_once "../modules/models/mail.php";
require_once "../common.php";

ensure_logged_in();?>

<?php include "templates/header.php"; ?>

<?php

if (isset($_GET["id"]) && ($mail = Mail::find_by_id($_GET["id"]))) { 

    $new_flag = 1;
    $id = $_GET["id"];

    if ($mail->receiver_id == $_SESSION["current_user"]->email)
    {
        $query = "update mails set read_flag = '".$new_flag."' where id = '".$id."' ";
        $result = mysqli_query($con,$query);
    }
    
    ?>

    <table width="950"  border="0" align="center" cellpadding="4" cellspacing="1" >
    <tr width="120px">
    <td align="right">To:</td>
    <td align="left"><?php echo $mail->receiver_id;?></td>
    </tr>
    <tr width="120px">
    <td align="right">Date:</td>
    <td align="left"><?php echo $mail->created_at; ?></td>
    </tr>
    <tr width="120px">
    <td align="right">Subject:</td>
    <td align="left"><?php echo $mail->title; ?></td>
    </tr>
    <tr width="120px">
    <td align="right">Content:</td>
    <td align="left"><?php echo nl2br($mail->content); ?></td>
    </tr>
    </table>



<?php
} else {
        echo "Invalid Email No.";
    }

?>