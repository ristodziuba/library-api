<?php

declare(strict_types=1);

namespace App\Repositories\Eloquent;

use App\Models\Author;
use App\Repositories\AuthorWriteRepositoryInterface;

final class AuthorWriteRepository implements AuthorWriteRepositoryInterface
{
    public function updateLastAddedBookTitle(
        Author $author,
        ?string $title
    ): void {
        $author->update([
            'last_added_book_title' => $title,
        ]);
    }
}
