<?php

declare(strict_types=1);

namespace App\Http\Requests\DTO;

final readonly class UpdateBookDto
{
    /**
     * @param  int[]  $authorIds
     */
    public function __construct(
        public string $title,
        public array $authorIds,
    ) {}
}
