<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Http\Requests\DTO\UpdateBookDto;
use Illuminate\Foundation\Http\FormRequest;

final class UpdateBookRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'min:1', 'max:255'],
            'author_ids' => ['required', 'array', 'min:1'],
            'author_ids.*' => ['integer', 'distinct', 'exists:authors,id'],
        ];
    }

    public function toDto(): UpdateBookDto
    {
        return new UpdateBookDto(
            $this->string('title')->toString(),
            $this->input('author_ids'),
        );
    }
}
