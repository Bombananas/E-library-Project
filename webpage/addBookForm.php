<?php
require_once 'config.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
    $_SESSION['error'] = '';
    $_SESSION['success'] = '';
}
ob_start();

function sanitizeFileName($filename) {
    return preg_replace('/[^A-Za-z0-9._-]/', '_', basename($filename));
}

$editId = isset($_GET['edit_id']) ? (int)$_GET['edit_id'] : 0;
$deleteId = isset($_GET['delete_id']) ? (int)$_GET['delete_id'] : 0;
$major = null;
$book = null;

if ($editId > 0) {
    $stmt = $conn->prepare("SELECT * FROM tblbook WHERE book_id = ?");
    $stmt->bind_param('i', $editId);
    $stmt->execute();
    $book = $stmt->get_result()->fetch_assoc();
    $stmt->close();
}

?>
<html lang="en">
<style>
    #bookFormModal {
        display: flex;
        position: fixed;
        z-index: 2000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.6);
        justify-content: center;
        align-items: center;
    }
    .form-content {
        background-color: #333;
        padding: 30px;
        border-radius: 8px;
        width: 90%;
        max-width: 600px;
        max-height: 90vh;
        overflow-y: auto;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
    }

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
    <div id="bookFormModal">
        <div class="form-content">
            <form class="form" action="addBookForm.php<?php echo $editId > 0 ? '?edit_id=' . $editId : ''; ?>" method="post" enctype="multipart/form-data">
                <label for="bookName">Book Name
                    <input type="text" id="bookName" name="bookName" placeholder="Book Name " value="<?php echo htmlspecialchars($book['book_name'] ?? ''); ?>" required>
                </label>
                <label for="bookAuthor">Book Author
                    <input type="text" id="bookAuthor" name="bookAuthor" placeholder="Book Author" value="<?php echo htmlspecialchars($book['book_author'] ?? ''); ?>" required>
                </label>
                <label for="description">Description
                    <textarea id="description" name="description" placeholder="Description" required><?php echo htmlspecialchars($book['description'] ?? ''); ?></textarea>
                </label>
                <label for="uploadBookFile">Upload Book File
                    <input type="file" id="uploadBookFile" name="uploadBookFile" <?php echo $editId > 0 ? '' : 'required'; ?> accept="application/pdf, .pdf">
                </label>
                <label for="uploadBookCover">Upload Book Cover
                    <input type="file" id="uploadBookCover" name="uploadBookCover" <?php echo $editId > 0 ? '' : 'required'; ?> accept="image/jpeg, image/png, image/webp, .jpg, .jpeg, .png, .webp">
                </label>
                <label for="uploadVideoFile">Upload Video File
                    <input type="file" id="uploadVideoFile" name="uploadVideoFile" accept="video/mp4, video/webm, .mp4, .webm">
                </label>
                <label for="majorId" style="display: none;">Major
                    <input hidden type="text" id="majorId" name="majorId" placeholder="Major ID" value="<?php echo htmlspecialchars($book['major_id'] ?? 0); ?>">
                </label>
                <button type="submit" name="contentSubmit"><?php echo $editId > 0 ? 'Update' : 'Submit'; ?></button>
                <button type="button" onclick="closeForm()">Close</button>
            </form>
        </div>
    </div>
