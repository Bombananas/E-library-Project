<?php
require_once 'config.php';
$editId = isset($_GET['edit_id']) ? (int)$_GET['edit_id'] : 0;
$bookName = '';
$bookAuthor = '';
$description = '';
$bookfile = '';
$bookcover = '';
$viedofile = '';
    $stmt = $conn->prepare("INSERT into tblbook (book_name, book_author, description, book_source, book_cover, book_video, major_id) values (?, ?, ?, ?, ?, ?, ?)");
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['contentSubmit'])) {
        $bookName = trim($_POST['bookName'] ?? '');
        $bookAuthor = trim($_POST['bookAuthor'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $majorId = trim($_POST['majorId'] ?? '19');
        $bookfile = file_get_contents($_FILES['uploadBookFile']['tmp_name']) ?? '';
        $bookcover = file_get_contents($_FILES['uploadBookCover']['tmp_name']) ?? '';
        $viedofile = file_get_contents($_FILES['uploadVideoFile']['tmp_name']) ?? '';
        $stmt->bind_param('sssssss', $bookName, $bookAuthor, $description, $bookfile, $bookcover, $viedofile, $majorId);
        $stmt->execute();
        echo $stmt->error;
        $stmt->close();
        header('Location: bookList.php');
        }

?>
<html lang="en">
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
    .form textarea {
        padding: 10px;
        margin-bottom: 20px;
        float: right;
        width: 250px;
    }
    .form select {
        padding: 10px;
        margin-bottom: 20px;
        float: right;
        width: 250px;
    }
</style>

<body>
    <div class="container" style="width: 100%;
        margin: 0 auto;
        padding: 20px;
        background-color: #333;">
        <form class="form" action="addBookForm.php<?php echo $editId > 0 ? '?edit_id=' . $editId : ''; ?>" method="post">
            <label for="bookName">Book Name 
                <input type="text" id="bookName" name="bookName" placeholder="Book Name " value="<?php echo htmlspecialchars($major['major_name_kh'] ?? ''); ?>" required>
            </label>
            <label for="bookAuthor">Book Author
                 <input type="text" id="bookAuthor" name="bookAuthor" placeholder="Book Author" value="<?php echo htmlspecialchars($major['major_name_en'] ?? ''); ?>" required>
            </label>
            <label for="description">Description
                <textarea id="description" name="description" placeholder="Description" required><?php echo htmlspecialchars($major['description'] ?? ''); ?></textarea>
            </label>
            <label for="uploadBookFile">Upload Book File
                <input type="file" id="uploadBookFile" name="uploadBookFile" required>
            </label>
             <label for="uploadBookCover">Upload Book Cover
                <input type="file" id="uploadBookCover" name="uploadBookCover" required>
            </label>
            <label for="uploadVideoFile">Upload Video File
                <input type="file" id="uploadVideoFile" name="uploadVideoFile">
            </label>
            <button type="submit" name="contentSubmit"><?php echo $editId > 0 ? 'Update' : 'Submit'; ?></button>
            <button type="button" onclick="closeForm();">Close</button>
        </form>
    </div>
</body>

</html>