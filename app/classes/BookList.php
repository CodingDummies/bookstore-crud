<?php

declare(strict_types=1);

namespace App\Classes;

/**
 * The BookList class provides methods for managing books, including fetching book lists, deleting, inserting, updating, and retrieving book details.
 */
class BookList {

    /**
     * Retrieves a list of books with details.
     *
     * @return array An array of books with their details.
     */
    public function getBookList(): array {
        $books = QueryBuilder::buildBookListquery();
        return $books;
    }

    /**
     * Deletes a book by its ID.
     *
     * @param string|int $id The ID of the book to delete.
     * @return string A message indicating the deletion status.
     */
    public function deleteBookById(string|int $id): string {
        $message = QueryBuilder::buildBookDeleteQueryById($id);
        return $message;
    }

    /**
     * Inserts a new book into the database.
     *
     * @param Book $book The book object to insert.
     * @return void
     */
    public function insertBook(Book $book): void {
        $categoryId = QueryBuilder::buildInsertCategoryQuery($book->getCategory());
        $authorId = QueryBuilder::buildInsertAuthorQuery($book->getAuthorFirstName(), $book->getAuthorLastName());
        QueryBuilder::buildInsertBookQuery($book->getName(), $categoryId, $authorId, $book->getDate());
    }

    /**
     * Updates an existing book in the database.
     *
     * @param Book $book The book object with updated information.
     * @return void
     */
    public function updateBook(Book $book): void {
        QueryBuilder::buildUpdateCategoryQuery($book->getId(), $book->getCategory());
        QueryBuilder::buildUpdateAuthorQuery($book->getId(), $book->getAuthorFirstName(), $book->getAuthorLastName());
        QueryBuilder::buildUpdateBookQuery($book->getId(), $book->getName(), $book->getDate());
    }

    /**
     * Retrieves the ID of the last inserted book.
     *
     * @return int The ID of the last inserted book.
     */
    public function getBookLastId(): int {
        return QueryBuilder::buildLastBookIdQuery();
    }

    /**
     * Retrieves details of a book by its ID.
     *
     * @param int|string $id The ID of the book to retrieve details for.
     * @return Book|null The Book object representing the retrieved book, or null if the book does not exist.
     */
    public function getCurrentBookById(int|string $id): ?Book {
        return QueryBuilder::buildCurrentBookQuery($id);
    }
}
