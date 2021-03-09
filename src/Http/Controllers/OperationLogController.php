<?php

namespace Encore\OperationLog\Http\Controllers;

use Encore\Admin\Http\Controllers\AdminController;
use Encore\Admin\Table;
use Illuminate\Support\Arr;

class OperationLogController extends AdminController
{
    /**
     * {@inheritdoc}
     */
    public function title()
    {
        return trans('admin.auth_logs');
    }

    /**
     * @return Table
     */
    protected function table()
    {
        $logModel = config('admins.operation_log.logs_model');

        $table = new Table(new $logModel());
        $table->model()->orderByDesc('id');

        $table->column('id', 'ID')->sortable();
        $table->column('user.name', trans('admin.user'));
        $table->column('operation', trans('admin.operation'))->display(function ($operation) {
            return admin_route_trans($operation);
        });
        $table->column('method', trans('admin.method'))->display(function ($method) use ($logModel) {
            $color = Arr::get($logModel::$methodColors, $method, 'grey');
            return '<span class="badge bg-' . $color . '">' . $method . '</span>';
        });
        $table->column('path', trans('admin.path'))->label('info');
        $table->column('ip', trans('admin.ip'))->label('info');
        $table->column('input', trans('admin.input'))->display(function () {
            return trans('admin.view');
        })->modal(trans('admin.view') . trans('admin.input'), function ($modal) {
            $input = json_decode($modal->input, true);
            $input = Arr::except($input, ['_pjax', '_token', '_method', '_previous_']);
            if (empty($input)) {
                return '<pre>{}</pre>';
            }

            return '<pre>'.json_encode($input, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE).'</pre>';
        });

        $table->column('created_at', trans('admin.created_at'));

        $table->actions(function (Table\Displayers\Actions $actions) {
            $actions->disableEdit();
            $actions->disableView();
        });

        $table->disableCreateButton();

        $table->filter(function (Table\Filter $filter) use ($logModel) {
            $userModel = config('admin.database.users_model');

            $filter->equal('user_id', trans('admin.user'))->select($userModel::pluck('name', 'id'));
            $filter->equal('method', trans('admin.method'))->select(array_combine($logModel::$methods, $logModel::$methods));
            $filter->like('path', trans('admin.path'));
            $filter->equal('ip', trans('admin.ip'));
        });

        return $table;
    }

    /**
     * @param mixed $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $ids = explode(',', $id);

        $logModel = config('admins.operation_log.logs_model');

        if ($logModel::destroy(array_filter($ids))) {
            return $this->response(false)->success(trans('admin.delete_succeeded'))->refresh()->send();
        } else {
            return $this->response(false)->error(trans('admin.delete_failed'))->send();
        }
    }
}
