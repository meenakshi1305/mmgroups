<?php

require 'dbconfig.php';

if (count($_FILES) > 0) {
    if (is_uploaded_file($_FILES['userImage']['tmp_name'])) {
        $imgData = file_get_contents($_FILES['userImage']['tmp_name']);
        $imgType = $_FILES['userImage']['type'];
        $sql = "INSERT INTO tbl_image(imageType ,imageData) VALUES(?, ?)";
        $statement = $con->prepare($sql);
        $statement->bind_param('ss', $imgType, $imgData);
        $current_id = $statement->execute() or die("<b>Error:</b> Problem on Image Insert<br/>" . mysqli_connect_error());
    }
}
?>
<HTML>
<HEAD>
<TITLE>Upload Image to MySQL BLOB</TITLE>
<link href="css/form.css" rel="stylesheet" type="text/css" />
<link href="css/style.css" rel="stylesheet" type="text/css" />
<style>
.image-gallery {
    text-align:center;
}
.image-gallery img {
    padding: 3px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.5);
    border: 1px solid #FFFFFF;
    border-radius: 4px;
    margin: 20px;
}
</style>
</HEAD>
<BODY>
    <form name="frmImage" enctype="multipart/form-data" action=""
        method="post">
        <div class="phppot-container tile-container">
            <label>Upload Image File:</label>
            <div class="row">
                <input name="userImage" type="file" class="full-width" />
            </div>
            <div class="row">
                <input type="submit" value="Submit" />
            </div>
        </div>
        <div class="image-gallery">
            <?php  '/list.php'; ?>
        </div>
        
    </form>
</BODY>
</HTML>