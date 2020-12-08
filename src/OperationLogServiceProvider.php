<?php

namespace Encore\OperationLog;

use Encore\OperationLog\Http\Middleware\OperationLogMiddleware;
use Illuminate\Support\ServiceProvider;

class OperationLogServiceProvider extends ServiceProvider
{
    /**
     * @var array
     */
    protected $commands = [
        Console\InitCommand::class,
    ];

    /**
     * {@inheritdoc}
     */
    public function boot(OperationLog $extension)
    {
        if (! OperationLog::boot()) {
            return ;
        }

        $this->app->booted(function () {
            OperationLog::routes(__DIR__.'/../routes/web.php');
        });

        if ($this->app->runningInConsole() && $migrations = $extension->migrations) {
            $this->publishes([$migrations => database_path('migrations')], 'admin-operation-log-migrations');
        }

        if ($this->app->runningInConsole() && $migrations = $extension->config) {
            $this->publishes([$migrations => config_path('admins')], 'admin-operation-log-config');
        }
    }


    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        app('router')->aliasMiddleware('admin.operation-log', OperationLogMiddleware::class);

        // 替换配置文件
        config([
            'admin.route.middleware.log' => 'admin.operation-log',
        ]);

        $this->commands($this->commands);
    }
}
