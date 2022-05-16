<?php
require_once(dirname(__FILE__)."/../../common.php");
//new
//require "../modules/models/mail.php";
//new
maybe_session_start();
verify_logged_in();
?>



<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta http-equiv="x-ua-compatible" content="ie=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <script
  src="https://code.jquery.com/jquery-3.6.0.js"
  integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk="
  crossorigin="anonymous"></script>
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/smoothness/jquery-ui.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="//code.jquery.com/jquery-1.12.4.js"></script>
    <script src="//code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

    <link rel="stylesheet" href="templates/header.css">

    <title>CRSMGR --- Group-work Assistant(CGA)</title>
  </head>

<!--new-->
<?php
try {
    if (array_key_exists("current_user", $_SESSION))
    {
        $received_mails = Mail::where(array('receiver_id' => $_SESSION["current_user"]->email));
    }
    else
    {
        $received_mails = Mail::where(array('receiver_id' => 'NAN'->email));
    }
} catch (PDOException $error) {
    echo $sql . "<br>" . $error->getMessage();
}

?>
<!--new-->

  <body>
    <h1>CRSMGR --- Group-work Assistant(CGA) </h1>
    <ul class="topnav">
      <b>
      <li class="navelem"><a class="active" href="index.php">Home</a></li>
      <li class="navelem"><a href="account_settings.php">Account Settings</a></li> 
     <!--new-->
     <?php $unread = 0;
      foreach ($received_mails as $row) { 
        if ($row->read_flag == 0) {
            $unread += 1;
        } } 

    if ($unread == 0) { ?>
      <!--new-->
      <li class="navelem"><a href="email.php">Email</a></li>
      </b>
</ul> </body>
      <!--new-->
      <?php } else { ?>
        <li class="navelem"><a href="email.php">Email<?php echo ' '.'('.$unread.')' ?></a></li>
                </b>
</ul></body>
        <?php } ?>

      <!--new-->
</b>
</ul>
    
    <?php
    if (isset($_SESSION["error_message"]) && $_SESSION["error_message"]!="") {
        echo $_SESSION["error_message"];
    }


    if (isset($_SESSION["current_user"])) {
        $name = get_users_name();
        echo "<p> Current user : <b> {$name} </b>  .  <input id=\"LogoutButton\" type=\"button\" value=\"Logout\" ></input>

        <script type=\"text/javascript\">
            document.getElementById(\"LogoutButton\").onclick = function () {
                location.href = \"logout.php\";
            };
        </script></p>";
    } else {
        header("Location: login.php");
    }
    ?>
    

  </body>

  
</html>