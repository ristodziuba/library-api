<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Actions\DeleteBookAction;
use App\Models\Author;
use App\Models\Book;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class DeleteBookActionTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_deletes_book_and_detaches_authors(): void
    {
        $author = Author::factory()->create();
        $book = Book::factory()->create();

        $book->authors()->attach($author->id);

        $action = $this->app->make(DeleteBookAction::class);

        $action->execute($book->id);

        $this->assertDatabaseMissing('books', [
            'id' => $book->id,
        ]);

        $this->assertDatabaseMissing('author_book', [
            'book_id' => $book->id,
            'author_id' => $author->id,
        ]);
    }
}
