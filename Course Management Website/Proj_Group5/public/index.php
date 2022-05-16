<?php
require_once(dirname(__FILE__)."/../common.php");
maybe_session_start();

if (!isset($_SESSION["current_user"])) {
     header("Location: login.php");
}
else {
  header("Location: main.php");
}

?>