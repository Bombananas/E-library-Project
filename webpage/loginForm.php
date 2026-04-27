<?php 
require_once 'config.php';

if (isset($_POST['loginSubmit'])) {
    $userName = $_POST['userName'] ?? '';
    $password = $_POST['password'] ?? '';

    if (empty($userName) || empty($password)) {
        echo 'Username and password cannot be empty.';
        header('Location: index.php');
        exit;
    }

    // Query ONLY by username
    $loginQuery = "SELECT * FROM userinfo WHERE UserName = ?";
    $stmt = $conn->prepare($loginQuery);
    $stmt->bind_param('s', $userName);
    $stmt->execute();

    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Verify password
        if (password_verify($password, $user['Password'])) {
            $userRole = $user['Role'];
            session_start();
            $_SESSION['user_role'] = $userRole;
            header('Location: index.php');
            exit;
        } else {
            echo 'Invalid username or password.';
        }
    } else {
        echo 'Invalid username or password.';
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
        <form class="form" action="loginForm.php" method="post">
            <label for="userName">Username
                <input type="text" id="userName" name="userName" placeholder="Username" value="" required>
            </label>
            <label for="password">Password
                <input type="password" id="password" name="password" placeholder="Password" required>
            </label>
            <button type="submit" name="loginSubmit<?php echo $userRole = $_SESSION['user_role'] ?? null; ?>">Login</button>
            <button type="button" onclick="closeForm()">Close</button>
        </form>
    </div>
</body>