<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Actions\CreateBookAction;
use App\Actions\DeleteBookAction;
use App\Actions\UpdateBookAction;
use App\Exceptions\BookNotFoundException;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBookRequest;
use App\Http\Requests\UpdateBookRequest;
use App\Http\Resources\BookResource;
use App\Models\Book;
use App\Repositories\BookReadRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

final class BookController extends Controller
{
    public function index(
        Request $request,
        BookReadRepositoryInterface $bookReadRepository
    ): JsonResponse {
        $perPage = (int) $request->query('per_page', 15);

        $books = $bookReadRepository->paginateWithAuthors($perPage);

        return response()->json([
            'data' => BookResource::collection($books),
            'meta' => [
                'current_page' => $books->currentPage(),
                'last_page' => $books->lastPage(),
                'total' => $books->total(),
            ],
        ]);
    }

    public function store(
        StoreBookRequest $request,
        CreateBookAction $action
    ): JsonResponse {
        $book = $action->execute(
            $request->string('title')->toString(),
            $request->validated('author_ids')
        );

        return response()->json(
            new BookResource($book->load('authors')),
            Response::HTTP_CREATED
        );
    }

    public function destroy(
        int $book,
        DeleteBookAction $action
    ): JsonResponse {
        $action->execute($book);

        return response()->json(status: Response::HTTP_NO_CONTENT);
    }

    public function show(Book $book): BookResource
    {
        return new BookResource($book->load('authors'));
    }

    public function update(
        UpdateBookRequest $request,
        Book $book,
        UpdateBookAction $action
    ): BookResource|JsonResponse {
        try {
            $updated = $action->execute((int) $book->id, $request->toDto());
        } catch (BookNotFoundException) {
            return response()->json(['message' => 'Not found'], Response::HTTP_NOT_FOUND);
        }

        return new BookResource($updated);
    }
}
