<?php include "templates/header.php"; 
?>
<a href= "<?php echo $_SERVER["HTTP_REFERER"]?>"> Back to last page</a>
<br></br>
<?php
require_once "../modules/models/marked_entity_file.php";
require_once "../modules/models/marked_entity.php";
require_once "../modules/models/course_group.php";
require_once "../modules/models/attachment.php";
require_once "../common.php";

ensure_logged_in();

if (isset($_POST['submit'])) {
    $marked_entity_file = new MarkedEntityFile();
    $marked_entity_file->title = $_POST['title'];
    $marked_entity_file->description = $_POST['description'];
    $marked_entity_file->entity_id = $_POST['marked_entity_id'];
    $marked_entity_file->user_id = $_POST['user_id'];

    // Uploads folder needs to be created in the public/ directory
    $target_dir = "uploads/";
    $file_id = uniqid().basename($_FILES["file"]["name"]);
    $target_file = $target_dir . $file_id;

    $file_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    $file_size = $_FILES["file"]["size"];

    $attachment = new Attachment();
    $attachment->file_size = $_FILES["file"]["size"];
    $attachment->file_filename = basename($_FILES["file"]["name"]);
    $attachment->file_id = $file_id;

    if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)) {
        echo "The file ". htmlspecialchars(basename($_FILES["file"]["name"])). " has been uploaded.";
    } else {
        echo "Sorry, there was an error uploading your file.";
    }

    try {
        $marked_entity_file->save();
        $attachment->attachable_id = $marked_entity_file->id;
        $attachment->attachable_type = 'MarkedEntityFile';
        $attachment->save();
        header("Location:".$_SERVER['HTTP_REFERER']);
    } catch (PDOException $error) {
        echo "<br>" . $error->getMessage();
    }
}

if(!isset($_GET["marked_entity_id"]) or !isset($_GET["group_id"])){
    echo '<a href ="index.php"> Back to home page </a><br></br>';
    die("Likely an illegal access!");
}

$is_old = False;
$is_admin = False;
$is_instructor = False;
$is_expired = False;

$database = getConnection();


if(!isset($_SESSION['me_access_'.$_GET["marked_entity_id"]]) or !isset($_SESSION['me_'.$_GET['marked_entity_id'].'_group_access_'.$_GET['group_id']]) 
or !isset($_SESSION['me_expired_'.$_GET["marked_entity_id"]])
){
 echo "Redirecting...";
 header('Location: marked_entity.php?id=' . $_GET['marked_entity_id']);
}

if(get_priv() == 0){
    $is_admin = True;
    // pass
}
else {
    if(!$_SESSION['me_access_'.$_GET["marked_entity_id"]] or !$_SESSION['me_'.$_GET['marked_entity_id'].'_group_access_'.$_GET['group_id']]){
        echo '<a href ="course_list.php"> Back to course page</a><br></br>';
        die("You have no access to these files!");
    }
   $is_expired = $_SESSION['me_expired_'.$_GET["marked_entity_id"]];
    if(get_priv() == 3){
        if(!isset($_SESSION['is_old_group_'.$_GET['group_id']])){
            echo "Redirecting...";
            header('Location: marked_entity.php?id=' . $_GET['marked_entity_id']);
        }
        else{
            if($_SESSION['is_old_group_'.$_GET['group_id']]){
                $is_old = True;
            }
        }
    }
    else if (get_priv() == 1 or get_priv() == 2){
        $is_instructor = True;
    }
}

$marked_entity_id = $_GET["marked_entity_id"];
$group_id = $_GET["group_id"];


if(is_null(MarkedEntity::find_by_id($marked_entity_id))){
    echo '<a href ="index.php"> Back to home page </a><br></br>';
    die("Non existent marked entity!");
}
else{
    if($is_admin or $is_instructor){
        $sql = 'SELECT * FROM student_file_info WHERE entity_id = '.$marked_entity_id.' AND group_id = '.$group_id.' ORDER BY updated_at DESC' ;
        // echo $sql;
    }
    else{
        if($is_old){
            $old_since = $_SESSION['old_since'.$_GET['group_id']];
            // echo $old_since;
            $sql = 'SELECT * FROM student_file_info WHERE entity_id = '.$marked_entity_id.' AND group_id = '.$group_id.
            ' AND updated_at < \''.$old_since.'\''.' ORDER BY updated_at DESC';
        }
        else{
            $sql = 'SELECT * FROM student_file_info WHERE entity_id = '.$marked_entity_id.' AND group_id = '.$group_id.' ORDER BY updated_at DESC' ;
        }
    }
    $f = $database->prepare($sql);
    $f -> execute();
    $files = $f -> fetchAll();

    $record_query = 'SELECT f.*,u.first_name FROM `file_actions` as f join users as u where f.user_id = u.id AND me_id ='.$marked_entity_id.' ORDER BY action_at DESC';
    if($is_old){
        $record_query = 'SELECT f.*,u.first_name FROM `file_actions` as f join users as u where f.user_id = u.id AND me_id ='.$marked_entity_id. 
        ' AND action_at < \''.$old_since.'\''.' ORDER BY action_at DESC';
        // echo $record_query;
    }
    $r = $database->prepare($record_query);
    $r -> execute();
    $records = $r -> fetchAll();
}

