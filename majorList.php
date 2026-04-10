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
<style>
    .contentTable {
        width: 100%;
        border-collapse: collapse;
    }

    .contentTable th,
    .contentTable td {
        border: 1px solid #ddd;
        padding: 8px;
    }

    .contentTable th, .contentTable td {
        background-color: #f2f2f2;
        text-align: left;
    }

    .deletecontent,
    .editcontent {
        color: #007bff;
        text-decoration: none;
        margin-right: 10px;
    }

    .deletecontent:hover,
    .editcontent:hover {
        text-decoration: underline;
    }
    .button button {
        padding: 10px;
        background-color: #007bff;
        width: 100%;
        color: white;
        border: none;
        cursor: pointer;
    }
    .button button:hover {
        background-color: #0056b3;
    }
</style>

<div class="container">
    <section class="contentList">
        <div class="listContainer">
            <table class="contentTable">
                <thead>
                    <th>ID</th>
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
                                <td><?php echo $ID ?></td>
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
                            $ID++;
                        }
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </section>
    <div class="button">
        <button type="button" onclick="closeForm(); loadData('addMajorForm.php<?php echo $levelId > 0 ? '?level_id_pre=' . $levelId : '' ?>')">Add More Major</button>
    </div>
</div>