<?php
require_once 'config.php';
$levelID = '';
$levelName = '';
$levelTime = '';
$levelDescription = '';
$editID = '';
if (isset($_GET['delete'])) {
    $deleteID = (int)$_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM `tbllevel` WHERE level_id = ?");
    if ($stmt) {
        $stmt->bind_param('i', $deleteID);
        $stmt->execute();
        $stmt->close();
    }
    if (isset($_GET['ajax']) && $_GET['ajax'] == '1') {
        header('Content-Type: application/json');
        echo json_encode(['success' => true]);
        exit;
    }
    header('Location: index.php');
    exit;
}

?>
<html lang="en">
<style>

</style>
<body>
    <div class="container" style="width: 100%;
        margin: 0 auto;
        padding: 20px;
        background-color: #333;">
        <section class="contentList">
            <div class="listContainer">
                <table class="contentTable">
                    <thead>
                        <th>ID</th>
                        <th>Level Name</th>
                        <th>Level Time</th>
                        <th>Level Description</th>
                        <th>Action</th>
                    </thead>
                    <tbody class="contentTableBody">
                        <?php
                        $ID = 1;
                        $displayList = $conn->query("SELECT * FROM `tbllevel`");
                        if (mysqli_num_rows($displayList) > 0) {
                            while ($row = mysqli_fetch_assoc($displayList)) {
                                $levelName = $row["level_name"];
                                $levelTime = $row["timetostudy"];
                                $levelDescription = $row["description"];
                        ?>
                                <tr>
                                    <td><?php echo $ID ?></td>
                                    <td><span onclick="loadIntoSubjectSelect('majorList.php?level_id=<?php echo $row['level_id'] ?>'); loadData('addMajorForm.php?level_id_pre=<?php echo $row['level_id'] ?>');" style="cursor:pointer; color:#007bff; text-decoration:underline;"><?php echo $levelName ?></span></td>
                                    <td><?php echo $levelTime ?></td>
                                    <td><?php echo $levelDescription ?></td>
                                    <td>
                                        <a href="levelList.php?delete=<?php echo $row['level_id'] ?>" class="deletecontent"><i>Delete</i></a>
                                        <a href="#" onclick="loadData('addLevelForm.php?edit_id=<?php echo $row['level_id'] ?>')" class="editcontent"><i>Edit</i></a>
                                    </td>
                                </tr>
                        <?php
                                $ID++;
                            }
                        } ?>
                    </tbody>
                </table>
            </div>
        </section>
        <div class="button">
            <button type="button" onclick="closeForm(); loadData('addLevelForm.php')">Add More Level</button>
            <button type="button" onclick="closeForm()">Close</button>
        </div>

    </div>
</body>

</html>