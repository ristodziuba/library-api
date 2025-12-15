<?php

declare(strict_types=1);

namespace App\Repositories\Eloquent;

use App\Models\Book;
use App\Repositories\BookReadRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

final class BookReadRepository implements BookReadRepositoryInterface
{
    public function paginateWithAuthors(int $perPage): LengthAwarePaginator
    {
        return Book::query()
            ->with('authors:id,first_name,last_name')
            ->orderByDesc('id')
            ->paginate($perPage);
    }

    public function findWithAuthorsOrNull(int $id): ?Book
    {
        return Book::query()
            ->with('authors:id,first_name,last_name')
            ->find($id);
    }
}
