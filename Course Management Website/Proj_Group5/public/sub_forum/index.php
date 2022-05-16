<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Discussion Forum</title>
        <link rel="stylesheet" href="comments.css">
        <!--<script src="jquery-1.9.1.min.js"></script>-->
		<script src="https://code.jquery.com/jquery-3.6.0.min.js" crossorigin="anonymous"></script>
        <!--<script src="jquery-ui-1.10.3-custom.min.js"></script>-->
		<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js" crossorigin="anonymous"></script>
        <!--<script src="jquery-migrate-1.2.1.js"></script>-->
        <!--<script src="jquery.blockUI.js"></script>-->
		<script src="jquery.blockUI-2.70.0.js"></script>
        <script src="comments_blog.js"></script>
    </head>
    <body>
        <?php
        require("comments.php");
        require("topic.php");
        include("header_forum.php");
        ?> 
            <a href= "<?php echo $_SERVER["HTTP_REFERER"]?>"> Back to discussion page</a>

                <div style="width: 800px;">
    <div id = "topic_content">
    <div id="topic_form_wrapper">
    <div>
        <h2> Author : <?php echo $topic_author;?></h2></div>
    <div><?php echo $topic_content;?></div>
    </div>  
</div> 
</div>
        <div style="width: 600px;">
        <?php if(!$is_old and !$is_expired){ ?>
            <div id="comment_wrapper">
                <div id="comment_form_wrapper">
                    <div id="comment_resp"></div>
                    <h4>Leave a comment<a href="javascript:void(0);" id="cancel-comment-reply-link">Cancel Reply</a></h4>
                    <form id="comment_form" name="comment_form" action="" method="post" enctype="multipart/form-data">
                        <div>
                            Comment<textarea name="comment_text" id="comment_text" rows="6"></textarea>
                        </div>
                        <div>
                            <input type="hidden" name="page_id" id="page_id" value= <?php echo $id;?> />   
                            <input type="hidden" name="reply_id" id="reply_id" value=""/>
                            <input type="hidden" name="group_id" id="group_id" value="<?php echo isset($_GET['gid']) ?$_GET['gid']:($_GET['iid']) ;?>">
                            <input type="hidden" name="course_id" id="course_id" value=<?php echo $_GET['me_id'];?>>
                            <input type="hidden" name="reply_author" id="reply_author" value= <?php echo $_SESSION["current_user"]-> first_name;?> />
                            <input type="hidden" name="depth_level" id="depth_level" value=""/>
                            <input type="file" name="file" id="file">
                            <input type="submit" name="comment_submit" id="comment_submit" value="Post Comment" class="button"/>
                        </div>
                    </form>
                </div>
                <?php } ?>
                <?php
                echo $comments;
                if($is_old or $is_expired){ 
                ?>
                 <script>
  function removeElt() {
var elements = document.getElementsByClassName("reply_button");
while (elements.length > 0) {
  elements[0].parentNode.removeChild(elements[0]);
}
}
    removeElt();
 </script>
                <?php } ?>
                <!-- delete script for admin -->
                <script type="text/javascript">
    $(".delete").click(function(){
        var file_index = $(this).parents("span").attr("file_index");
        var file_id = $(this).parents("span").attr("file_id");
        var comment_id = $(this).parents("li").attr("comment_id");
        if(confirm('Are you sure to delete this file ?')) {
            $.ajax({
                url: 'deletecomment_files.php',
                type: 'GET',
                data: {file_index: file_index,file_id:file_id,comment_id:comment_id},
                error: function() {
                  alert('Something is wrong, couldn\'t delete record');
                },
                success: function(data) {
                    $('span[class="comment_file"][file_index ="'+file_index+'"]').remove();
                    alert("Record delete successfully.");  
                }
            });
        }
    });
</script>
            </div>
        </div>
    </body>
</html>