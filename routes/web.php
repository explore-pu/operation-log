<?php

use Encore\OperationLog\Http\Controllers\OperationLogController;
use Illuminate\Support\Facades\Route;
use Illuminate\Routing\Router;


Route::group([
    'as'         => config('admin.route.as') . '.',
], function (Router $router) {
    $logController = config('admins.operation_log.logs_controller', OperationLogController::class);
    $router->resource('auth_logs', $logController)->only(['index', 'destroy'])->names('auth_logs');
});
