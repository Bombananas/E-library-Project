<?php
require_once 'config.php';
$levelId = isset($_GET['level_id']) ? (int)$_GET['level_id'] : 0;
$majors = [];
if (isset($_GET['delete'])) {
    $deleteID = (int)$_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM `tblmajor` WHERE major_id = ?");
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
if ($levelId > 0) {
    $stmt = $conn->prepare("SELECT * FROM tblmajor WHERE level_id = ?");
    $stmt->bind_param('i', $levelId);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $majors[] = $row;
    }
    $stmt->close();
}
?>
<div class="container" style="width: 100%;
        margin: 0 auto;
        padding: 20px;
        background-color: #333;">
    <section class="contentList">
        <div class="listContainer">
            <table class="contentTable">
                <thead>
                    <th>Major Name (KH)</th>
                    <th>Major Name (EN)</th>
                    <th>Year of Study</th>
                    <th>Description</th>
                    <th>Action</th>
                </thead>
                <tbody class="contentTableBody">
                    <?php
                    if (empty($majors)) {
                        echo '<tr><td colspan="6">No majors found for this level.</td></tr>';
                    } else {
                        $ID = 1;
                        foreach ($majors as $row) {
                    ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row["major_name_kh"]) ?></td>
                                <td><?php echo htmlspecialchars($row["major_name_en"]) ?></td>
                                <td><?php echo htmlspecialchars($row["year_stardy"]) ?></td>
                                <td><?php echo htmlspecialchars($row["description"]) ?></td>
                                <td>
                                    <a href="#" onclick="deleteMajor(<?php echo $row['major_id'] ?>, <?php echo $levelId ?>)">Delete</a>
                                    <a href="#" onclick="editMajor(<?php echo $row['major_id'] ?>)">Edit</a>
                                </td>
                            </tr>

                    <?php
                        }
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </section>
    <div class="button">
        <button type="button" onclick="closeForm(); loadData('addMajorForm.php<?php echo $levelId > 0 ? '?level_id_pre=' . $levelId : '' ?>')">Add More Major</button>
        <button type="button" onclick="closeForm();">Close</button>
    </div>
</div>