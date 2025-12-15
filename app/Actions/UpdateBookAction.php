<?php

declare(strict_types=1);

namespace App\Actions;

use App\Exceptions\BookNotFoundException;
use App\Http\Requests\DTO\UpdateBookDto;
use App\Jobs\UpdateAuthorLastBookTitleJob;
use App\Models\Book;
use App\Repositories\BookReadRepositoryInterface;
use App\Repositories\BookWriteRepositoryInterface;
use Illuminate\Support\Facades\DB;
use RuntimeException;

final class UpdateBookAction
{
    public function __construct(
        private readonly BookWriteRepositoryInterface $bookWriteRepository,
        private readonly BookReadRepositoryInterface $bookReadRepository,
    ) {}

    public function execute(int $bookId, UpdateBookDto $dto): Book
    {
        return DB::transaction(function () use ($bookId, $dto): Book {
            $book = $this->bookReadRepository->findWithAuthorsOrNull($bookId);

            if ($book === null) {
                throw new BookNotFoundException($bookId);
            }

            $this->bookWriteRepository->update($book, [
                'title' => $dto->title,
            ]);

            $this->bookWriteRepository->syncAuthors($book, $dto->authorIds);

            foreach ($dto->authorIds as $authorId) {
                UpdateAuthorLastBookTitleJob::dispatch($authorId);
            }

            $fresh = $this->bookReadRepository->findWithAuthorsOrNull($bookId);

            if ($fresh === null) {
                // Should not happen unless deleted inside transaction.
                throw new RuntimeException('Book not found');
            }

            return $fresh;
        });
    }
}
