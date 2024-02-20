<?php

declare(strict_types=1);

namespace App\Classes;

/**
 * Class QueryBuilder
 *
 * The QueryBuilder class provides methods for building SQL queries related to book management.
 */
class QueryBuilder {

    private static function init() {
        $mysqli = mysqli_connect("localhost", "root", "", "db_bookstore");

        if ($mysqli->connect_error) {
            die("Connection failed: " . $mysqli->connect_error);
        }

        return $mysqli;
    }

    /**
     * Retrieves a list of books with details from the database.
     *
     * This method fetches the list of books from the database, including details such as book ID, name, publication date,
     * category name, and author name.
     *
     * @return array An array containing details of each book.
     */
    public static function buildBookListquery(): array {
        // Initialize the mysqli connection
        $mysqli = self::init();

        // SQL query to retrieve the list of books with details
        $bookListQuery = "SELECT tb.book_id, tb.book_name, tb.publish_date, tc.category_name, ta.last_name, ta.first_name 
                      FROM tbl_books AS tb 
                      INNER JOIN tbl_category AS tc ON tb.category_id = tc.category_id 
                      INNER JOIN tbl_author AS ta ON tb.author_id = ta.author_id";

        // Prepare and execute the SQL statement
        $stmt = $mysqli->prepare($bookListQuery);
        $stmt->execute();

        // Bind the result variables
        $stmt->bind_result($bookId, $bookName, $publishDate, $categoryName, $lastName, $firstName);

        // Fetch the results into an array
        $books = [];
        while ($stmt->fetch()) {
            $books[] = new Book($bookId, $bookName, $firstName, $lastName, $categoryName, $publishDate);
        }

        // Close the statement and database connection
        $stmt->close();
        $mysqli->close();

        return $books;
    }


    /**
     * Builds a SQL query to delete a book by its ID.
     *
     * This method constructs and executes a prepared SQL DELETE statement to remove a book from the database based on its ID.
     *
     * @param string|int $id The ID of the book to be deleted.
     * @return string A message indicating the success of the deletion operation.
     */
    public static function buildBookDeleteQueryById(string|int $id): string {
        // Initialize the mysqli connection
        $mysqli = self::init();

        // Prepare the SQL statement
        $stmt = $mysqli->prepare("DELETE FROM tbl_books WHERE book_id = ?");

        // Bind the book ID parameter
        $stmt->bind_param("i", $id);

        // Execute the statement
        $stmt->execute();

        // Close the statement and database connection
        $stmt->close();
        $mysqli->close();

        // Return a success message
        return "Book with ID $id deleted successfully.";
    }


    /**
     * Builds a SQL query to insert a new category into the database.
     *
     * This method constructs and executes a prepared SQL INSERT statement to add a new category to the database.
     *
     * @param string $categoryName The name of the category to be inserted.
     * @return int The ID of the newly inserted category.
     */
    public static function buildInsertCategoryQuery(string $categoryName): int {
        // Initialize the mysqli connection
        $mysqli = self::init();

        // Prepare the SQL statement
        $stmt = $mysqli->prepare("INSERT INTO tbl_category (category_name) VALUES (?)");

        // Bind the category name parameter
        $stmt->bind_param("s", $categoryName);

        // Execute the statement
        $stmt->execute();

        // Get the ID of the newly inserted category
        $categoryId = $stmt->insert_id;

        // Close the statement and database connection
        $stmt->close();
        $mysqli->close();

        // Return the ID of the newly inserted category
        return $categoryId;
    }

    /**
     * Builds a SQL query to insert a new author into the database.
     *
     * This method constructs and executes a prepared SQL INSERT statement to add a new author to the database.
     *
     * @param string $firstName The first name of the author.
     * @param string $lastName The last name of the author.
     * @return int The ID of the newly inserted author.
     */
    public static function buildInsertAuthorQuery(string $firstName, string $lastName): int {
        // Initialize the mysqli connection
        $mysqli = self::init();

        // Prepare the SQL statement
        $stmt = $mysqli->prepare("INSERT INTO tbl_author (first_name, last_name) VALUES (?, ?)");

        // Bind the parameters
        $stmt->bind_param("ss", $firstName, $lastName);

        // Execute the statement
        $stmt->execute();

        // Get the ID of the newly inserted author
        $authorId = $stmt->insert_id;

        // Close the statement and database connection
        $stmt->close();
        $mysqli->close();

        // Return the ID of the newly inserted author
        return $authorId;
    }

    /**
     * Builds a SQL query to insert a new book into the database.
     *
     * This method constructs and executes a prepared SQL INSERT statement to add a new book to the database.
     *
     * @param string $bookName The name of the book.
     * @param int $categoryId The ID of the category the book belongs to.
     * @param int $authorId The ID of the author of the book.
     * @param string $publishDate The publish date of the book.
     * @return int The ID of the newly inserted book.
     */
    public static function buildInsertBookQuery(string $bookName, int $categoryId, int $authorId, string $publishDate): int {
        // Initialize the mysqli connection
        $mysqli = self::init();

        // Prepare the SQL statement
        $stmt = $mysqli->prepare("INSERT INTO tbl_books (book_name, category_id, author_id, publish_date) VALUES (?, ?, ?, ?)");

        // Bind the parameters
        $stmt->bind_param("siss", $bookName, $categoryId, $authorId, $publishDate);

        // Execute the statement
        $stmt->execute();

        // Get the ID of the newly inserted book
        $bookId = $stmt->insert_id;

        // Close the statement and database connection
        $stmt->close();
        $mysqli->close();

        // Return the ID of the newly inserted book
        return $bookId;
    }


