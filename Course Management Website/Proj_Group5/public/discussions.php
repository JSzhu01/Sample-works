<?php include "templates/header.php"; ?>
<?php

require "../modules/models/discussion.php";
require_once "../modules/models/marked_entity.php";

require_once ("check_login.php");
require_once "../common.php";


$discussion_records = getConnection();
$is_admin = False;

if(get_priv() == 0){
    $is_admin = True;
}

if((!isset($_GET["me"]) or !isset($_GET["gid"])) and !$is_admin){
    echo '<a href ="index.php"> Back to home page </a><br></br>';
    die("Likely an illegal access!");
}

$is_old = False;
$is_instructor = False;
$is_expired = False;

if(!$is_admin and (!isset($_SESSION['me_access_'.$_GET["me"]]) or !isset($_SESSION['me_'.$_GET['me'].'_group_access_'.$_GET['gid']]) or !isset($_SESSION['me_expired_'.$_GET["me"]]))){
 echo "Redirecting...";
 header('Location: ../marked_entity.php?id=' . $_GET['me']);
}

if(get_priv() == 0){
    if(isset($_GET["me"]) and isset($_GET["gid"])){
    $me = $_GET["me"];
    $gid = $_GET["gid"];
    }
    // pass
}
else {
    // echo $_SESSION['me_access_'.$_GET["me"]];
    // echo $_SESSION['group_access_'.$_GET['gid']];
   $is_expired = $_SESSION['me_expired_'.$_GET["me"]];
    if(get_priv() == 3){
        if(!isset($_SESSION['is_old_group_'.$_GET['gid']])){
            echo "Redirecting...";
            header('Location: marked_entity.php?id=' . $_GET['me']);
        }
        else{
            if($_SESSION['is_old_group_'.$_GET['gid']]){
                $is_old = True;
            }
        }
    }
    else if (get_priv() == 1 or get_priv() == 2){
        $is_instructor = True;
    }
$me = $_GET["me"];
$gid = $_GET["gid"];
}




if(!$is_admin && is_null(MarkedEntity::find_by_id($me))){
    echo '<a href ="index.php"> Back to home page </a><br></br>';
    die("Non existent marked entity!");
}
else{
    if($is_admin && (!isset($_GET["me"]) or !isset($_GET["gid"])) ){
        $sql = 'SELECT * FROM discussions_info Where instructor_post = 0';
    }
    else if ($is_instructor){
        $sql = 'SELECT * FROM discussions_info WHERE me_id = '.$me.' AND group_id = '.$gid.' ORDER BY updated_at DESC' ;
        // echo $sql;
    }
    else{
        if($is_old){
            $old_since = $_SESSION['old_since'.$_GET['gid']];
            // echo $old_since;
            $sql = 'SELECT * FROM discussions_info WHERE me_id = '.$me.' AND group_id = '.$gid.
            ' AND updated_at < \''.$old_since.'\''.' ORDER BY updated_at DESC';
        }
        else{
            $sql = 'SELECT * FROM discussions_info WHERE me_id = '.$me.' AND group_id = '.$gid.' ORDER BY updated_at DESC' ;
        }
    }
    $d = $discussion_records->prepare($sql);
    $d -> execute();
    $discussions = $d -> fetchAll();
}

if($is_expired){
    echo "<b> The Marked entity already passed the deadline hence no changes can be made. Please contact admin if any change is needed.</b>";
}

// echo $i_sql;
if($is_admin && (!isset($_GET["me"]) or !isset($_GET["gid"]))){
    $i_sql = 'SELECT * FROM discussions_info WHERE instructor_post <> 0 ORDER BY updated_at DESC';
}
else{
    $i_sql = 'SELECT * FROM discussions_info WHERE me_id = '.$me.' AND instructor_post <> 0 ORDER BY updated_at DESC';
}
$i = $discussion_records->prepare($i_sql);
$i -> execute();
$i_posts = $i-> fetchAll();

?>



