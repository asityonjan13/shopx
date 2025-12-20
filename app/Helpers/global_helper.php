<?php
// Check user has permissions  to view elements in sidebar
if (!function_exists('hasPermission')) {
    function hasPermission(array $permissions): bool
    {
        return auth('admin')->user()->hasRole('Super Admin')
            || auth('admin')->user()->hasAnyPermission($permissions);
    }
}
