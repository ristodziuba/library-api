<?php

declare(strict_types=1);

namespace App\Providers;

use App\Repositories\AuthorReadRepositoryInterface;
use App\Repositories\AuthorWriteRepositoryInterface;
use App\Repositories\BookReadRepositoryInterface;
use App\Repositories\BookWriteRepositoryInterface;
use App\Repositories\Eloquent\AuthorReadRepository;
use App\Repositories\Eloquent\AuthorWriteRepository;
use App\Repositories\Eloquent\BookReadRepository;
use App\Repositories\Eloquent\BookWriteRepository;
use Illuminate\Support\ServiceProvider;

final class RepositoryServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(
            BookReadRepositoryInterface::class,
            BookReadRepository::class
        );

        $this->app->bind(
            BookWriteRepositoryInterface::class,
            BookWriteRepository::class
        );

        $this->app->bind(
            AuthorReadRepositoryInterface::class,
            AuthorReadRepository::class
        );

        $this->app->bind(
            AuthorWriteRepositoryInterface::class,
            AuthorWriteRepository::class
        );
    }
}
