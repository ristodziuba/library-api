<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Author;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface AuthorReadRepositoryInterface
{
    public function paginateWithBooks(
        int $perPage,
        ?string $searchQuery
    ): LengthAwarePaginator;

    public function findWithBooksOrNull(int $id): ?Author;
}
