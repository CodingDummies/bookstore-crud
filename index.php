<?php require_once "app/app.php" ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <title>Book Store</title>
</head>

<body>
    <main>
        <h1>BOOK LIST</h1>
        <table>
            <tr>
                <th>Book ID</th>
                <th>Book Name</th>
                <th>Author</th>
                <th>Category</th>
                <th>Publish Date</th>
                <th>Action</th>
            </tr>
            <?php foreach ($bookList->getBookList() as $book) : ?>
                <tr>
                    <td><?= $book->getId() ?></td>
                    <td><?= $book->getName() ?></td>
                    <td><?= "{$book->getAuthorFirstName()} {$book->getAuthorLastName()}" ?></td>
                    <td><?= $book->getCategory() ?></td>
                    <td><?= $book->getDate() ?></td>
                    <td>
                        <a class="edit" href="<?= "edit-book.php?id=" . $book->getId() ?>">Edit</a>
                        <button class="delete" onclick="openModal(<?= $book->getId() ?>)">Delete</button>
                    </td>
                </tr>
            <?php endforeach ?>
        </table>

        <div>
            <a class=" add-link" href="add-book.php">Add New Book</a>
        </div>

        <dialog>
            <span>Are you sure you want to delete this book?</span>
            <div>
                <button class="yes" onclick="confirm()">Yes</button>
                <button class="no" onclick="closeModal()">No</button>
            </div>
        </dialog>
    </main>

    <script>
        const dialog = document.querySelector("dialog");
        let bookId

        const openModal = (id) => {
            dialog.showModal();
            bookId = id;
            console.log(bookId);
        }

        const closeModal = () => {
            dialog.close();
            bookId = null;
        }

        const confirm = () => {
            if (!bookId) return;

            const xmlhttp = new XMLHttpRequest();

            // Set up a callback function to handle the response
            xmlhttp.onreadystatechange = function() {
                if (xmlhttp.readyState == XMLHttpRequest.DONE) {
                    if (xmlhttp.status == 200) {
                        // Request was successful, handle the response
                        window.location.reload();
                    } else {
                        // Request failed
                        console.error('Request failed: ' + xmlhttp.status);
                    }
                }
            };

            // Set the Content-Type header if sending form data
            xmlhttp.open("POST", "delete-book.php");
            xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xmlhttp.send(`id=${bookId}`);
        }
    </script>
</body>

</html>