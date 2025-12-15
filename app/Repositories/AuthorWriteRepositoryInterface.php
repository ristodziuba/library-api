<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Author;

interface AuthorWriteRepositoryInterface
{
    public function updateLastAddedBookTitle(
        Author $author,
        ?string $title
    ): void;
}
