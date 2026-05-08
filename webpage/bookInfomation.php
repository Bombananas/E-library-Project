<?php
require_once 'config.php';
$bookId = $_GET['book_id'] ?? null;
if ($bookId) {
    $stmt = $conn->prepare("SELECT * FROM tblbook WHERE book_id = ?");
    $stmt->bind_param('i', $bookId);
    $stmt->execute();
    $bookResult = $stmt->get_result();
    if ($bookResult->num_rows > 0) {
        $book = $bookResult->fetch_assoc();
    } else {
        echo "Book not found.";
        exit;
    }
    $stmt->close();
} else {
    echo "No book ID provided.";
    exit;
}


?>
<style>
    .containerBook {
        width: 80%;
        padding: 20px;
        background-color: #333;
        border-radius: 8px;
    }

    .listContainer {
        display: flex;
        gap: 20px;
    }

    .bookCoverDetails {
        flex-shrink: 0;
        width: 200px;
        height: auto;
    }

    .bookDetails {
        flex-grow: 1;
    }

    .button {
        text-align: right;
    }

    .button button {
        padding: 10px 20px;
        font-size: 16px;
        cursor: pointer;
    }
    .bookSrouce {
        margin-top: 20px;
    }
    .bookResource a {
        display: inline-block;
        margin-right: 10px;
        margin-top: 20px;
    }
    .bookResource button {
        padding: 10px 20px;
        font-size: 16px;
        cursor: pointer;
    }

</style>
<div class="containerBook" style="width: 100%;
        margin: 0 auto;
        padding: 20px;
        background-color: #333;">
    <section class="contentList">
        <div class="listContainer">
            <div class="bookCoverDetails">
                <img src="/uploads/covers/<?php echo htmlspecialchars($book['book_cover']); ?>" alt="Book Cover" style="max-width: 100%; height: auto;">
            </div>
            <div class="bookDetails" style="color: white;">
                <h2><?php echo htmlspecialchars($book['book_name']); ?></h2>
                <p><strong>Author: </strong> <?php echo htmlspecialchars($book['book_author']); ?></p>
                <p><strong>Description: </strong></p>
                <p><?php echo htmlspecialchars($book['description']); ?></p>
            </div>
            <div class="bookResource" style="color: white;">
                <a href="/uploads/books/<?php echo htmlspecialchars($book['book_source']); ?>" target="_blank">
                    <button>Read Book Online</button>
                </a>
                <?php if ($book['book_video']): ?>
                    <a href="/uploads/videos/<?php echo htmlspecialchars($book['book_video']); ?>" target="_blank">
                        <button>Watch Video</button>
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </section>
    <div class="button">
        <button type="button" onclick="closeForm()">Close</button>
    </div>

</div>