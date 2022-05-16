<?php include "templates/header.php";   
require_once("connection.php");

if(isset($_GET['Id']))
{
    $gid = $_GET['Id']; //group id
    $name = $_GET['name'];
    $id = $_SESSION["current_user"]-> id;
    //echo $gid;
    $query = "select u.first_name, u.last_name, ug.user_group_id, ug.group_id from users u, user_group ug
    where u.id = ug.user_id and ug.group_id = '".$gid."' ";   
    $result = mysqli_query($con,$query);  
}
else
{
    echo 'Something is going wrong, please try it again';
}
?>


                    <div >
                        <div class="title">
                            <h2><?php echo 'Group: ' .strtoupper($name);?></h2>
                            <h2> View Members</h2>
                        </div>
                        <table>
                                <thead>
                                        <tr>
                                        <th>First Name</th>
                                        <th>Last Name</th>
                                        </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    while($row = mysqli_fetch_assoc($result))
                                    {
                                        $fn = $row['first_name'];
                                        $ln = $row['last_name'];   
                                    ?>
                                <tr>
                                        <td><?php echo $fn ?></td>
                                        <td><?php echo $ln ?></td>
                                  </tr>
                                    <?php } ?>
                                </tbody> 
                        </table>
                    </div>   

<br>

<button onclick="history.go(-1);">Back </button>
