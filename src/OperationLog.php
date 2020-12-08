<?php

namespace Encore\OperationLog;

use Encore\Admin\Extension;

class OperationLog extends Extension
{
    public $name = 'operation-log';

    public $views = __DIR__ . '/../resources/views';

    public $assets = __DIR__ . '/../resources/assets';

    public $migrations = __DIR__ . '/../database/migrations';

    public $config = __DIR__ . '/../config';

    public $menu = [
        'title' => 'OperationLog',
        'path' => 'operation-log',
        'icon' => 'fa-gears',
    ];
}
