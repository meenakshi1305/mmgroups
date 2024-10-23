<?php

require 'dbconfig.php';

$sql = "SELECT imageId FROM tbl_image ORDER BY imageId DESC";
$stmt = $con->prepare($sql);
$stmt->execute();
$result = $stmt->get_result();
?>

<?php
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        ?>
    <?php ?>
		<img src="imageView.php?image_id=<?php echo $row["imageId"];?>">
<?php
    }
}
?>