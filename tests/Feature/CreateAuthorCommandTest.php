<?php

declare(strict_types=1);

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class CreateAuthorCommandTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_creates_author_from_prompted_input(): void
    {
        $this->artisan('author:create')
            ->expectsQuestion('First name', 'John')
            ->expectsQuestion('Last name', 'Doe')
            ->expectsOutput('Author John Doe created with ID 1.')
            ->assertExitCode(0);

        $this->assertDatabaseHas('authors', [
            'first_name' => 'John',
            'last_name' => 'Doe',
        ]);
    }
}
