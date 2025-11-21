<?php

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;



if (!function_exists('format_price')) {
    /**
     * Format price with currency icon.
     *
     * @param float|int|string $amount
     * @param string $currency
     * @return string
     */
    function format_price($amount, $currency = '$')
    {
        $amount = is_numeric($amount) ? number_format($amount, 2) : floatval($amount);

        return $currency . $amount;
    }
}

if (!function_exists('format_datetime')) {
    /**
     * Format a date/time in "M d Y H:i" if time exists, else "M d Y"
     *
     * @param string|\Carbon\Carbon $date
     * @return string
     */
    function format_datetime($date)
    {
        if (!$date) return '';

        // Convert to Carbon instance if not already
        $date = $date instanceof \Carbon\Carbon ? $date : \Carbon\Carbon::parse($date);

        // Check if time is 00:00:00
        if ($date->format('H:i:s') === '00:00:00') {
            return $date->format('M d Y'); // Only date
        }

        return $date->format('M d Y H:i'); // Date + time
    }


    if (!function_exists('routePrefix')) {
        function routePrefix()
        {
            $prefix = request()->segment(1); // 'admin' or 'vendor'

            if ($prefix === 'admin') {
                return 'admin.';
            } elseif ($prefix === 'vendor') {
                return 'vendor.';
            }

            return '';
        }
    }
}

if (!function_exists('get_admin_setting')) {
    function get_admin_setting($key, $default = null)
    {
        // ✅ Only run DB logic when the application is fully booted
        if (!function_exists('app') || !app() || !app()->bound('db')) {
            return $default;
        }

        try {
            return DB::table('admin_settings')->where('key', $key)->value('value') ?? $default;
        } catch (\Throwable $th) {
            // prevent crash during composer or artisan
            return $default;
        }
    }
}