<?php
$count = 1;
if ($discussions && count($discussions)) { ?>
        <?php if($is_old){
        echo " <b>You have left the group, therefore only shows the discussions up until your leave. </b>";
    } ?>
    <?php if((!isset($_GET["me"]) or !isset($_GET["gid"]))){ ?>
    <div>Showing all the Discussions. Hi ADMIN.</div>
    <?php } else { ?>
    <div>Discussions for Group: <?php echo $discussions[0]['group_name']; ?> </div>
    <?php } ?>
    <div>Number of discussions: <?php echo count($discussions); ?> </div>

        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Title</th>
                    <th>User ID</th>
                    <th>Created At</th>
                    <th>Related Course</th>
                    <th>Related Group</th>
                    <th>Marked Entity Title</th>
                </tr>
            </thead>
            <tbody>
        <?php foreach ($discussions as $row) { ?>
            <tr>
                <td><?php echo escape($count); ?></td>
                <td><a href="./sub_forum/index.php?id=<?php echo $row['id']; ?>&me_id=<?php echo $row['me_id']; ?>&gid=<?php echo $row['group_id']; ?>"><?php echo escape($row['title']); ?></a></td>
                <td><?php echo escape(User::find_by_id($row['user_id']) -> first_name); ?></td>
                <td><?php echo escape($row['created_at']);  ?> </td>
                <td><?php echo escape($row['course_num']);  ?> </td>
                <td style="text-align:center"><?php echo escape($row['group_name']);  ?> </td>
                <td><?php echo escape($row['me_title']);  ?> </td>
            </tr>
        <?php $count++;} ?>
        
        </tbody>
    </table>
    <?php } else { ?>
        <blockquote>No discussions found.</blockquote>
    <?php }
?> 

<?php if(($i_posts) && count($i_posts)) { ?>
<h3>Instuctor Posts:</h3>
<table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Title</th>
                    <th>User ID</th>
                    <th>Created At</th>
                    <th>Related Course</th>
                    <th>Marked Entity Title</th>
                </tr>
            </thead>
            <tbody>
        <?php $count = 1; foreach ($i_posts as $row) { ?>
            <tr>
                <td><?php echo escape($count); ?></td>
                <td><a href="./sub_forum/index.php?id=<?php echo $row['id']; ?>&me_id=<?php echo $row['me_id']; ?>&iid=<?php echo $row['instructor_post']; ?>"><?php echo escape($row['title']); ?></a></td>
                <td><?php echo escape(User::find_by_id($row['user_id']) -> first_name); ?></td>
                <td><?php echo escape($row['created_at']);  ?> </td>
                <td><?php echo escape($row['course_num']);  ?> </td>
                <td><?php echo escape($row['me_title']);  ?> </td>
            </tr>
        <?php $count++;
    }  ?>
                </tbody>
                </table>
                <?php } else{
                    echo "<blockquote>No instructor posts exists.</blockquote>"; 
                }
        ?>
<?php
if(!$is_old and !$is_expired and !$is_admin and !$is_instructor){ ?>
<p><h2>Add a discussion</h2>
<form method="post" action ="create_discussion.php">
    <label for="title">Title</label>
    <input type="text" name="title" id="title">
    <input type="hidden" name="me_id" value= "<?php echo $me?>" >
    <input type="hidden" name="gid" value= "<?php echo $gid?>" >
    <br></br>
    <label for="content">Content</label>
    <div>
    <textarea name="content" id="content" rows="6"></textarea>
    </div>
    <input type="submit" name="submit" value="Submit">
</form>
<?php } ?>
<?php
if($is_instructor or $is_admin && isset($me) && isset($gid)){ ?>
<p><h2>Add an instructor post</h2>
<form method="post" action ="create_instructor_p.php">
    <label for="title">Title</label>
    <input type="text" name="title" id="title">
    <input type="hidden" name="me_id" value= "<?php echo $me?>" >
    <input type="hidden" name="i_post" value= "<?php echo $gid?>" >
    <br></br>
    <label for="content">Content</label>
    <div>
    <textarea name="content" id="content" rows="6"></textarea>
    </div>
    <input type="submit" name="submit" value="Submit">
</form>
<?php } ?>

<p><a href="index.php"> Back to Home</a></p>

<?php include "templates/footer.php"; ?>