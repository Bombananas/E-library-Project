<?php
require_once 'config.php';
require_once 'editLevelContent.php';

$editId = isset($_GET['edit_id']) ? (int)$_GET['edit_id'] : 0;
$levelIdPre = isset($_GET['level_idpre']) ? (int)$_GET['level_idpre'] : 0;
$levelName = '';
$levelTime = '';
$levelDescription = '';

$fetchId = $editId > 0 ? $editId : $levelIdPre;
if ($fetchId > 0) {
    $stmt = $conn->prepare("SELECT * FROM `tbllevel` WHERE level_id = ?");
    if ($stmt) {
        $stmt->bind_param('i', $fetchId);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        if ($row) {
            $levelName = $row['level_name'];
            $levelTime = $row['timetostudy'];
            $levelDescription = $row['description'];
        }
        $stmt->close();
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['contentSubmit'])) {
    $editId = (int)($_POST['edit_id'] ?? 0);
    $levelName = trim($_POST['levelName'] ?? '');
    $levelTime = trim($_POST['levelTime'] ?? '');
    $levelDescription = trim($_POST['levelDescription'] ?? '');

    if ($levelName === '' || $levelTime === '' || $levelDescription === '') {
        header('location:index.php');
        exit;
    }

    if ($editId > 0) {
        $updated = editLevelContent($conn, $editId, $levelName, $levelTime, $levelDescription);
        if ($updated) {
            header('location:index.php');
            exit;
        }
        echo 'Update failed: ' . htmlspecialchars($conn->error);
    } else {
        $stmt = $conn->prepare("INSERT INTO `tbllevel` (`level_name`, `timetostudy`, `description`) VALUES (?, ?, ?)");
        if ($stmt) {
            $stmt->bind_param('sss', $levelName, $levelTime, $levelDescription);
            if ($stmt->execute()) {
                header('location:index.php');
                exit;
            } else {
                echo 'Insert failed: ' . htmlspecialchars($stmt->error);
            }
            $stmt->close();
        } else {
            echo 'Prepare failed: ' . htmlspecialchars($conn->error);
        }
    }
}
$now = new DateTime();
$now->setTimezone(new DateTimeZone('Asia/Phnom_Penh'));
$currentTime =  $now->format('H:i');
?>
<html lang="en">
<style>
    .form {
        display: flex;
        flex-direction: column;
    }

    .form label {
        margin-bottom: 10px;
    }

    .form input {   
        padding: 10px;
        margin-bottom: 20px;
    }

    .form button {
        padding: 10px;
        background-color: #007bff;
        color: white;
        border: none;
        cursor: pointer;
    }
    .form button:hover {
        background-color: #0056b3;
    }
    .form input {
        float: right;
        width: 250px;
    }
    .form label {
        display: flex;
        justify-content: space-between;
        align-items: center;
        color: white;
    }
</style>

<body>
    <div class="container" style="width: 100%;
        margin: 0 auto;
        padding: 20px;
        background-color: #333;">
        <form class="form" action="addLevelForm.php<?php echo $editId > 0 ? '?edit_id=' . $editId : ($levelIdPre > 0 ? '?level_idpre=' . $levelIdPre : ''); ?>" method="post">
            <input type="hidden" name="edit_id" value="<?php echo $editId; ?>">
            <input type="hidden" name="level_idpre" value="<?php echo $levelIdPre; ?>">
            <label for="levelName">Level Name
                <input type="text" id="levelName" name="levelName" placeholder="Level Name" value="<?php echo htmlspecialchars($levelName); ?>" required>
            </label>
            <label for="Time to Study">Time to Study
                <input type="number" id="levelTime" name="levelTime" value="" placeholder="Level Time">
            </label>
            <label for="levelDescription">Level Description
                <input type="text" id="levelDescription" name="levelDescription" placeholder="Level Description" value="<?php echo htmlspecialchars($levelDescription); ?>" required>
            </label>
            <button type="submit" name="contentSubmit"><?php echo $editId > 0 ? 'Update' : 'Submit'; ?></button>
            <button type="button" onclick="closeForm()">Close</button>
            <button type="button" onclick="closeForm(); loadData('levelList.php')">Go Back</button>
        </form>
    </div>
</body>

</html>