<?php
require_once 'config.php';
$levelId = isset($_GET['level_id']) ? (int)$_GET['level_id'] : null;
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
if ($levelId !== null) {
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
    .filtercontainer {
        display: flex;
        gap: 12px;
        align-items: center;
        font-family: sans-serif;
        flex-direction: row;
        margin: 1em;
    }

    .pill {
        padding: 10px 25px;
        border: 1px solid #ccc;
        border-radius: 50px;
        background-color: white;
        cursor: pointer;
        font-size: 16px;
        transition: all 0.2s ease;
        margin-bottom: .2em;
    }

    .pill.active {
        background-color: black;
        color: white;
        border-color: black;
    }

    .pill:hover:not(.active) {
        background-color: #f0f0f0;
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
            <div class="filtercontainer ">
                <?php
                if (empty($majors)) {
                    echo '<tr><td colspan="6">No majors found for this level.</td></tr>';
                } else {
                    $columnCount = 0;
                    foreach ($majors as $row) {
                        if ($columnCount % 4 == 0) {
                            echo '<br>';
                        }
                ?>
                        <button class="pill"><?php echo htmlspecialchars($row["major_name_kh"]) ?> / <?php echo htmlspecialchars($row["major_name_en"]) ?></button>


                <?php
                        $columnCount++;
                    }
                }
                ?>
            </div>
        </div>
    </section>
<?php if ($levelId !== null): ?>
    <div class="button">
        <button type="button" onclick=" loadData('majorFullList.php?level_id=<?php echo $levelId ?>');">See The Full List Of Majors</button>
    </div>
<?php endif; ?>
</div>