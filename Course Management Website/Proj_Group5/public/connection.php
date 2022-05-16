<?php

require "../config.php";
//$con = mysqli_connect("localhost","UserName","Password","DatabaseName");
$con = mysqli_connect($host, $username, $password, $dbname);

if(!$con)
{
    die("Connection Error");
}
else
{

    echo 'Connection Successful';
}
 
 ?>