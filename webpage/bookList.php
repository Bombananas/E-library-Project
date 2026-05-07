<?php
require_once 'config.php';
$majorId = isset($_GET['major_id']) ? (int)$_GET['major_id'] : 18;
$levelName = '';
$stmt = $conn->prepare("SELECT level_name FROM tbllevel WHERE level_id = (SELECT level_id FROM tblmajor WHERE major_id = ?)");
$stmt->bind_param('i', $majorId);
$stmt->execute();
$levelNameResult = $stmt->get_result();
if ($levelNameRow = $levelNameResult->fetch_assoc()) {
    $levelName = $levelNameRow['level_name'];
}
$books = [];
$majorNameRow = null;
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
        max-width: 100%;
        max-height: 100%;
        margin: 0 auto;
        overflow-y: scroll;
        scrollbar-width: thin;
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
        height: 141px;
        margin-right: 20px;
        border-radius: 4px;
    }

    .bookItem h3 {
        margin-top: 0;
    }
</style>
<div class="bookListContainer">
    <?php if (count($books) > 0): ?>
        <h2><?php echo htmlspecialchars($levelName . '  :  ' . $majorNameRow['major_name_kh'] . ' / ' . $majorNameRow['major_name_en']); ?></h2>
        <ul class="bookList">
            <?php foreach ($books as $book): ?>
                <li class="bookItem" onclick="loadData('bookInfomation.php?book_id=<?php echo $book['book_id']; ?>')">
                    <img src="uploads/covers/<?php echo htmlspecialchars($book['book_cover']); ?>" alt="Book Cover" class="bookCover">
                    <div class="bookInfomation">
                        <h3><?php echo htmlspecialchars($book['book_name']); ?></h3>
                        <p>Author: <?php echo htmlspecialchars($book['book_author']); ?></p>
                        <p>Book Description: <?php echo htmlspecialchars($book['description']); ?></p>
                    </div>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>No books found for this major.</p>
    <?php endif; ?>