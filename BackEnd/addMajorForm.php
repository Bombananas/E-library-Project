<?php
require_once 'config.php';
$goBackToLevel = isset($_GET['level_id_pre']) ? (int)$_GET['level_id_pre'] : 0;
echo 'Go back to Level ID: ' . ($goBackToLevel > 0 ? $goBackToLevel : 'none');
function getMajorsByLevelId($levelId) {
    global $conn;
    $stmt = $conn->prepare("SELECT * FROM tblmajor WHERE level_id = ?");
    if ($stmt) {
        $stmt->bind_param('i', $levelId);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();
        return $result;
    }
    return false;
}

$editId = isset($_GET['edit_id']) ? (int)$_GET['edit_id'] : 0;
$major = null;
if ($editId > 0) {

    $stmt = $conn->prepare("SELECT * FROM tblmajor WHERE major_id = ?");
    $stmt->bind_param('i', $editId);
    $stmt->execute();
    $result = $stmt->get_result();
    $major = $result->fetch_assoc();
    $stmt->close();   
    $getLevelId = $conn->prepare("SELECT level_id FROM tblmajor WHERE major_id = ?");
    $getLevelId->bind_param('i', $editId);
    $getLevelId->execute();
    $getLevelIdResult = $getLevelId->get_result();  
}
$levelIdPre = 0;
if (isset($_GET['level_id_pre'])) {
    $levelIdPre = (int)$_GET['level_id_pre'];
} elseif (isset($_GET['level_idpre'])) {
    $levelIdPre = (int)$_GET['level_idpre'];
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['contentSubmit'])) {
    $majorNameKh = trim($_POST['majorNameKh'] ?? '');
    $majorNameEn = trim($_POST['majorNameEn'] ?? '');
    $yearStudy = (int)($_POST['yearStudy'] ?? 0);
    $description = trim($_POST['description'] ?? '');
    $levelId = (int)($_POST['levelId'] ?? 0);

        if ($editId > 0) {
            $stmt = $conn->prepare("UPDATE tblmajor SET major_name_kh = ?, major_name_en = ?, year_stardy = ?, description = ?, level_id = ? WHERE major_id = ?");
            $stmt->bind_param('ssisii', $majorNameKh, $majorNameEn, $yearStudy, $description, $levelId, $editId);
        } else {
            $stmt = $conn->prepare("INSERT INTO `tblmajor` (`major_name_kh`, `major_name_en`, `year_stardy`, `description`, `level_id`) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param('ssisi', $majorNameKh, $majorNameEn, $yearStudy, $description, $levelId);
        }
        if ($stmt) {
            if ($stmt->execute()) {
                echo 'Success';
                header('Location: index.php');
            } else {
                echo 'Failed: ' . htmlspecialchars($stmt->error);
            }
            $stmt->close();
        } else {
            echo 'Prepare failed: ' . htmlspecialchars($conn->error);
        }

}
$levels = $conn->query("SELECT level_id, level_name FROM tbllevel");
$yearNow = date('Y');
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
    .form textarea {
        padding: 10px;
        margin-bottom: 20px;
        float: right;
        width: 250px;
    }
    .form select {
        padding: 10px;
        margin-bottom: 20px;
        float: right;
        width: 250px;
    }
</style>

<body>
    <div class="container" style="width: 100%;
        margin: 0 auto;
        padding: 20px;
        background-color: #333;">
        <form class="form" action="addMajorForm.php<?php echo $editId > 0 ? '?edit_id=' . $editId : ''; ?>" method="post">
            <label for="majorNameKh">Major Name (Khmer)
                <input type="text" id="majorNameKh" name="majorNameKh" placeholder="Major Name in Khmer" value="<?php echo htmlspecialchars($major['major_name_kh'] ?? ''); ?>" required>
            </label>
            <label for="majorNameEn">Major Name (English)
                <input type="text" id="majorNameEn" name="majorNameEn" placeholder="Major Name in English" value="<?php echo htmlspecialchars($major['major_name_en'] ?? ''); ?>" required>
            </label>
            <label for="yearStudy">Year of Study
                <input type="number" id="yearStudy" name="yearStudy" placeholder="Year of Study" min="1" value="<?php echo htmlspecialchars($major['year_stardy'] ?? $yearNow); ?>" required>
            </label>
            <label for="description">Description
                <textarea id="description" name="description" placeholder="Description" required><?php echo htmlspecialchars($major['description'] ?? ''); ?></textarea>
            </label>
            <label for="levelId" style="display: none;">Level
                <select id="levelId" name="levelId" hidden>
                    <option value="">Select Level</option>
                    <?php  while ($level = $levels->fetch_assoc()): ?>
                        <option value="<?php echo $level['level_id']; ?>" <?php echo (
                            ($major && $major['level_id'] == $level['level_id']) ||
                            (!$major && $levelIdPre > 0 && $levelIdPre == $level['level_id'])
                        ) ? 'selected' : ''; ?>><?php echo htmlspecialchars($level['level_name']); ?></option>
                    <?php endwhile; ?>
                </select>
            </label>
            <button type="submit" name="contentSubmit"><?php echo $editId > 0 ? 'Update' : 'Submit'; ?></button>
            <button type="button" onclick="closeForm();">Close</button>
            <button type="button" onclick="closeForm(); loadData('majorFullList.php?level_id=<?php echo $goBackToLevel > 0 ? $goBackToLevel : $getLevelIdResult->fetch_assoc()['level_id'] ?>')">Go Back</button>
        </form>
    </div>
</body>

</html>
