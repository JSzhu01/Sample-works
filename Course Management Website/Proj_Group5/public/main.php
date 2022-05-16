<?php 
require_once "../common.php";
maybe_session_start();
ensure_logged_in();
include "templates/header.php"; 

if (isset($_SESSION["current_user"])) {
  $is_admin = (get_priv() == 0) ? True : False;
  $is_instructor = (get_priv() == 1) ? True : False;
  $user = $_SESSION["current_user"];
  echo '<h2> Landing Page </h2>';
  echo '<ul>';
    if ($user->privilege == 0 || $user->privilege == 1) {
      echo '<li>
              <a href="create_user.php"><strong>Create A New User</strong></a>
            </li>';
    }
    if ($user->privilege == 0 || $user->privilege == 1) {
      echo '<li>
        <a href="view_users.php"><strong>Manage All Users</strong></a>
      </li>';
    } 
    if(get_priv() == 0){
    echo'
    <li>
      <a href="discussions.php"><strong>View All Discussion Forums</strong></a>
    </li>'; 

    echo'
    <li>
      <a href="marked_entities.php"><strong>View All Marked Entity Files</strong></a>
    </li>';
    }

    if ($user->privilege == 0) {
      // echo'
      // <li>
      //   <a href="instructors_list.php"><strong>Assign An Instructors</strong></a>
      // </li>';  
    }
    
    if ($user->privilege == 1) {
      echo '
      <li>
        <a href="modifyGroup.php"><strong>Manage Courses</strong></a>
      </li>';
    }else{
      echo '
      <li>
        <a href="course_list.php"><strong>Manage Courses</strong></a>
      </li>';
    }
    if($user -> privilege == 3){
      echo '
      <li>
        <a href="past_marked_entities.php"><strong>View marked entites from group that you left.</strong></a>
      </li>';
    }

    echo '
    <li>
      <a href="meetings.php"><strong>Schedule Meetings</strong></a> 
    </li>

    </ul>';
?>
<?php
    $database = getConnection();
    if($is_admin){
      $sql = 'SELECT section_id,course_num,section FROM course_section';
    }
    else if($is_instructor){
      $sql = 'SELECT section_id,course_num,section FROM course_section WHERE user_id = '.$_SESSION['current_user_id'];
    }
    else{
      $sql = 'SELECT DISTINCT section_id,course_num,section FROM student_me_info WHERE user_id = '.$_SESSION['current_user_id'];
    }
    $q = $database ->prepare($sql);
    $q -> execute();
    $results = $q->fetchAll();
?>
<!-- Dynamic table for selection -->
<h4 style="width:100%; text-align:center;"> Quick Navigation </h4>
<div class = form style="width:100%; text-align:center;">
<form action = "navigation.php" method = "post">

    <select required id = "course_section" name = "course_section">
        <option value="">Select Course Section</option>
        <?php 
          if($results && count($results)){
            foreach($results as $row){
              echo '<option value="'.$row['section_id'].'">'.$row['course_num'].' '.$row['section'].'</option>'; 
            }
          }
          else{
            echo '<option value="">No courses avaliable for you.</option>'; 
          }
        ?>
    </select>
    <br></br>
    <!-- ME dropdown -->
    <select id="marked_entities" name="marked_entities">
        <option value="">Select Mark Entity</option>
    </select>
    <br></br>
    <!-- Group dropdown -->
    <?php //if($is_admin or $is_instructor){ ?>
    <select id="course_group" name="course group">
     <?php//} else {?>
      <!-- <select required id="course_group" name="course group">
        <?php //} ?> -->
        <option value="">Select Course group</option>
    </select>
    <br></br>
    <select id="page_type" name="page_type">
        <option value="">Select Discussion or Marked Entity File Page</option>
        <option value="Discussion">Discussions Page</option>
        <option value="File"> File Page </option>
    </select>
    <br></br>
    <input type="submit" name="submit" value="Submit"/>
  </form>
  </div>
<?php } ?>
<script>
  $(document).ready(function(){
    $('#course_section').on('change', function(){
        console.log("course_section changed")
        var sid = $(this).val();
        if(sid){
            $.ajax({
                type:'POST',
                url:'ajaxData.php',
                data:'sid='+sid,
                success:function(html){
                    $('#marked_entities').html(html);
                    $('#course_group').html('<option value="">Select Marked Entities first</option>'); 
                }
            }); 
        }else{
            $('#marked_entities').html('<option value="">Select Course Section first</option>');
            $('#course_group').html('<option value="">Select Mark Entities first</option>'); 
        }
    });
    $('#marked_entities').on('change', function(){
        var me_id = $(this).val().split(',')[0];
        if(me_id){
            $.ajax({
                type:'POST',
                url:'ajaxData.php',
                data:'me_id='+me_id,
                success:function(html){
                    $('#course_group').html(html);
                }
            }); 
        }else{
            $('#course_group').html('<option value="">Select MarkEntities first</option>'); 
        }
    });


  });
  </script>
<?php include "templates/footer.php"; 
?>