<?php 
require_once 'config.php';

$displayList = $conn->query("SELECT * FROM `tblmajor`");
if (mysqli_num_rows($displayList) > 0) {
    while ($row = mysqli_fetch_assoc($displayList)) {
        $majorNameKh = $row["major_name_kh"];
    ?>
            <div class="subjectLink">
                <h2><a href=""><?php echo (string)$majorNameKh ?></a></h2>
            </div>
<?php
}
} else {
    echo "<div class='noContentAlert'> Content is unavailable <div>";
}