<?php
require "../modules/models/user.php";
require_once "../common.php";
?>

<?php include "templates/header.php"; ?>

<?php

function compare_passwords($p1, $p2)
{
    // Used to verify the confirmation password matches original typed password
    return $p1 === $p2;
}
?>


<h3>
    User Account
</h3>

<style>
label { display: table-cell; }
input { display: table-cell; }
li    { display: table-row;}
</style>

<?php if (isset($_SESSION["current_user"])) {
    $user = $_SESSION["current_user"];
    // new (and the semicolon in the previous row.)
    if($_SESSION["current_user"] -> privilege == 0){  
    //new
    ?>

<form action="editAdmin.php?Id=<?php echo $user->id ?>"method="post" style="display:table">
 <ul>
  <li >
    <label for="first_name">First Name:  <?php echo escape($user->first_name)?></label>
    <input type="text" id="first_name" pattern="[A-Za-z ]{1,}" name="first_name" required>
  </li>
  <li>
    <label for="last_name">Last Name:  <?php echo escape($user->last_name)?></label>
    <input type="text" id="last_name" pattern="[A-Za-z ]{1,}" name="last_name" required>
  </li>
  <!--
  <li>
    <label for="student_id">Student ID: <?php echo escape($user->student_id)?></label>
    <input type="text" id="student_id" name="student_id"></input>
  </li>
  -->
  <li>
    <label for="email">Email: <?php echo escape($user->email)?></label>
    <input type="email" id="email" name="email" required></input>
  </li>
  <li>
    <label for="password">Change Password: </label>
    <input type="text" id="msg" name="adminpassword" required></input>
  </li>
 </ul>
 <input type="submit" name="submit" value="Submit">
</form>
<!--new-->
<?php
    }else { ?>

    <form action="editPassword.php?Id=<?php echo $user->id ?>" method="post" style="display:table">
 <ul>
  <li>
    <label for="password">Change Password: </label>
    <input type="text" id="msg" name="password" required></input>
  </li>
 </ul>
 <input type="submit" name="submit" value="Submit">
</form>

<?php } 
?>
<!--new-->
<?php
  }else { ?>

    <h5>You need to be logged to see your account details.</h1>

<?php } 
?>

<?php include "templates/footer.php"; ?>



