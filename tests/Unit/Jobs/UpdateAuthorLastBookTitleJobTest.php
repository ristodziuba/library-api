<?php

declare(strict_types=1);

namespace Tests\Unit\Jobs;

use App\Jobs\UpdateAuthorLastBookTitleJob;
use App\Models\Author;
use App\Models\Book;
use App\Repositories\AuthorReadRepositoryInterface;
use App\Repositories\AuthorWriteRepositoryInterface;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class UpdateAuthorLastBookTitleJobTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_stores_last_added_book_title_for_author(): void
    {
        $author = Author::create([
            'first_name' => 'John',
            'last_name' => 'Doe',
        ]);

        $bookA = Book::create(['title' => 'First Book']);
        $bookB = Book::create(['title' => 'Second Book']);

        $author->books()->attach($bookA->id);
        $author->books()->attach($bookB->id);

        $job = new UpdateAuthorLastBookTitleJob($author->id);
        $job->handle(
            app(AuthorReadRepositoryInterface::class),
            app(AuthorWriteRepositoryInterface::class)
        );

        $author->refresh();

        $this->assertSame('Second Book', $author->last_added_book_title);
    }

    public function test_it_updates_title_after_book_deletion(): void
    {
        $author = Author::create([
            'first_name' => 'Jane',
            'last_name' => 'Smith',
        ]);

        $bookA = Book::create(['title' => 'Book A']);
        $bookB = Book::create(['title' => 'Book B']);

        $author->books()->attach([$bookA->id, $bookB->id]);

        // simulate deletion of last book
        $author->books()->detach($bookB->id);
        $bookB->delete();

        $job = new UpdateAuthorLastBookTitleJob($author->id);
        $job->handle(
            app(AuthorReadRepositoryInterface::class),
            app(AuthorWriteRepositoryInterface::class)
        );

        $author->refresh();

        $this->assertSame('Book A', $author->last_added_book_title);
    }

    public function test_it_does_nothing_when_author_does_not_exist(): void
    {
        $job = new UpdateAuthorLastBookTitleJob(999);

        $job->handle(
            app(AuthorReadRepositoryInterface::class),
            app(AuthorWriteRepositoryInterface::class)
        );

        $this->assertTrue(true); // no exception = success
    }
}
