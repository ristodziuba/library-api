<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Author;
use App\Models\Book;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class AuthorsSearchApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_filters_authors_by_book_title_using_search_query(): void
    {
        $authorJohn = Author::factory()->create([
            'first_name' => 'John',
            'last_name' => 'Doe',
        ]);

        $authorJane = Author::factory()->create([
            'first_name' => 'Jane',
            'last_name' => 'Smith',
        ]);

        $bookLaravel = Book::factory()->create([
            'title' => 'Laravel Clean Architecture',
        ]);

        $bookSymfony = Book::factory()->create([
            'title' => 'Symfony Internals',
        ]);

        $bookLaravel->authors()->attach([$authorJohn->id, $authorJane->id]);
        $bookSymfony->authors()->attach([$authorJane->id]);

        $response = $this->getJson('/api/authors?search=Laravel');

        $response->assertOk();

        $response->assertJsonCount(2, 'data');

        $response->assertJsonFragment([
            'first_name' => 'John',
            'last_name' => 'Doe',
        ]);

        $response->assertJsonFragment([
            'first_name' => 'Jane',
            'last_name' => 'Smith',
        ]);
    }
}
