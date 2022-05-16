<?php
	require_once("config.php");

    if (!isset($_SESSION["current_user"])) {
        header("Location: ../login.php");
    }
    
    $id = 0;
    $author = $_SESSION["current_user"]->first_name;
	if (!empty($_GET['id'])) {
		$id = $_REQUEST['id'];
	}



	$sql = "SELECT users.first_name, discussions.topic_content FROM users INNER JOIN discussions ON users.id"." = discussions.user_id AND discussions.id = $id" ;
	$results = dbQuery($sql);
	if($results->num_rows > 0){
        $row = $results -> fetch_assoc();
        $topic_author = $row["first_name"];
        $topic_content = $row["topic_content"];
    }
    else{
        header('Location:../discussions.php');
    }
?>