</body>
</html>
<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['contentSubmit'])) {
    $bookName = trim($_POST['bookName'] ?? '');
    $majorId = trim($_POST['majorId'] ?? '');
    $stmt = $conn->prepare("SELECT * FROM tblbook WHERE book_name = ? AND major_id = ?");
    $stmt->bind_param('si', $bookName,$majorId);
    $stmt->execute();
    $result = $stmt->get_result();
    $existingBook = $result->fetch_assoc();
    if ($existingBook && $existingBook['book_name'] === $bookName) {
        $_SESSION['error'] = "A book with this name already exists. Please choose a different name.";
        header('Location: index.php');
        exit;
    }
    $stmt->close();
    $bookAuthor = trim($_POST['bookAuthor'] ?? '');
    $description = trim($_POST['description'] ?? '');

    $bookFile = sanitizeFileName($_FILES['uploadBookFile']['name'] ?? '');
    $bookFileTmp = $_FILES['uploadBookFile']['tmp_name'] ?? '';
    $bookCover = sanitizeFileName($_FILES['uploadBookCover']['name'] ?? '');
    $bookCoverTmp = $_FILES['uploadBookCover']['tmp_name'] ?? '';
    $videoFile = sanitizeFileName($_FILES['uploadVideoFile']['name'] ?? '');
    $videoFileTmp = $_FILES['uploadVideoFile']['tmp_name'] ?? '';

    $baseUploadDir = __DIR__ . '/uploads';
    $booksFolder = $baseUploadDir . '/books/' . $bookFile;
    $coverFolder = $baseUploadDir . '/covers/' . $bookCover;
    $videoFolder = $baseUploadDir . '/videos/' . $videoFile;

    if (!is_dir($baseUploadDir . '/books')) {
        mkdir($baseUploadDir . '/books', 0755, true);
    }
    if (!is_dir($baseUploadDir . '/covers')) {
        mkdir($baseUploadDir . '/covers', 0755, true);
    }
    if (!is_dir($baseUploadDir . '/videos')) {
        mkdir($baseUploadDir . '/videos', 0755, true);
    }

    if (!empty($bookFileTmp) && move_uploaded_file($bookFileTmp, $booksFolder)) {
        $_SESSION['success'] = "Book file uploaded successfully.";
    } else if (!empty($bookFileTmp)) {
        $_SESSION['error'] = "Failed to upload book file.";
    }

    if (!empty($bookCoverTmp) && move_uploaded_file($bookCoverTmp, $coverFolder)) {
        $_SESSION['success'] = "Book cover uploaded successfully.";
    } else if (!empty($bookCoverTmp)) {
        $_SESSION['error'] = "Failed to upload book cover.";
    }

    if (!empty($videoFileTmp) && move_uploaded_file($videoFileTmp, $videoFolder)) {
        $_SESSION['success'] = "Video file uploaded successfully.";
    } else if (!empty($videoFileTmp)) {
        $_SESSION['error'] = "Failed to upload video file.";
    }

    if ($editId > 0) {
        $bookFileParam = !empty($bookFile) ? $bookFile : ($book['book_source'] ?? '');
        $bookCoverParam = !empty($bookCover) ? $bookCover : ($book['book_cover'] ?? '');
        $videoFileParam = !empty($videoFile) ? $videoFile : ($book['book_video'] ?? '');

        $stmt = $conn->prepare("UPDATE tblbook SET book_name = ?, book_author = ?, description = ?, book_source = ?, book_cover = ?, book_video = ? WHERE book_id = ?");
        $stmt->bind_param('ssssssi', $bookName, $bookAuthor, $description, $bookFileParam, $bookCoverParam, $videoFileParam, $editId);

        if ($stmt->execute()) {
            $_SESSION['success'] = "Book updated successfully.";
            header('Location: index.php');
            exit;
        } else {
            $_SESSION['error'] = "Failed to update book: " . $stmt->error;
        }
        $stmt->close();
    } else {
        $stmt = $conn->prepare("INSERT into tblbook (book_name, book_author, description, book_source, book_cover, book_video, major_id) values (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param('ssssssi', $bookName, $bookAuthor, $description, $bookFile, $bookCover, $videoFile, $majorId);

        if ($stmt->execute()) {
            $_SESSION['success'] = "Book added to database successfully.";
            header('Location: index.php');
            exit;
        } else {
            $_SESSION['error'] = "Failed to add book to database: " . $stmt->error;
        }
        $stmt->close();
    }

    if ($editId > 0) {
        $stmt = $conn->prepare("SELECT * FROM tblbook WHERE book_id = ?");
        $stmt->bind_param('i', $editId);
        $stmt->execute();
        $book = $stmt->get_result()->fetch_assoc();
        $stmt->close();
    } else {
        $book = null;
    }

    }
    if ($deleteId > 0) {
        $stmt = $conn->prepare("DELETE FROM tblbook WHERE book_id = ?");
        $stmt->bind_param('i', $deleteId);
        if ($stmt->execute()) {
            $_SESSION['success'] = "Book deleted successfully.";
            header('Location: index.php');
            exit;
        } else {
            $_SESSION['error'] = "Failed to delete book: " . $stmt->error;
        }
        $stmt->close();
    }

?>