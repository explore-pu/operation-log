<?php

namespace Encore\OperationLog\Http\Middleware;

use Encore\Admin\Facades\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class OperationLogMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     *
     * @return mixed
     */
    public function handle(Request $request, \Closure $next)
    {
        if ($this->shouldLogOperation($request)) {
            $request->setTrustedProxies(request()->getClientIps(), \Illuminate\Http\Request::HEADER_X_FORWARDED_FOR);
            $log = [
                'user_id' => Admin::user()->id,
                'operation' => admin_restore_route($request->route()->action['as']),
                'path'    => admin_restore_path(substr($request->path(), 0, 255)),
                'method'  => $request->method(),
                'ip'      => $request->getClientIp(),
                'input'   => json_encode($request->input()),
            ];

            try {
                $logModel = config('admins.operation_log.logs_model');
                $logModel::create($log);
            } catch (\Exception $exception) {
                // pass
            }
        }

        return $next($request);
    }

    /**
     * @param Request $request
     *
     * @return bool
     */
    protected function shouldLogOperation(Request $request)
    {
        return Admin::user() && config('admins.operation_log.enable') && !$this->inExceptArray($request) && $this->inAllowedMethods($request->method());
    }

    /**
     * Whether requests using this method are allowed to be logged.
     *
     * @param string $method
     *
     * @return bool
     */
    protected function inAllowedMethods($method)
    {
        $allowedMethods = collect(config('admins.operation_log.allowed_methods'))->filter();

        if ($allowedMethods->isEmpty()) {
            return true;
        }

        return $allowedMethods->map(function ($method) {
            return strtoupper($method);
        })->contains($method);
    }

    /**
     * Determine if the request has a URI that should pass through CSRF verification.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return bool
     */
    protected function inExceptArray($request)
    {
        foreach (config('admins.operation_log.excepts') as $except) {
            $except = admin_base_path($except);
            if ($except !== '/') {
                $except = trim($except, '/');
            }

            $methods = [];

            if (Str::contains($except, ':')) {
                list($methods, $except) = explode(':', $except);
                $methods = explode(',', $methods);
            }

            $methods = array_map('strtoupper', $methods);

            if ($request->is($except) && (empty($methods) || in_array($request->method(), $methods))) {
                return true;
            }
        }

        return false;
    }
}
