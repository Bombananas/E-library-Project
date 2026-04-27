<?php
require_once 'config.php';
$userName = $_POST['userName'] ?? '';
$password = password_hash($_POST['password'] ?? '', PASSWORD_DEFAULT);
$userNameQury = "SELECT * FROM tbluser WHERE user_name = ?";
$stmt = $conn->prepare($userNameQury);
$stmt->bind_param('s', $userName);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows > 0) {
    echo 'Username already exists.';
    exit;
}
$registerQuery = "INSERT INTO tbluser (user_name, user_password) VALUES (?, ?)";
if(isset($_POST['registerSubmit'])) {
if (empty($userName) || empty($password)) {
    echo 'Username and password cannot be empty.';
    exit;
}
$stmt = $conn->prepare($registerQuery);
$stmt->bind_param('ss', $userName, $password);
if ($stmt->execute()) {
    echo 'Registration successful. You can now log in.';
    header('Location: index.php');
    exit;
} else {
    echo 'Registration failed: ' . htmlspecialchars($stmt->error);
}}
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
        <form class="form" action="registerForm.php" method="post">
            <label for="userName">Username
                <input type="text" id="userName" name="userName" placeholder="Username" value="<?php echo htmlspecialchars($userName); ?>" required>
            </label>
            <label for="password">Password
                <input type="password" id="password" name="password" placeholder="Password" required>
            </label>
            <button type="submit" name="registerSubmit">Register</button>
            <button type="button" onclick="closeForm()">Close</button>
        </form>
    </div>
</body>