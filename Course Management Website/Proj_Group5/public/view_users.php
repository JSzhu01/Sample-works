<?php

require "../modules/models/user.php";
require_once "../common.php";
include "templates/header.php";

try {
    $result = User::getAll();
} catch (PDOException $error) {
    echo $sql . "<br>" . $error->getMessage();
}

$is_admin = False;
if(get_priv() !=0){
echo '<a href="index.php">Back to Home</a><br><br>';
  die("Only admin can access to user administration panel.");
}
else{
    $is_admin = True;
}
?>

<?php
if ($result && count($result)) { ?>
        <h2>Users</h2>

        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Title</th>
                    <th>Email Address</th>
                    <th>ID</th>
                    <th>Created At</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
        <?php 
            $num = 0; 
            foreach ($result as $row) {
            ?> 
            <tr id="<?php echo escape($row->id); ?>">
                <td><?php echo $num++; ?></td>
                <td><?php echo escape($row->first_name); ?></td>
                <td><?php echo escape($row->last_name); ?></td>

                <td><?php 
                    switch($row->privilege){
                        case 0:
                            echo "Admin";
                            break;
                        case 1:
                            echo "Instructor";
                            break;
                        case 2: 
                            echo "TA";
                            break;
                        default:
                            echo "Student";
                            break;
                    }
                    ; ?></td>

                <td><?php echo escape($row->email); ?></td>
                <td><?php echo escape($row->student_id); ?></td>
                <td><?php echo escape($row->created_at);  ?> </td>
                <?php if ($row-> privilege !=0){ ?>
                <td><?php echo '<a class="btn btn-default" href="edituser.php?id='.$row->id.'">Edit</a>'; ?> <a href = # class = "delete" >Delete</a> </td>
                <?php } ?>
            </tr>
        <?php } ?>
        </tbody>
    </table>
    <?php } else { ?>
        <blockquote>No users found.</blockquote>
    <?php }
?> 

<br>
<a href="index.php"> Back to Home</a>
<script type="text/javascript">
    $(".delete").click(function(){
        var id = $(this).parents("tr").attr("id");
        if(confirm('Are you sure to delete this record ?')) {
            $.ajax({
                url: 'deleteuser.php',
                type: 'GET',
                data: {id: id},
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