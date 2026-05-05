<?php
require_once 'config.php';
$majorId = isset($_GET['major_id']) ? (int)$_GET['major_id'] : null;
$books = [];
if ($majorId !== null) {
    $stmt = $conn->prepare("SELECT * FROM tblbook WHERE major_id = ?");
    $stmt->bind_param('i', $majorId);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $books[] = $row;
    }
    $stmt->close();
    $stmt = $conn->prepare("SELECT * FROM tblmajor WHERE major_id = ?");
    $stmt->bind_param('i', $majorId);
    $stmt->execute();
    $majorNameResult = $stmt->get_result();
    $majorNameRow = $majorNameResult->fetch_assoc();
    $stmt->close();
}
?>
<style>
    .bookListContainer {
        padding: 20px;
        background-color: #f5f5f5;
        border-radius: 8px;
        max-width: 100%;
        margin: 0 auto;
        cursor: pointer;
    }

    .bookList {
        list-style-type: none;
        padding: 0;
    }

    .bookItem {
        display: flex;
        align-items: flex-start;
        margin-bottom: 20px;
        background-color: white;
        padding: 15px;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .bookCover {
        width: 120px;
        height: auto;
        margin-right: 20px;
        border-radius: 4px;
    }

    .bookItem h3 {
        margin-top: 0;
    }
</style>
<div class="bookListContainer">
    <?php if (count($books) > 0): ?>
        <h2><?php echo htmlspecialchars($majorNameRow['major_name_kh'] . ' / ' . $majorNameRow['major_name_en']); ?></h2>
        <ul class="bookList">
            <?php foreach ($books as $book): ?>
                <li class="bookItem">
                    <img src="uploads/covers/<?php echo htmlspecialchars($book['book_cover']); ?>" alt="Book Cover" class="bookCover">
                    <div class="bookInfomation">
                        <h3><?php echo htmlspecialchars($book['book_name']); ?></h3>
                        <p>Author: <?php echo htmlspecialchars($book['book_author']); ?></p>
                        <p>Book Description: <?php echo htmlspecialchars($book['description']); ?></p>
                    </div>
                    <div class="actionBtn">
                        <a href="#" onclick="loadData('addBookForm.php?edit_id=' + <?php echo $book['book_id']; ?>); return false;">edit</a>
                        <a href="addBookForm.php?delete_id=<?php echo $book['book_id']; ?>" onclick="return confirm('Are you sure you want to delete this book?');">delete</a>
                    </div>
                </li>
            <?php endforeach; ?>
            <button type="button" onclick="closeForm()">Close</button>
        </ul>
    <?php else: ?>
        <p>No books found for this major.</p>
    <?php endif; ?>