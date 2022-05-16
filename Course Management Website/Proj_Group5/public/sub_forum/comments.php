<?php
	require("config.php");
	require("helper.php");
	require("../../common.php");
	maybe_session_start();

if (!isset($_GET['id']) or !isset($_GET['me_id']) or (!isset($_GET['gid']) and !isset($_GET['iid']))) {
		echo '<a href="../index.php"> Back to home page</a>';
		die("Likely an Illegal access!");
}

$id = $_REQUEST['id'];
$database = getConnection();
$is_admin = False;
$me_id = $_GET["me_id"];
if(get_priv() == 0){
    $is_admin = True;
}


$is_old = False;
$is_instructor = False;
$is_expired = False;

if(!$is_admin){
if(!isset($_SESSION['me_access_'.$_GET["me_id"]]) ){
    echo '<a href="../index.php"> Back to home page</a>';
    die("Likely an Illegal access!");
}
else{
    if(isset($_GET['gid'])&& !isset($_SESSION['me_'.$_GET['me_id'].'_group_access_'.$_GET['gid']])){
        echo '<a href="../index.php"> Back to home page</a>';
        die("Likely an Illegal access!");
    }
}
}

if(get_priv() == 0){
    // pass
}
else {
    // echo $_SESSION['me_access_'.$_GET["me"]];
    // echo $_SESSION['group_access_'.$_GET['gid']];
$me_sql = 'SELECT * FROM marked_entities WHERE id= '.$me_id;
$me = dbQuery($me_sql);
$marked_entity = $me ->fetch_row();
$expiry_date = new DateTime($marked_entity['5']);
        if(new DateTime() > $expiry_date ){
            $is_expired =  True;
        }
        else{
            $is_expired = False;
        }
    if(get_priv() == 3){
        if(isset($_GET['gid']) && !isset($_SESSION['is_old_group_'.$_GET['gid']])){
            echo "Redirecting...";
            header('Location: ../marked_entity.php?id=' . $_GET['me_id']);
        }
        else{
            if(isset($_GET['gid']) && isset($_SESSION['is_old_group_'.$_GET['gid']])&&$_SESSION['is_old_group_'.$_GET['gid']]){
                $is_old = True;
            }
        }
    }
    else if (get_priv() == 1 or get_priv() == 2){
        $is_instructor = True;
    }
}
if(isset($_GET["gid"])){
 $gid = $_GET["gid"];
}
if(isset($_GET["iid"])){
    $iid = $_GET["iid"];
   }

	$sql = "SELECT * FROM comment_file_info WHERE page_id = $id";
	$results = dbQuery($sql);
	$items = array();
	while ($row = dbFetchAssoc($results)) {
		$items[] = $row;
	}
	$comments = format_comments($items,$is_admin);
?>