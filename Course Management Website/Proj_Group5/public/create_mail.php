<?php

require "../modules/models/mail.php";
require_once "../common.php";
//4.22new
require_once("connection.php");
?>
<!--
<script language="javascript">
function disp_alert() {
    if (!isset($result)) {
        alert ("hahaha");
    }
}
</script>
-->

<?php
ensure_logged_in();

if (isset($_POST["submit"])) {
    $str_arr = explode(";", $_POST["receiver_id"]);
    foreach ($str_arr as $str)
    {
        //4.22new
        $receiver1_id = trim($str, " ");
        $query = "select * from users where email = '".$receiver1_id."'";
        $result = mysqli_query($con,$query);
        while ($row = mysqli_fetch_assoc($result)) {
            $receiver_id = $row['email']; 
        }
        if (mysqli_num_rows($result) != 0) {
              //4.22new
            $mail = new Mail();
            $mail->receiver_id = $receiver_id;
            $mail->sender_id = $_SESSION["current_user"]->email;
            //$mail->sender_id = $_SESSION["current_user"]->id;//
            $mail->title = $_POST["title"];
            $mail->content = $_POST["content"];
            $mail->delete_flag = 3;
            $mail->read_flag = 0;

            try {
                $mail->save();
            } catch (PDOException $error) {
            echo "<br>" . $error->getMessage();
            }
            header("Location: outbox.php"); 
        } 
        else {
            echo 'This email address does not exist in CRSMGR.';
            header("Refresh:2");

        }
        
    }

    //header("Location: outbox.php");   
}
?>

<?php include "templates/header.php"; ?>
<!--4.5new-->
<meta charset="utf-8">
<!--4.5new-->
<a href="email.php">Back to Email</a>

<h2>Create a New Message</h2>

<form method="post">
<table border="1" cellpadding="4" cellspacing="1" width="96%" align="center" class="example_thead_class">
        <tr class="tablerow">
        <th bgcolor=pink> To</th>   
        <td> <input type="text" name="receiver_id" id="receiver_id" size="40"></td>
</select>
        </tr>
        <tr class="tablerow">
        <th bgcolor=pink> Subject</th>
        <td> <input type="text" name="title" id="title" size="40"></td>
        </tr>
        <tr class="tablerow">
        <th bgcolor=pink> Content</th>
        <td> <textarea type="text" name="content" id="content" cols="100" rows="10"  style="width:550px;height:300px;">
            </textarea>
        </td></tr>
    </table>
    <table border =0 align = "center">
        <tr class="tableheader">
        <td align="center" colspan="4"><input type="submit" name="submit" value="Send" class="btnSubmit"></td>
        </tr>
    </table>
</form>


<?php include "templates/footer.php"; ?>