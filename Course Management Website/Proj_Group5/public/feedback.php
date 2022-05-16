<?php

require_once "../modules/models/feedback.php";
require_once "../common.php";

ensure_logged_in();
$database = getConnection();
if (isset($_POST["submit"])) {
    $add = 'INSERT INTO feedbacks (user_id,content,identity_id,identity_type,updated_at) VALUES (:user_id,:content,:identity_id,"files",:timestamp) ON DUPLICATE KEY UPDATE content = :content,updated_at = :timestamp';
    $dbo = $database -> prepare($add);
    $dbo->bindValue(':user_id', $_SESSION["current_user_id"], PDO::PARAM_INT);
    $dbo->bindValue(':content', $_POST["content"], PDO::PARAM_STR);
    $dbo->bindValue(':identity_id', $_POST["identity_id"], PDO::PARAM_INT);
    $date = new DateTime();
    $dbo->bindValue(':timestamp', $date ->format('Y-m-d H:i:s') , PDO::PARAM_STR);
    $dbo-> execute();
    header('Location: ' . $_SERVER["HTTP_REFERER"] );
}
?>

<?php include "templates/header.php"; ?>
<?php
if(!isset($_GET["me_id"]) or !isset($_GET["identity_id"])){
    echo '<a href ="index.php"> Back to home page </a><br></br>';
    die("Likely an illegal access!");
}

$identity_id = $_GET["identity_id"];
if(!is_numeric($identity_id)){
    echo '<a href ="index.php"> Back to home page </a><br></br>';
    die("Invalid ID!");
}

if(!isset($_SESSION['me_access_'.$_GET["me_id"]])) {
 echo "Redirecting...";
 header('Location: marked_entity.php?id=' . $_GET['me_id']);
}

if(!$_SESSION['me_access_'.$_GET["me_id"]]){
    echo '<a href ="course_list.php"> Back to course page</a><br></br>';
        die("You have no access to this feedback!");
}



$sql ='SELECT * FROM feedbacks where identity_id ='.$identity_id.' AND identity_type = "files"';
$q = $database->prepare($sql);
$q -> execute();
$feedback = $q->fetchAll();
?>
<?php
$feedback_e = False;
if($feedback){
    foreach($feedback as $feed){
        if($feed['user_id'] == $_SESSION["current_user_id"]){
            $feedback_e = True;
        }
    ?>
    <table>
            <thead>
                <tr>
                    <th>User ID</th>
                    <th>Created_at</th>
                    <th>Updated_at</th>
                </tr>
            </thead>
            <tbody>
            <tr>
                <td><?php echo escape($feed['user_id']); ?></td>
                <td><?php echo escape($feed['created_at']);  ?> </td>
                <td><?php echo escape($feed['updated_at']);  ?> </td>
            </tr>
            </tbody>
    </table>
    <p style="padding: 5px; border: 2px solid red;"> <?php echo escape($feed['content']); ?></p>

 <?php }}?>
        

<?php if(get_priv() == 0 or get_priv()==1 or get_priv()==2){ 
    if($feedback_e){?>
<h2>Update the feedback</h2>
<?php } else { ?>
    <h2>Provide the feedback</h2>
    <?php } ?>
<form method="post">
    <input type="hidden" name="identity_id" value= <?php echo $_GET['identity_id'] ?>>
    <label for="content">Content</label>
    <div>
    <textarea name="content" id="content" rows="6"></textarea>
    </div>
    <input type="submit" name="submit" value="Submit">
</form>
<?php } ?>

<a href="javascript:history.go(-1)"> back to file page</a>

<?php include "templates/footer.php"; ?>