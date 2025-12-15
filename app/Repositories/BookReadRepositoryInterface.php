<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Book;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface BookReadRepositoryInterface
{
    public function paginateWithAuthors(int $perPage): LengthAwarePaginator;

    public function findWithAuthorsOrNull(int $id): ?Book;
}
