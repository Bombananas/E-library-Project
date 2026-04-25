<?php
require_once 'config.php';
session_start();
$userRole = $_SESSION['user_role'] ?? null;
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <header class="banner">
        <nav>
            <div class="logo">
                <h1>NPIT E-Library</h1>
            </div>
            <div class="navLinks">
                <button type="button" onclick="closeForm(); loadData('loginForm.php')">Login</button>
                <?php if ($userRole == 'admin'): ?>
                    <h1>Admin Panel</h1>
                    <?php endif; ?>
                    <button type="button" onclick="closeForm(); loadData('registerForm.php')">Register</button>
            </div>
        </nav>
        <div class="bannerContainer">
        </div>
    </header>
    <main class="mainBody">
        <div class="contentBody">
            <aside class="sideBar">
                <div class="seeLevelList">
                    <button type="button" onclick="loadData('levelList.php')">See The Full List Of Level</button>
                </div>
                <?php
                include("leveldata.php");
                ?>
                <div class="seeLevelList">
                    <button type="button" onclick="closeForm(); loadData('addLevelForm.php')">Add More Level</button>
                </div>
                
            </aside>
            <section class="mainContent">
                <div class="subjectSelect">
                    <?php
                    include("majorlist.php");
                    ?>
                </div>
                <article class="contentDisplay">
                    <div class="seeLevelList">
                    <button type="button" onclick="closeForm(); ">Add Book</button>
                </div>
                </article>
            </section>
        </div>
    </main>
    <div id="showResult" class="showResult"></div>
    <footer class="footer">
        <p>&copy; វិទ្យាស្ថានជាតិពហុបច្ចេកទេសកម្ពុជា NPIT. All rights reserved.</p>
    </footer>
    <script src="script.js"></script>
</body>

</html>