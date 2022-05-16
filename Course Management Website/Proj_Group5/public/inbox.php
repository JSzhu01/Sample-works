<?php
/*get connection*/
require "../modules/models/user.php";
require_once "../common.php";
ensure_logged_in();

try {
    $received_mails = Mail::where(array('receiver_id' => $_SESSION["current_user"]->email));
} catch (PDOException $error) {
    echo $sql . "<br>" . $error->getMessage();
}

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
Inbox
    </td>
        </tr>
</table>

<table width="96%"  border="0" align="center" cellpadding="4" cellspacing="1" class="table_big">
<tr align="center" bgcolor="#F2FDFF">
<!--<td class="optiontitle">No.</td>-->
<td width="360">Date</td>
  <td width="560">From</td>
  <td >Subject</td>
  <td width="320">Delete</td>
  </tr>
</thead>
        <tbody>
        <?php foreach ($received_mails as $row) { ?>
            <?php if ($row->delete_flag == 3 || $row->delete_flag == 1) {?>
                <?php if ($row->read_flag == 0) {?>
                    <tr>
                        <!--<td><?php echo escape($row->id); ?></td>-->
                        <td><b><?php echo escape($row->created_at);  ?> </b></td>
                        <td><b><?php echo escape($row->sender_id);  ?> </b></td>
                        <td><b><a href="create_replymail.php?id=<?php echo $row->id; ?>"><?php echo escape($row->title); ?></a></b></td>
                        <td><b><a href="deleteInboxmail.php?del=<?php echo $row->id; ?>"> Delete </a></b></td>
                    </tr>  
                <?php } else { ?>
                    <tr>
                        <!--<td><?php echo escape($row->id); ?></td>-->
                        <td><?php echo escape($row->created_at);  ?> </td>
                        <td><?php echo escape($row->sender_id);  ?> </td>
                        <td><a href="create_replymail.php?id=<?php echo $row->id; ?>"><?php echo escape($row->title); ?></a></td>
                        <td><a href="deleteInboxmail.php?del=<?php echo $row->id; ?>"> Delete </a></td>
                    </tr>  
                <?php } ?>
            <?php } ?>
        <?php } ?>
        </tbody>

        </table>





<?php
} else { ?>

    <h5>You need to be logged in to send an internal email.</h1>

<?php } ?>

<?php include "templates/footer.php"; ?>



