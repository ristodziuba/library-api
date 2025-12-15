<?php

declare(strict_types=1);

namespace App\Actions;

use App\Jobs\UpdateAuthorLastBookTitleJob;
use App\Repositories\BookReadRepositoryInterface;
use App\Repositories\BookWriteRepositoryInterface;
use Illuminate\Support\Facades\DB;
use RuntimeException;

final class DeleteBookAction
{
    public function __construct(
        private readonly BookReadRepositoryInterface $bookReadRepository,
        private readonly BookWriteRepositoryInterface $bookWriteRepository
    ) {}

    public function execute(int $bookId): void
    {
        $book = $this->bookReadRepository->findWithAuthorsOrNull($bookId);

        if ($book === null) {
            throw new RuntimeException('Book not found');
        }

        DB::transaction(function () use ($book): void {
            $authorIds = $book->authors()->pluck('authors.id')->all();

            $book->authors()->detach();
            $this->bookWriteRepository->delete($book);

            DB::afterCommit(static function () use ($authorIds): void {
                foreach ($authorIds as $authorId) {
                    UpdateAuthorLastBookTitleJob::dispatch((int) $authorId);
                }
            });
        });
    }
}
