<?php 
require_once "../common.php";
ensure_logged_in();
include "templates/header.php"; 

echo '<a href="main.php">Back to Home</a><br></br>';
if (isset($_SESSION["current_user"])) {
    if(get_priv()!= 3){
        die("You are not a student!");
    }
    $database = getConnection();
    $sql = 'SELECT DISTINCT 
    l.me_id,cs.course_num,cs.section 
    FROM leftMarkEntity AS l 
    LEFT OUTER JOIN course_section AS cs 
    ON
    l.section_id = cs.section_id WHERE l.me_id NOT IN(
    SELECT DISTINCT
        me_id
    FROM
        student_me_info AS smi
    WHERE
        smi.user_id ='.$_SESSION["current_user_id"].')';

    $q = $database -> prepare($sql);
    $q -> execute();
    $result = $q -> fetchAll();
    if($result && count($result)){ ?>
        <h2>See the mark entity where you left the group</h2>

        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Course Num</th>
                    <th>Section</th>
                    <th>Title</th>
                    <th>Link</th>
                </tr>
            </thead>
            <tbody>
            <?php $count = 1; foreach ($result as $row) { ?>
                <tr>
                <td><?php echo escape($count); ?></td>
                <td><?php echo escape($row['course_num']);  ?> </td>
                <td><?php echo escape($row['section']);  ?> </td>
                <td style="text-align:center"><?php 
                $query = "SELECT title FROM marked_entities WHERE id = ".$row['me_id'];
                $p = $database -> prepare($query);
                $p -> execute();
                $title = $p -> fetch();
                echo escape($title['title']);  ?> </td>
                <td><?php echo '<a href = '.escape("marked_entity.php?id=".$row['me_id']).' > Go to Mark Entity Page</a>';  ?> </td>
            </tr>
        <?php $count++;
    }  ?>
                </tbody>
                </table>
                <?php } else{
                    echo "<blockquote>No mark entity where you left the group exists.</blockquote>"; 
                }
        ?>


   <?php } ?>