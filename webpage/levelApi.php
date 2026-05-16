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
        }
        $stmt->close();
    } else {
        messages('An Error has occur');
    }
}
