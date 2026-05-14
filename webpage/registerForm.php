<?php
require_once 'config.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$userName = '';
$passwordRaw = '';
if (isset($_POST['registerSubmit'])) {
    $userName = trim($_POST['userName'] ?? '');
    $passwordRaw = $_POST['password'] ?? '';

    if ($userName === '' || $passwordRaw === '') {
        echo 'Username and password cannot be empty.';
        exit;
    }

    $password = password_hash($passwordRaw, PASSWORD_DEFAULT);
    $userNameQuery = "SELECT * FROM tbluser WHERE user_name = ?";
    $stmt = $conn->prepare($userNameQuery);
    $stmt->bind_param('s', $userName);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        echo 'Username already exists.';
        exit;
    }

    $registerQuery = "INSERT INTO tbluser (user_name, user_password) VALUES (?, ?)";
    $stmt = $conn->prepare($registerQuery);
    $stmt->bind_param('ss', $userName, $password);
    if ($stmt->execute()) {
        header('Location: index.php');
        exit;
    } else {
        echo 'Registration failed: ' . htmlspecialchars($stmt->error);
    }
}
?>
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
        <form class="form" action="registerForm.php" onsubmit="return App.passwordComfire()" method="post">
            <label for="userName">Username
                <input type="text" id="userName" name="userName" placeholder="Username" value="<?php echo htmlspecialchars($userName); ?>" required>
            </label>
            <label for="password">Password
                <input type="password" id="password" name="password" placeholder="Password" required>
            </label>
            <label for="confirmPassword">Confirm Password
                <input type="password" id="confirmPassword" name="confirmPassword" placeholder="Confirm Password" required>
            </label>
            <button type="submit" name="registerSubmit">Register</button>
            <button type="button" onclick="closeForm()">Close</button>
        </form>
    </div>
</body>