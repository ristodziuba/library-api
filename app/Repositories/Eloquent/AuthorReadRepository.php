<?php

declare(strict_types=1);

namespace App\Repositories\Eloquent;

use App\Models\Author;
use App\Repositories\AuthorReadRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

final class AuthorReadRepository implements AuthorReadRepositoryInterface
{
    public function paginateWithBooks(
        int $perPage,
        ?string $searchQuery = null
    ): LengthAwarePaginator {
        $query = Author::query();

        if ($searchQuery !== null && $searchQuery !== '') {
            $query
                ->whereHas('books', function ($booksQuery) use ($searchQuery) {
                    $booksQuery->where('title', 'like', '%' . $searchQuery . '%');
                })
                ->with([
                    'books' => function ($booksQuery) use ($searchQuery) {
                        $booksQuery
                            ->select('books.id', 'books.title')
                            ->where('title', 'like', '%' . $searchQuery . '%');
                    },
                ]);
        } else {
            $query->with('books:id,title');
        }

        return $query->paginate($perPage);
    }

    public function findWithBooksOrNull(int $id): ?Author
    {
        return Author::query()
            ->with('books:id,title')
            ->find($id);
    }
}
