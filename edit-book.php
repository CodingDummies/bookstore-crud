<?php

use App\Classes\Book;

require_once "app/app.php";

if (isset($_POST["submit"])) {
    $bookId = $_POST["book-id"];
    $bookName = $_POST["book-name"];
    $firstName = $_POST["first-name"];
    $lastName = $_POST["last-name"];
    $category = $_POST["category"];
    $publishDate = $_POST["publish-date"];

    $book = new Book($bookId, $bookName, $firstName, $lastName, $category, $publishDate);
    $bookList->updateBook($book);
    header("Location: index.php");
}

$id = $_GET["id"];
$book = $bookList->getCurrentBookById($id);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/add.css">
    <title>Book Store</title>
</head>

<body>
    <main>
        <a class="back" href="index.php">Back</a>
        <h1>Update Book</h1>
        <form action="edit-book.php" method="post">
            <div>
                <label for="book-id">Book ID:</label>
                <input type="text" id="book-id" name="book-id" readonly class="id" value="<?= $book->getId() ?>" required>
            </div>
            <div>
                <label for="book-name">Book Name:</label>
                <input type="text" id="book-name" name="book-name" value="<?= $book->getName() ?>" required>
            </div>
            <div>
                <label for="first-name">Author First Name:</label>
                <input type="text" id="first-name" name="first-name" value="<?= $book->getAuthorFirstName() ?>" required>
            </div>
            <div>
                <label for="last-name">Author Last Name:</label>
                <input type="text" id="last-name" name="last-name" value="<?= $book->getAuthorLastName() ?>" required>
            </div>
            <div>
                <label for="category">Category:</label>
                <input type="text" id="category" name="category" value="<?= $book->getCategory() ?>" required>
            </div>
            <div>
                <label for="publish-date">Publish Date:</label>
                <input type="date" id="publish-date" name="publish-date" value="<?= $book->getDate() ?>" required>
            </div>

            <div class="button-con">
                <button type="submit" name="submit">Update Book</button>
            </div>
        </form>
    </main>
</body>

</html>