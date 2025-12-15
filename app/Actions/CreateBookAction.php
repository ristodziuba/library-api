<?php

declare(strict_types=1);

namespace App\Actions;

use App\Jobs\UpdateAuthorLastBookTitleJob;
use App\Models\Book;
use App\Repositories\BookWriteRepositoryInterface;
use Illuminate\Support\Facades\DB;

final class CreateBookAction
{
    public function __construct(
        private readonly BookWriteRepositoryInterface $bookWriteRepository
    ) {}

    /**
     * @param  int[]  $authorIds
     */
    public function execute(string $title, array $authorIds): Book
    {
        return DB::transaction(function () use ($title, $authorIds): Book {
            $book = $this->bookWriteRepository->create($title);
            $book->authors()->sync($authorIds);

            DB::afterCommit(static function () use ($authorIds): void {
                foreach ($authorIds as $authorId) {
                    UpdateAuthorLastBookTitleJob::dispatch($authorId);
                }
            });

            return $book;
        });
    }
}
