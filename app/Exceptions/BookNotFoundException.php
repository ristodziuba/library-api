<?php

declare(strict_types=1);

namespace App\Exceptions;

use RuntimeException;

final class BookNotFoundException extends RuntimeException
{
    public function __construct(int $bookId)
    {
        parent::__construct('Book not found (id='.$bookId.')');
    }
}
