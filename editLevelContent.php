<?php
require_once 'config.php';

/**
 * Update a level record in tbllevel.
 *
 * @param mysqli $conn
 * @param int $levelID
 * @param string $levelName
 * @param string $levelTime
 * @param string $levelDescription
 * @return bool
 */
function editLevelContent($conn, $levelID, $levelName, $levelTime, $levelDescription)
{
    $stmt = $conn->prepare("UPDATE `tbllevel` SET `level_name` = ?, `time` = ?, `description` = ? WHERE `level_id` = ?");
    if (!$stmt) {
        return false;
    }
    $stmt->bind_param('sssi', $levelName, $levelTime, $levelDescription, $levelID);
    $result = $stmt->execute();
    $stmt->close();
    return $result;
}

// Optional API endpoint handler (form POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['level_id'])) {
    $levelID = (int) $_POST['level_id'];
    $levelName = trim($_POST['level_name'] ?? '');
    $levelTime = trim($_POST['level_time'] ?? '');
    $levelDescription = trim($_POST['level_description'] ?? '');

    $success = editLevelContent($conn, $levelID, $levelName, $levelTime, $levelDescription);

    if (isset($_POST['ajax']) && $_POST['ajax'] == '1') {
        header('Content-Type: application/json');
        echo json_encode(['success' => $success]);
        exit;
    }

    if ($success) {
        header('Location: levelList.php');
        exit;
    }

    echo 'Error: Could not update level.';
}
