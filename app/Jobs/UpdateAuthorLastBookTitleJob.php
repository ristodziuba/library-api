<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Repositories\AuthorReadRepositoryInterface;
use App\Repositories\AuthorWriteRepositoryInterface;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

final class UpdateAuthorLastBookTitleJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public function __construct(
        private readonly int $authorId
    ) {}

    public function handle(
        AuthorReadRepositoryInterface $authorReadRepository,
        AuthorWriteRepositoryInterface $authorWriteRepository
    ): void {
        $author = $authorReadRepository->findWithBooksOrNull($this->authorId);

        if ($author === null) {
            return;
        }

        $lastBookTitle = $author->books()
            ->orderByDesc('books.id')
            ->limit(1)
            ->value('books.title');

        $authorWriteRepository->updateLastAddedBookTitle(
            $author,
            $lastBookTitle
        );
    }
}
