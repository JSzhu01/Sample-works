<?php
require "../modules/models/user.php";
require_once "../common.php";
require_once "../modules/models/loggedin.php";

maybe_session_start();

if (count($_POST) > 0) {
    $is_success = 0;
    $user = User::find_by_email($_POST["email"]);

    if (isset($user)) {
        if (password_verify($_POST["password"], $user->password_digest)) {
            $is_success = 1;
        }
    }

    if ($is_success == 0) {
        $_SESSION["error_message"] = "Invalid Email or Password!";
        header ("Location: login.php");
    } else {
		$_SESSION["error_message"] = "";
        // Create user login token
        $loginToken = new Loggedin();
        $loginToken->user_digest = md5(time());
        $loginToken->user_id = $user->id;

        // Check if user id already exists in loggedin table. If it does, delete all entries for the user id before saving new token
        if (Loggedin::find_by_user_id($user->id)) {
            // TODO: Delete the auth token for the user that already exists.
        }

        // Add token to logedin table
        try {
            $loginToken->save();
        } catch (PDOException $error) {
            echo "<br>" . $error->getMessage();
        }

        // Register login token in session variable
        $_SESSION["AuthKey"] = $loginToken->user_digest;
        $_SESSION["current_user"] = $user;
        // echo $_SESSION["current_user"]->email;
        $_SESSION["current_user_id"] = $user->id;
        $_SESSION["priv_level"] = $user->privilege;
        header("Location: main.php");
    }
}
?>