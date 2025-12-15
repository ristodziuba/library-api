<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Actions\UpdateBookAction;
use App\Models\Author;
use App\Models\Book;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class UpdateBookActionTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_updates_book_and_syncs_authors(): void
    {
        $author1 = Author::factory()->create();
        $author2 = Author::factory()->create();

        $book = Book::factory()->create([
            'title' => 'Old title',
        ]);

        $book->authors()->attach($author1->id);

        $action = app(UpdateBookAction::class);

        $updated = $action->execute(
            $book->id,
            new \App\Http\Requests\DTO\UpdateBookDto(
                'New title',
                [$author2->id]
            )
        );

        $this->assertSame('New title', $updated->title);
        $this->assertCount(1, $updated->authors);
        $this->assertSame($author2->id, $updated->authors->first()->id);
    }
}
