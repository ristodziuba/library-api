<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Author;
use App\Models\Book;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class AuthorsApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_filters_authors_by_book_title(): void
    {
        $author1 = Author::create([
            'first_name' => 'John',
            'last_name' => 'Doe',
        ]);

        $author2 = Author::create([
            'first_name' => 'Jane',
            'last_name' => 'Smith',
        ]);

        $bookLaravel = Book::create(['title' => 'Laravel Guide']);
        $bookSymfony = Book::create(['title' => 'Symfony Handbook']);

        $author1->books()->attach($bookLaravel->id);
        $author2->books()->attach($bookSymfony->id);

        $response = $this->getJson('/api/authors?search=Laravel');

        $response->assertOk();
        $response->assertJsonCount(1, 'data');
        $response->assertJsonFragment([
            'first_name' => 'John',
            'last_name' => 'Doe',
        ]);
    }

    public function test_it_returns_authors_with_books(): void
    {
        $author = Author::factory()->create();
        $book = Book::factory()->create(['title' => 'Clean Code']);

        $author->books()->attach($book);

        $response = $this->getJson('/api/authors');

        $response->assertOk();
        $response->assertJsonFragment([
            'title' => 'Clean Code',
        ]);
    }

}
