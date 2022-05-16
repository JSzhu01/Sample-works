<?php
require_once(dirname(__FILE__)."/../../common.php");
maybe_session_start();
verify_logged_in();
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta http-equiv="x-ua-compatible" content="ie=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />

		<script src="https://code.jquery.com/jquery-3.6.0.min.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/smoothness/jquery-ui.css">
		<script src="jquery.blockUI-2.70.0.js"></script>
    <script src="//code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

    <link rel="stylesheet" href="header_forum.css">

    <title>CRSMGR --- Group-work Assistant(CGA)</title>
  </head>

  <body>
    <h1>CRSMGR --- Group-work Assistant(CGA) </h1>
    <ul class="topnav">
      <b>
      <li class="navelem"><a class="active" href="../index.php">Home</a></li>
      <li class="dropdown">
        <button class="dropbtn"><b>Courses</b></button>
        <div class="dropdown-content">
          <a href="#">Course List</a>
          <a href="#">Marked Entities</a>
          <a href="../discussions.php">Discussions</a>
          <a href="../meetings.php">Meetings</a>
        </div>
      </li>
      <li class="navelem"><a href="../account_settings.php">Account Settings</a></li>
      <li class="navelem"><a href="#email">Email</a></li>
      <li class="navelem"><a href="../instructors_list.php">Instructors</a></li>
</b>
</ul>
    
    <?php
    if (isset($_SESSION["error_message"]) && $_SESSION["error_message"]!="") {
        echo $_SESSION["error_message"];
    }

    if (isset($_SESSION["current_user"])) {
        $name = get_users_name();
        echo "<p> Current user : <b> {$name} </b> .  <input id=\"LogoutButton\" type=\"button\" value=\"Logout\" ></input>

        <script type=\"text/javascript\">
            document.getElementById(\"LogoutButton\").onclick = function () {
                location.href = \"../logout.php\";
            };
        </script></p>";
    } else {
        echo "<p> Please login to proceed. <input id=\"LoginButton\" type=\"button\" value=\"Login\" ></input>

        <script type=\"text/javascript\">
            document.getElementById(\"LoginButton\").onclick = function () {
                location.href = \"../login.php\";
            };
        </script></p>";
    }
    ?>
    

  </body>

  
</html>