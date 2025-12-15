<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Book;

interface BookWriteRepositoryInterface
{
    public function create(string $title): Book;

    public function update(Book $book, array $data): void;

    /**
     * @param  int[]  $authorIds
     */
    public function syncAuthors(Book $book, array $authorIds): void;

    public function delete(Book $book): void;
}
