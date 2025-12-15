<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\AuthorResource;
use App\Repositories\AuthorReadRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

final class AuthorController extends Controller
{
    public function index(
        Request $request,
        AuthorReadRepositoryInterface $authorReadRepository
    ): JsonResponse {
        $perPage = (int) $request->query('per_page', 15);

        $searchQuery = $request->query('search'); // book title
        if ($searchQuery !== null) {
            $searchQuery = (string) $searchQuery;
        }

        $authors = $authorReadRepository->paginateWithBooks(
            $perPage,
            $searchQuery
        );

        return response()->json([
            'data' => AuthorResource::collection($authors),
            'meta' => [
                'current_page' => $authors->currentPage(),
                'last_page' => $authors->lastPage(),
                'total' => $authors->total(),
            ],
        ]);
    }

    public function show(
        int $author,
        AuthorReadRepositoryInterface $authorReadRepository
    ): JsonResponse {
        $authorModel = $authorReadRepository->findWithBooksOrNull($author);

        if ($authorModel === null) {
            return response()->json([
                'message' => 'Author not found',
            ], 404);
        }

        return response()->json(
            new AuthorResource($authorModel)
        );
    }
}
