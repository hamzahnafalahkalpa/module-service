<?php

namespace Hanafalah\ModuleService;

use Hanafalah\ModuleService\Schemas\{
    ServiceItem,
    Service,
    ModelHasService,
    ServicePrice
};

use Hanafalah\ModuleService\Models\{
    Service as ModelsService,
};
use Hanafalah\LaravelSupport\Providers\BaseServiceProvider;

class ModuleServiceServiceProvider extends BaseServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->registerMainClass(ModuleService::class)
            //             ->registerCommandService(Providers\CommandServiceProvider::class)
            ->registers([
                '*',
                'Services'  => function () {
                    $this->binds([
                        Contracts\ModuleService::class   => ModuleService::class,
                        Contracts\ServiceItem::class     => ServiceItem::class,
                        Contracts\Service::class         => Service::class,
                        Contracts\ModelHasService::class => ModelHasService::class,
                        Contracts\ServicePrice::class    => ServicePrice::class,
                    ]);
                }
            ]);
    }

    protected function dir(): string
    {
        return __DIR__ . '/';
    }

    protected function migrationPath(string $path = ''): string
    {
        return database_path($path);
    }
}
