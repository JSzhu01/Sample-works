<?php
/*get connection*/
require "../modules/models/user.php";
require_once "../common.php";
ensure_logged_in();
?>

<?php include "templates/header.php"; ?>


<!-- Fan-->
<?php if (isset($_SESSION["current_user"])) {
    $user = $_SESSION["current_user"] ?>
<ul>
  <li>
    <a href="create_mail.php"><strong>New Message</strong></a>
  </li>
  <li>
    <a href="inbox.php"><strong>Inbox</strong></a>
  </li>
  <li>
    <a href="outbox.php"><strong>Outbox</strong></a>
  </li> 
</ul>

<?php
} else { ?>

    <h5>You need to be logged in to send an internal email.</h1>

<?php } ?>



<?php include "templates/footer.php"; ?>