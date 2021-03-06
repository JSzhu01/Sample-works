<?php

/**
  * Escapes HTML for output
  *
  */

include_once dirname(__FILE__)."/modules/models/loggedin.php";

date_default_timezone_set('America/Montreal');

function escape($html)
{
    if(is_null($html)){
        return '';
    }
    return htmlspecialchars($html, ENT_QUOTES | ENT_SUBSTITUTE, "UTF-8");
}

function maybe_session_start()
{
    if (!isset($_SESSION)) {
        session_start();
    }
}

function ensure_logged_in()
{
    maybe_session_start();

    if (!isset($_SESSION["current_user"])) {
        header("Location: login.php");
    }
}

function array_last($arr)
{
    return $arr[array_key_last($arr)] ?? null;
}

function startsWith($string, $startString)
{
    $len = strlen($startString);
    return (substr($string, 0, $len) === $startString);
}

function get_users_name()
{
    // Gets the first name of the logged in user
    if (isset($_SESSION)) {
        $fname = $_SESSION["current_user"];
        return $fname->first_name;
    } else {
        return '';
    }
}

function verify_logged_in()
{
    if (isset($_SESSION) && array_key_exists("AuthKey", $_SESSION)) { // Session is started and authentication key exists
        // Get the auth token from session
        $authtoken = $_SESSION["AuthKey"];
        if (!Loggedin::find_by_user_digest($authtoken)) { // If user does not exist or authentication key does not match an entry in the loggedin table, logout
            $_SESSION = array();
            setcookie(session_name(), '', time() - 30000, '/'); // time() - 30000 changes the expiration time of the cookie to some point in the past so that the cookie expires
            session_destroy();
        }
    } else { // No session details exist or authentication key doesnt exist
    return false;// Should this return none or null?
    }
}

function logged_in()
{
    maybe_session_start();
    return isset($_SESSION["current_user"]);
}

function isAssoc(array $arr): bool
{
    if (array() === $arr) {
        return false;
    }
    return array_keys($arr) !== range(0, count($arr) - 1);
}

function get_priv(){
    maybe_session_start();
    return $_SESSION['priv_level'] ?? null;
}