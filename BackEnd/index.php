<?php
require_once 'config.php';
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