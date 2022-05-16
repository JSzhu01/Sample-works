<?php

require_once "../modules/models/discussion.php";
require_once "../common.php";

ensure_logged_in();

if (isset($_POST["submit"])) {
    $discussion = new Discussion();
    $discussion->title = $_POST["title"];
    $discussion->user_id = $_SESSION["current_user"]->id;
    $discussion->topic_content = $_POST["content"];
    $discussion->me_id = $_POST["me_id"];
    $discussion->instructor_post = $_POST["i_post"];
    $gid = $_POST["i_post"];

    try {
        $discussion->save();

        header("Location: sub_forum/index.php?id={$discussion->id}"."&me_id=$discussion->me_id"."&gid=$gid");;
    } catch (PDOException $error) {
        echo "<br>" . $error->getMessage();
    }
}
?>

<!-- <?php include "templates/header.php"; ?>

<h2>Add a discussion</h2>
<form method="post">
    <label for="title">Title</label>
    <input type="text" name="title" id="title">
    <input type="hidden" name="me_id" value= "">
    <br></br>
    <label for="content">Content</label>
    <div>
    <textarea name="content" id="content" rows="6"></textarea>
    </div>
    <input type="submit" name="submit" value="Submit">
</form>

<a href="index.php"> back to home</a>

<?php include "templates/footer.php"; ?> -->