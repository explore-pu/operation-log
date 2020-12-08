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