    /**
     * Builds a SQL query to update the category name for a book.
     *
     * This method constructs and executes a prepared SQL UPDATE statement to update the category name associated with a specific book ID.
     *
     * @param string|int $bookId The ID of the book for which the category name will be updated.
     * @param string $newCategoryName The new name for the category.
     * @return int The ID of the updated category.
     */
    public static function buildUpdateCategoryQuery(string|int $bookId, string $newCategoryName): int {
        // Initialize the mysqli connection
        $mysqli = self::init();

        // Prepare the SQL statement
        $stmt = $mysqli->prepare("UPDATE tbl_category SET category_name = ? WHERE category_id = (SELECT category_id FROM tbl_books WHERE book_id = ?)");

        // Bind the parameters
        $stmt->bind_param("si", $newCategoryName, $bookId);

        // Execute the statement
        $stmt->execute();

        // Get the ID of the updated category
        $categoryId = (int) $mysqli->query("SELECT category_id FROM tbl_books WHERE book_id = $bookId")->fetch_assoc()['category_id'];

        // Close the statement and database connection
        $stmt->close();
        $mysqli->close();

        // Return the ID of the updated category
        return $categoryId;
    }

    /**
     * Builds a SQL query to update the author's first name and last name for a book.
     *
     * This method constructs and executes a prepared SQL UPDATE statement to update the first name and last name of the author associated with a specific book ID.
     *
     * @param string|int $bookId The ID of the book for which the author's name will be updated.
     * @param string $newFirstName The new first name for the author.
     * @param string $newLastName The new last name for the author.
     * @return int The ID of the updated author.
     */
    public static function buildUpdateAuthorQuery(string|int $bookId, string $newFirstName, string $newLastName): int {
        // Initialize the mysqli connection
        $mysqli = self::init();

        // Prepare the SQL statement
        $stmt = $mysqli->prepare("UPDATE tbl_author SET first_name = ?, last_name = ? WHERE author_id = (SELECT author_id FROM tbl_books WHERE book_id = ?)");

        // Bind the parameters
        $stmt->bind_param("ssi", $newFirstName, $newLastName, $bookId);

        // Execute the statement
        $stmt->execute();

        // Get the ID of the updated author
        $authorId = (int) $mysqli->query("SELECT author_id FROM tbl_books WHERE book_id = $bookId")->fetch_assoc()['author_id'];

        // Close the statement and database connection
        $stmt->close();
        $mysqli->close();

        // Return the ID of the updated author
        return $authorId;
    }


    /**
     * Builds a SQL query to update the details of a book.
     *
     * This method constructs and executes a prepared SQL UPDATE statement to update the name and publish date of a book with a specific ID.
     *
     * @param int|string $bookId The ID of the book to be updated.
     * @param string $newBookName The new name for the book.
     * @param string $newPublishDate The new publish date for the book.
     * @return void
     */
    public static function buildUpdateBookQuery($bookId, string $newBookName, string $newPublishDate): void {
        // Initialize the mysqli connection
        $mysqli = self::init();

        // Prepare the SQL statement
        $stmt = $mysqli->prepare("UPDATE tbl_books SET book_name = ?, publish_date = ? WHERE book_id = ?");

        // Bind the parameters
        $stmt->bind_param("ssi", $newBookName, $newPublishDate, $bookId);

        // Execute the statement
        $stmt->execute();

        // Close the statement and database connection
        $stmt->close();
        $mysqli->close();
    }

    /**
     * Get the next available book ID to be assigned.
     *
     * This method queries the database to determine the next available book ID
     * by fetching the maximum book_id currently present in the table and incrementing it by 1.
     *
     * @return int The next available book ID.
     */
    public static function buildLastBookIdQuery(): int {
        // Initialize the mysqli connection
        $mysqli = self::init();

        // Prepare the SQL statement to get the maximum book_id
        $result = $mysqli->query("SELECT MAX(book_id) FROM tbl_books");

        // Fetch the result
        $row = $result->fetch_row();
        $maxBookId = (int) $row[0];

        // Close the result set
        $result->close();

        // Increment the maximum book_id to get the next available ID
        $nextBookId = $maxBookId + 1;

        // Close the database connection
        $mysqli->close();

        // Return the next available book ID
        return $nextBookId;
    }

    /**
     * Builds a SQL query to retrieve details of a specific book by its ID and returns a Book object.
     *
     * This method constructs and executes a prepared SQL SELECT statement to fetch the details of a book with a specific ID.
     *
     * @param int|string $id The ID of the book to retrieve details for.
     * @return Book|null The Book object representing the fetched book, or null if the book with the specified ID does not exist.
     */
    public static function buildCurrentBookQuery(int|string $id): ?Book {
        // Initialize the mysqli connection
        $mysqli = self::init();

        // Prepare the SQL statement
        $stmt = $mysqli->prepare("SELECT b.book_id, b.book_name, c.category_name, a.first_name, a.last_name, b.publish_date 
                              FROM tbl_books b 
                              JOIN tbl_category c ON b.category_id = c.category_id 
                              JOIN tbl_author a ON b.author_id = a.author_id 
                              WHERE b.book_id = ?");

        // Bind the parameter
        $stmt->bind_param("s", $id);

        // Execute the statement
        $stmt->execute();

        // Bind the result variables
        $stmt->bind_result($bookId, $bookName, $categoryName, $authorFirstName, $authorLastName, $publishDate);

        // Fetch the result
        $stmt->fetch();

        // Close the statement and database connection
        $stmt->close();
        $mysqli->close();

        // Return a Book object if a book was found, otherwise return null
        if ($bookId !== null) {
            return new Book($bookId, $bookName, $categoryName, $authorFirstName, $authorLastName, $publishDate);
        }
        return null;
    }
}
