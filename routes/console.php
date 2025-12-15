<?php

use App\Models\Author;
use Illuminate\Support\Facades\Artisan;

Artisan::command('author:create', function () {
    $firstName = trim((string) $this->ask('First name'));
    $lastName = trim((string) $this->ask('Last name'));

    $author = Author::create([
        'first_name' => $firstName,
        'last_name' => $lastName,
    ]);

    $this->info(sprintf('Author %s %s created with ID %d.', $author->first_name, $author->last_name, $author->id));
})->purpose('Create a new author');
