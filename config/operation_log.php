<?php

return [
    'enable' => true,

    'logs_table' => 'admin_logs',

    'logs_model' => Encore\OperationLog\Models\OperationLog::class,

//    'logs_controller' => Encore\OperationLog\Http\Controllers\OperationLogController::class,

    'allowed_methods' => ['GET', 'HEAD', 'POST', 'PUT', 'DELETE', 'CONNECT', 'OPTIONS', 'TRACE', 'PATCH'],

    'excepts' => [
        "_require_config",
        "auth_logs",
//        "auth_logs/*",
    ]
];
