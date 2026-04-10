<?php
require_once 'config.php';

$displayList = $conn->query("SELECT * FROM `tbllevel`");
if (mysqli_num_rows($displayList) > 0) {
    while ($row = mysqli_fetch_assoc($displayList)) {
        $levelName = $row["level_name"];
    ?>
            <div class="levelLink">
                <h2><a href="javascript:void(0)" onclick="loadIntoSubjectSelect('majorList.php?level_id=<?php echo $row['level_id'] ?>')"><?php echo (string)$levelName ?></a></h2>
            </div>
<?php
}
} else {
    echo "<div class='noContentAlert'> Content is unavailable <div>";
}
?>
<style>
    .levelLink h2 a {
        color: #007bff;
        text-decoration: none;
    }

    .levelLink h2 a:hover {
        text-decoration: underline;
    }
    .levelLink h2 a:active {
        color: #333;
    }
</style>