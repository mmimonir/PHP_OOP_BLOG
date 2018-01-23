<?php include 'inc/header.php' ?>
<?php include 'inc/sidebar.php' ?>

<?php 
if (!isset($_GET['editpostid']) || $_GET['editpostid'] == null) {
    echo "<script>window.location = 'postlist.php'; </script>";
    // header("Location:catlist.php");
} else {
    $postid = $_GET['editpostid'];
}

 ?>

        <div class="grid_10">
        
            <div class="box round first grid">
                <h2>Update Post</h2>
<?php 
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $tittle = mysqli_real_escape_string($db->link, $_POST['tittle']);
    $cat    = mysqli_real_escape_string($db->link, $_POST['cat']);
    $body   = mysqli_real_escape_string($db->link, $_POST['body']);
    $tags   = mysqli_real_escape_string($db->link, $_POST['tags']);
    $author = mysqli_real_escape_string($db->link, $_POST['author']);

    $permited       = array('jpg', 'jpeg', 'png', 'gif');
    $file_name      = $_FILES['image']['name'];
    $file_size      = $_FILES['image']['size'];
    $file_temp      = $_FILES['image']['tmp_name'];
    $div            = explode('.', $file_name);
    $file_ext       = strtolower(end($div));
    $uinque_image   = substr(md5(time()), 0, 10) . '.' . $file_ext;
    $uploaded_image = "upload/" . $uinque_image;

    if ($tittle == "" || $cat == "" || $body == "" || $tags == "" || $author == "" || $file_name == "") {
        echo "<span class='error'>Field Must Not Be Empty.</span>";
    } elseif ($file_size > 1048576) {
        echo '<span class="error">Image Size should be less then 1MB.</span>';
    } elseif (in_array($file_ext, $permited) === false) {
        echo '<span class="error">You can upload only:-' . implode(',', $permited) . '</span>';
    } else {
        move_uploaded_file($file_temp, $uploaded_image);
        $query         = "INSERT INTO tbl_post (cat, tittle, body, image, author, tags) VALUES ('$cat', '$tittle', '$body', '$uploaded_image', '$author', '$tags')";
        $inserted_rows = $db->insert($query);
        if ($inserted_rows) {
            echo '<span class="success">New Post Added Successfully.</span>';
        } else {
            echo '<span class="error">Data Not Added.</span>';
        }
    }
}


 ?>

                <div class="block">
                <?php 

                $query = "SELECT * FROM tbl_post WHERE id='$postid' ORDER BY id DESC";
                $getpost = $db->select($query);
                if ($getpost) {
                    while ($postresult = $getpost->fetch_assoc()) {
                        ?>               
                 <form action="addpost.php" method="post" enctype="multipart/form-data">
                    <table class="form">
                       
                        <tr>
                            <td>
                                <label>Title</label>
                            </td>
                            <td>
                                <input type="text" name="tittle" value="<?php echo $postresult['tittle']; ?>" class="medium" />
                            </td>
                        </tr>
                        
                     
                        <tr>
                            <td>
                                <label>Category</label>
                            </td>
                            <td>
                                <select id="select" name="cat">
                                    <option>Select Category</option>

<?php 
$query = "SELECT * FROM tbl_category";
                        $category = $db->select($query);
                        if ($category) {
                            while ($result = $category->fetch_assoc()) {
                                ?>
                                
                                    <option 
                                    <?php if ($postresult['cat']==$result['id']) {
                                    ?>
                                        selected ="selected"
                                     <?php
                                } ?> value="<?php echo $result['id']; ?>"><?php echo $result['name']; ?></option>
             <?php
                            }
                        } ?>
                                             
                                </select>
                            </td>
                        </tr>
                   
                    
                        
                        <tr>
                            <td>
                                <label>Upload Image</label>
                            </td>
                            <td>
                                <img src="<?php echo $postresult['image']; ?>" height="80px" width="200px"><br/>
                                <input type="file" name="image" />
                            </td>
                        </tr>
                        <tr>
                            <td style="vertical-align: top; padding-top: 9px;">
                                <label>Content</label>
                            </td>
                            <td>
                                <textarea class="tinymce" name="body" colspan="10"><?php echo $postresult['body']; ?></textarea>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label>Tags</label>
                            </td>
                            <td>
                                <input type="text" name="tags" value="<?php echo $postresult['tags']; ?>" class="medium" />
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label>Author</label>
                            </td>
                            <td>
                                <input type="text" name="author" value="<?php echo $postresult['author']; ?>" class="medium" />
                            </td>
                        </tr>
                        <tr>
                            <td></td>
                            <td>
                                <input type="submit" name="submit" Value="Save" />
                            </td>
                        </tr>
                    </table>
                    </form>

                    <?php
                    }
                } ?>
                </div>
            </div>
        </div>

<!-- Load TinyMCE -->
    <script src="js/tiny-mce/jquery.tinymce.js" type="text/javascript"></script>
    <script type="text/javascript">
        $(document).ready(function () {
            setupTinyMCE();
            setDatePicker('date-picker');
            $('input[type="checkbox"]').fancybutton();
            $('input[type="radio"]').fancybutton();
        });
    </script>
        <?php include 'inc/footer.php' ?>



