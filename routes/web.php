<?php

use Encore\OperationLog\Http\Controllers\OperationLogController;
use Illuminate\Support\Facades\Route;

$logController = config('admins.operation_log.logs_controller', OperationLogController::class);
Route::resource('admin_logs', $logController)->only(['index', 'destroy'])->names('admin_logs');
