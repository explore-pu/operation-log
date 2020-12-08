<?php

if (!function_exists('admin_route_trans')) {
    /**
     * @param $route
     * @return string
     */
    function admin_route_trans($route)
    {
        $trans = [];
        foreach (explode('.', $route) as $string) {
            if ($string !== config('admin.route.as')) {
                $trans[] = trans('admin.' . $string);
            }
        }
        return implode('.', $trans);
    }
}

if (!function_exists('admin_restore_path')) {
    /**
     * 恢复路由地址（去掉路由前缀prefix）
     *
     * @param string $path
     * @return string
     */
    function admin_restore_path($path = '')
    {
        $new_path = [];
        foreach (explode('/', $path) as $value) {
            if ($value !== config('admin.route.prefix')) {
                array_push($new_path, $value);
            }
        }

        return $new_path ? implode('/', $new_path) : '/';
    }
}

if (!function_exists('admin_restore_route')) {
    /**
     * 恢复路由名称（去掉路由名称的前缀as）
     *
     * @param $name
     * @return string
     */
    function admin_restore_route($name)
    {
        if ($route_as = config('admin.route.as')) {
            $route = [];
            foreach (explode('.', $name) as $route_key => $route_value) {
                if ($route_key !== 0 && $route_value !== $route_as) {
                    $route[] = $route_value;
                }
            }

            return implode('.', $route);
        }

        return $name;
    }
}
