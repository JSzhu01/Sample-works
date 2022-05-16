<?php
/*get connection*/
require "../modules/models/user.php";
require_once "../common.php";
ensure_logged_in();

//new
try {
    //$sent_mails = Mail::where(array('sender_id' => $_SESSION["current_user"]->id));
    $sent_mails = Mail::where(array('sender_id' => $_SESSION["current_user"]->email));
} catch (PDOException $error) {
    echo $sql . "<br>" . $error->getMessage();
}
//new
?>

<?php include "templates/header.php"; ?>

<?php if (isset($_SESSION["current_user"])) {
    $user = $_SESSION["current_user"] ?>


<a href="email.php">Back to Email</a>
<table width="96%"  border="0" align="center" cellpadding="4" cellspacing="1" class="table_big">
 <tr align="center" >
          <td >
<!--<a href="email.php" style="color:red">Inbox</a>-->
<!--<a href="outbox.php" style="color:blue">Outbox</a>-->
    </td>
        </tr>
     <tr align="center" bgcolor="#aec3de">
          <td >
Outbox
    </td>
        </tr>
</table>

<table width="96%"  border="0" align="center" cellpadding="4" cellspacing="1" class="table_big">
<tr align="center" bgcolor="#F2FDFF">
<!--<td class="optiontitle">No.</td>-->
<td width="360">Date</td>
  <td width="560">To</td>
  <td >Subject</td>
  <td width="320">Delete</td>
  </tr>
<!--new-->
</thead>
            <tbody>
        <?php foreach ($sent_mails as $row) { ?>
            <?php if ($row->delete_flag == 3 || $row->delete_flag == 2) {?>
            <tr>
                <!--<td><?php echo escape($row->id); ?></td>-->
                <td><?php echo escape($row->created_at);  ?> </td>
                <td><?php echo escape($row->receiver_id);  ?> </td>
                <td><a href="each_mail.php?id=<?php echo $row->id; ?>"><?php echo escape($row->title); ?></a></td>
                <td><a href="deleteOutboxmail.php?del=<?php echo $row->id; ?>"> Delete </a></td>
            </tr>
            <?php } ?>   
        <?php } ?>
            </tbody>
        </table>
<!--new-->

<?php
} else { ?>

    <h5>You need to be logged in to send an internal email.</h1>

<?php } ?>

<?php include "templates/footer.php"; ?>



