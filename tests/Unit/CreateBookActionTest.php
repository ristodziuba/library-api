<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Actions\CreateBookAction;
use App\Models\Author;
use App\Models\Book;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class CreateBookActionTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_creates_book_and_attaches_authors(): void
    {
        $author1 = Author::factory()->create();
        $author2 = Author::factory()->create();

        $action = $this->app->make(CreateBookAction::class);

        $book = $action->execute(
            title: 'Clean Code',
            authorIds: [$author1->id, $author2->id]
        );

        $this->assertInstanceOf(Book::class, $book);
        $this->assertDatabaseHas('books', [
            'id' => $book->id,
            'title' => 'Clean Code',
        ]);

        $this->assertCount(2, $book->authors);
        $this->assertTrue($book->authors->contains($author1));
        $this->assertTrue($book->authors->contains($author2));
    }
}
