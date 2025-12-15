<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Author;
use App\Models\Book;
use Illuminate\Database\Seeder;

final class LibrarySeeder extends Seeder
{
    public function run(): void
    {
        $martin = Author::create([
            'first_name' => 'Robert',
            'last_name' => 'Martin',
        ]);

        $evans = Author::create([
            'first_name' => 'Eric',
            'last_name' => 'Evans',
        ]);

        $gamma = Author::create([
            'first_name' => 'Erich',
            'last_name' => 'Gamma',
        ]);

        $cleanCode = Book::create([
            'title' => 'Clean Code',
        ]);

        $ddd = Book::create([
            'title' => 'Domain-Driven Design',
        ]);

        $patterns = Book::create([
            'title' => 'Design Patterns',
        ]);

        // 1 book → many authors
        $patterns->authors()->attach([
            $martin->id,
            $gamma->id,
        ]);

        // many books → 1 author
        $cleanCode->authors()->attach($martin->id);
        $ddd->authors()->attach($evans->id);
    }
}
