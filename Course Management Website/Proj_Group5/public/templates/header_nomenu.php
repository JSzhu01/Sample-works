<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta http-equiv="x-ua-compatible" content="ie=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <script type="text/javascript" src="https://code.jquery.com/jquery-3.6.0.slim.min.js" integrity="sha256-u7e5khyithlIdTpu22PHhENmPcRdFiHRjhAuHcs05RI=" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/smoothness/jquery-ui.css">
    <script src="//code.jquery.com/jquery-1.12.4.js"></script>
    <script src="//code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

    <link rel="stylesheet" href="templates/header.css">

    <title>CRSMGR --- Group-work Assistant(CGA)</title>
  </head>

  <body>
    <h1>CRSMGR --- Group-work Assistant(CGA) </h1>
    <?php
    if (isset($_SESSION["error_message"]) && $_SESSION["error_message"]!="") {
      echo '<center>'.$_SESSION["error_message"].'</center>';
    }
    ?>
  </body>  
</html>