<?php
require_once 'config.php';
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json; charset=UTF-8');
function messages(string $message)
{
    echo json_encode(['message' => $message]);
}

if (isset($_GET['action']) && $_GET['action'] === 'submit-levelContent') {
    $levelName = trim($_POST['levelName']);
    $levelDuration = trim($_POST['levelDuration']);
    $levelDescription = trim($_POST['levelDescription']);
    $stmt = $conn->prepare("INSERT INTO `tbllevel` (`level_name`, `timetostudy`, `description`) VALUES (?, ?, ?)");
    if ($stmt) {
        $stmt->bind_param('sss', $levelName, $levelDuration, $levelDescription);
        if ($stmt->execute()) {
            messages('level add successfully');
        } else {
            messages('An Error has occur Insertion');
        }
        $stmt->close();
    } else {
        messages('An Error has occur');
    }
}
if (isset($_GET['action']) && $_GET['action'] === 'delete-levelContent') {
    $levelId = $_GET['id'];
    $stmt = $conn->prepare('DELETE FROM `tbllevel` WHERE level_id= ?');
    if ($stmt) {
        $stmt->bind_param('i', $levelId);
        if ($stmt->execute()) {
            messages('level deleted successfully');
        } else {
            messages('An Error has occur During Delete');
        }
        $stmt->close();
    } else {
        messages('An Error Has occur');
    }
}
