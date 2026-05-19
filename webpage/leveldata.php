<?php
require_once 'config.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$userRole = $_SESSION['user_role'] ?? null;
$displayList = $conn->query("SELECT * FROM `tbllevel`");
if (mysqli_num_rows($displayList) > 0) {
    while ($row = mysqli_fetch_assoc($displayList)) {
        $levelName = $row["level_name"];
?>
        <div class="levelLink" id="levelLink-<?php echo $row['level_id'] ?>">
            <h2><button onclick="addClass('activateLevelLink', 'levelLink-<?php echo $row['level_id'] ?>'); loadIntoSubjectSelect('majorList.php?level_id=<?php echo $row['level_id'] ?>&userrole=<?php echo $userRole ?>')"><?php echo (string)$levelName ?></button></h2>
        </div>
<?php
    }
} else {
    echo "<div class='noContentAlert'> Content is unavailable <div>";
}
?>
<style>
    .levelLink h2 button {
        padding: 10px 25px;
        min-width: 200px;
        border: 1px solid #ccc;
        border-radius: 50px;
        background-color: white;
        cursor: pointer;
        font-size: 16px;
        transition: all 0.2s ease;
        margin-bottom: .2em;
    }

    .activateLevelLink h2 button {
        background-color: black;
        color: white;
        border-color: black;
    }

    .levelLink h2 button:hover {
        text-decoration: underline;
    }

    .levelLink h2 button {
        color: #007bff;
        text-decoration: none;
    }
</style>