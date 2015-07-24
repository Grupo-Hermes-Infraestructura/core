<?php

namespace Ghi\Core\Providers;

use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(
            \Ghi\Core\Contracts\UserRepository::class,
            \Ghi\Core\Repositories\EloquentUserRepository::class
        );

        $this->app->bind(
            \Ghi\Core\Contracts\ObraRepository::class,
            \Ghi\Core\Repositories\EloquentObraRepository::class
        );

        $this->app->bind(
            \Ghi\Core\Contracts\ConceptoRepository::class,
            \Ghi\Core\Repositories\EloquentConceptoRepository::class
        );

        $this->app->bind(
            \Ghi\Core\Contracts\EmpresaRepository::class,
            \Ghi\Core\Repositories\EloquentEmpresaRepository::class
        );

        $this->app->bind(
            \Ghi\Core\Contracts\MaterialRepository::class,
            \Ghi\Core\Repositories\EloquentMaterialRepository::class
        );

        $this->app->bind(
            \Ghi\Core\Contracts\AlmacenRepository::class,
            \Ghi\Core\Repositories\EloquentAlmacenRepository::class
        );
    }
}
