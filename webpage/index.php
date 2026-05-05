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
        <div class="navbar" >
            <div class="logo" style="display: flex; flex-direction: row; align-items: center; gap: 10px;">
                <img src="image/schoollogo.png" alt="School Logo" style="width: 50px;">
                <h1>NPIT E-Library</h1>
            </div>
            <div class="navLinks">
                <?php if ($userRole == null): ?>
                    <button type="button" onclick="closeForm(); loadData('loginForm.php')">Login</button>
                <?php endif; ?>
                <?php if ($userRole != null): ?>
                    <button type="button" onclick="window.location.href='logout.php'">Logout</button>
                <?php endif; ?>
                <?php if ($userRole == 'Admin'): ?>
                    <button type="button" onclick="closeForm(); loadData('registerForm.php')">Register</button>
                    <?php endif; ?>
            </div>
        </div>
        <div class="bannerContainer"></div>
    </header>
    <main class="mainBody">
        <div class="contentBody">
            <aside class="sideBar">
                <?php if ($userRole == 'Admin'): ?>
                <div class="seeLevelList">
                    <button type="button" onclick="loadData('levelList.php')">See The Full List Of Level</button>
                </div>
                <?php endif; ?>
                <?php
                include("leveldata.php");
                ?>
                <?php if ($userRole == 'Admin'): ?>
                <div class="seeLevelList">
                    <button type="button" onclick="closeForm(); loadData('addLevelForm.php')">Add More Level</button>
                </div>
                <?php endif; ?>
            </aside>
            <section class="mainContent">
                <div class="subjectSelect"></div>
                <article class="contentDisplay">
                    <div class="button">
                        <button type="button" id="fullListBtn" style="display: none;" onclick=" loadData('bookList.php?major_id=' + window.selectedMajorId + '');">See The Full List Of Books</button>
                        <button type="button" id="addBookBtn" class="addBookButton" style="display: none;" onclick="loadAddBookFormModal()">Add Book</button>
                    </div>
                    <?php
                    include("bookList.php");
                    ?>
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