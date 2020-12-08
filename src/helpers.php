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
