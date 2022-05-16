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
//new
if (isset($_POST["submit"])) {
    $replytemplate = 'Re:';
    $replymail = new Mail();
    $replymail->receiver_id = $mail->sender_id;
    $replymail->sender_id = $_SESSION["current_user"]->email;
    //$mail->sender_id = $_SESSION["current_user"]->id;//
    $replymail->title = $replytemplate.$mail->title;
    $replymail->content = $_POST["reply"];
    $replymail->delete_flag = 3;
    $replymail->read_flag = 0;
    try {
        $replymail->save();
    } catch (PDOException $error) {
        echo "<br>" . $error->getMessage();
    }


    header("Location: outbox.php");   
}
//new  
?>
<table width="950"  border="0" align="center" cellpadding="4" cellspacing="1" >
<tr width="120px">
<td align="right">From:</td>
<td align="left"><?php echo $mail->sender_id;?></td>
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
<form method="post">
<table width="950"  border="0" align="center" cellpadding="4" cellspacing="1" >
<tr align="center" >
<td align="right">Reply:</td>
<td align="left">
<textarea type="text" name="reply" id="reply" cols="100" rows="10"  style="width:550px;height:300px;">
</textarea>
</td>
</tr>
<tr align="center">
<td  colspan="2" >
    <input type="submit" name="submit" value="Send" class="btnSubmit">
</td>
</tr>
</table>
</form>

<?php
} else {
        echo "Invalid Email No.";
    }

?>