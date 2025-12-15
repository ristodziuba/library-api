<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Author;
use App\Models\Book;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

final class BooksApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_create_book(): void
    {
        Sanctum::actingAs(User::factory()->create());

        $author = Author::create([
            'first_name' => 'John',
            'last_name' => 'Doe',
        ]);

        $response = $this->postJson('/api/books', [
            'title' => 'Clean Architecture',
            'author_ids' => [$author->id],
        ]);

        $response->assertCreated();
        $response->assertJsonFragment([
            'title' => 'Clean Architecture',
        ]);
    }

    public function test_authenticated_user_can_update_book(): void
    {
        Sanctum::actingAs(User::factory()->create());

        $author = Author::create([
            'first_name' => 'John',
            'last_name' => 'Doe',
        ]);

        $book = Book::create([
            'title' => 'Old title',
        ]);

        $response = $this->putJson("/api/books/{$book->id}", [
            'title' => 'Updated title',
            'author_ids' => [$author->id],
        ]);

        $response
            ->assertOk()
            ->assertJsonFragment([
                'title' => 'Updated title',
            ]);
    }

    public function testItFiltersAuthorsAndBooksBySearchQuery(): void
    {
        $author = Author::factory()->create([
            'first_name' => 'Robert',
            'last_name' => 'Martin',
        ]);

        $clean = Book::factory()->create(['title' => 'Clean Code']);
        $design = Book::factory()->create(['title' => 'Design Patterns']);

        $author->books()->attach([$clean->id, $design->id]);

        $response = $this->getJson('/api/authors?search=Clean');

        $response->assertOk();
        $response->assertJsonCount(1, 'data');
        $response->assertJsonFragment(['title' => 'Clean Code']);
        $response->assertJsonMissing(['title' => 'Design Patterns']);
    }

}
