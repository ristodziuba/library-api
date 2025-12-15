<?php

declare(strict_types=1);

namespace App\Repositories\Eloquent;

use App\Models\Book;
use App\Repositories\BookWriteRepositoryInterface;

final class BookWriteRepository implements BookWriteRepositoryInterface
{
    public function create(string $title): Book
    {
        return Book::create([
            'title' => $title,
        ]);
    }

    public function update(Book $book, array $data): void
    {
        $book->update($data);
    }

    /**
     * @param  int[]  $authorIds
     */
    public function syncAuthors(Book $book, array $authorIds): void
    {
        $book->authors()->sync($authorIds);
    }

    public function delete(Book $book): void
    {
        $book->delete();
    }
}
