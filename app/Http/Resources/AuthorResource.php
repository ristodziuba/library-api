<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

final class AuthorResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'last_added_book_title' => $this->last_added_book_title,

            // books WITHOUT authors
            'books' => BookSimpleResource::collection(
                $this->whenLoaded('books')
            ),
        ];
    }
}
