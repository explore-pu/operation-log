<?php

namespace Encore\OperationLog\Console;

use Encore\Admin\Models\Menu;
use Illuminate\Console\Command;

class InitCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'operation-log:init';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Initialize admin-operation-log';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function handle()
    {
        $this->initDatabase();
    }

    /**
     * Create tables and seed it.
     *
     * @return void
     */
    public function initDatabase()
    {
        $this->call('migrate');

        // 如果不存在角色菜单，创建一个
        if (!Menu::query()->where('uri', 'admin_logs')->exists()) {
            // 创建菜单项
            $lastOrder = Menu::query()->max('order');
            Menu::query()->create([
                'parent_id' => 0,
                'order' => $lastOrder++,
                'title' => trans('admin.admin_logs'),
                'icon' => 'fas fa-history',
                'uri' => 'admin_logs',
            ]);
        }
    }
}