if($is_expired){
    echo "<b> The Marked entity already passed the deadline hence no changes can be made. Please contact admin if any change is needed.</b>";
}

if ($files) {  ?>
    <?php if($is_old){
        echo " <b>You have left the group, therefore only shows the file up until your leave. </b>";
    } ?>
    <div>Files for Group: <?php echo $group_id; ?> </div>
    <div>Number of files: <?php echo count($files); ?> </div>

    <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Title</th>
                    <th>Description</th>
                    <th>File name</th>
                    <th>Created At</th>
                    <th>Download</th>
                    <th>Feedback</th>
                </tr>
            </thead>
            <tbody>
        <?php 
        $count = 1;
        foreach ($files as $row) { ?>
            <tr id="<?php echo escape($row['mef_id']); ?>" file_id = "<?php echo escape($row['fid_string']); ?>">
                <td><?php echo escape($count); ?></td>
                <td><?php echo escape($row['title']); ?></td>
                <td><?php echo escape($row['description']); ?></td>
                <td><?php echo $row['filename']; ?></td>
                <td><?php echo escape($row['created_at']);  ?> </td>
                <td><a href="download.php?file_id=<?php echo $row['fid_string'] ?>">Download</a></td>
                <td><a href="feedback.php?identity_id=<?php echo $row['mef_id']?>&me_id=<?php echo $marked_entity_id?>">Feedbacks</a> </td>
                <?php if (get_priv() == 0){ ?>
                <td><a href='<?php echo "#" ?>' class = "delete">Delete</a></td>
                <?php }; ?>
            </tr>
        <?php $count++;} ?>
        </tbody>
    </table>
<?php
} else { ?>
    <div>Files for Group: <?php echo $group_id; ?> </div>
    <div>Number of files: <?php echo count($files); ?> </div>
       <?php echo "No file existed.";
    } ?>
<br></br>
<?php if ($records) {  ?>
    <?php if($is_old){
        echo " <b>You have left the group, therefore only shows the file actions up until your leave. </b>";
    } ?>
    <br> </br>
    <div>Number of Actions: <?php echo count($records); ?> </div>

    <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>User Name</th>
                    <th>Action done</th>
                    <th>On File</th>
                    <th>At When</th>
                </tr>
            </thead>
            <tbody>
        <?php 
        $count = 1;
        foreach ($records as $row) { ?>
            <tr>
                <td><?php echo escape($count); ?></td>
                <td><?php echo escape($row['first_name']); ?></td>
                <td><?php echo escape($row['action']); ?></td>
                <td><?php echo $row['filename']; ?></td>
                <td><?php echo escape($row['action_at']);  ?> </td>
            </tr>
        <?php $count++;} ?>
        </tbody>
    </table>
<?php
} else { ?>
          <?php if($is_old){
        echo " <b>You have left the group, therefore only shows the file actions up until your leave. </b>";
    } ?>
       <div>Number of Actions: <?php echo count($records); ?> </div>
       <?php echo "No action record existed.";

    } ?>


<?php
if(!$is_old and !$is_instructor and !$is_expired and !$is_admin){ ?>
            <div>Add new file:</div>
            <form method="post" action="marked_entity_files.php" enctype="multipart/form-data">
                <label for="title">Title</label>
                <input type="text" name="title" id="title">
                <label for="description">Description</label>
                <input type="text" name="description" id="description">
                <input type="hidden" id="user_id" name="user_id" value="<?php echo $_SESSION["current_user_id"]; ?>">
                <input type="hidden" id="marked_entity_id" name="marked_entity_id" value="<?php echo $marked_entity_id; ?>">
                <input type="file" name="file" id="file">
                <input type="submit" name="submit" value="Submit">
            </form>
            <?php } ?>

<script type="text/javascript">
    $(".delete").click(function(){
        var id = $(this).parents("tr").attr("id");
        var file_id = $(this).parents("tr").attr("file_id");
        if(confirm('Are you sure to delete this record ?')) {
            $.ajax({
                url: 'deletemarked_files.php',
                type: 'GET',
                data: {id: id,file_id:file_id},
                error: function() {
                  alert('Something is wrong, couldn\'t delete record');
                },
                success: function(data) {
                    $("#" + id).remove();
                    alert("Record delete successfully.");  
                }
            });
        }
    });
</script>

<?php include "templates/footer.php"; ?>
