<?php

declare(strict_types=1);

namespace App\Classes;

class Book {

    public function __construct(
        private string|int $id,
        private string $name,
        private string $authorFirstName,
        private string $authorLastName,
        private string $category,
        private string $date
    ) {
    }

    public function getId(): string|int {
        return $this->id;
    }

    public function getName(): string {
        return $this->name;
    }

    public function getAuthorFirstName(): string {
        return $this->authorFirstName;
    }

    public function getAuthorLastName(): string {
        return $this->authorLastName;
    }

    public function getCategory(): string {
        return $this->category;
    }

    public function getDate(): string {
        return $this->date;
    }
}
