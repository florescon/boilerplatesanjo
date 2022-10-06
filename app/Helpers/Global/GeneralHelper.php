<?php

use Carbon\Carbon;

if (! function_exists('appName')) {
    /**
     * Helper to grab the application name.
     *
     * @return mixed
     */
    function appName()
    {
        return config('app.name', 'Laravel Boilerplate');
    }
}

if (! function_exists('carbon')) {
    /**
     * Create a new Carbon instance from a time.
     *
     * @param $time
     *
     * @return Carbon
     * @throws Exception
     */
    function carbon($time)
    {
        return new Carbon($time);
    }
}

if (! function_exists('homeRoute')) {
    /**
     * Return the route to the "home" page depending on authentication/authorization status.
     *
     * @return string
     */
    function homeRoute()
    {
        if (auth()->check()) {
            if (auth()->user()->isAdmin()) {
                return 'admin.dashboard';
            }

            if (auth()->user()->isUser()) {
                return 'frontend.user.dashboard';
            }
        }

        return 'frontend.index';
    }
}

if (! function_exists('partDay')) {
    /**
     * Return the route to the "home" page depending on authentication/authorization status.
     *
     * @return string
     */
    function partDay()
    {
        $partDay = now()->format('H');

        switch (true) {
                case $partDay >= 5 && $partDay <= 11:
                    return __('Good morning');
                case $partDay >= 12 && $partDay <= 16:
                    return __('Good afternoon');
                case $partDay >= 17 && $partDay <= 19:
                    return __('Good evening');
                case $partDay >= 20 || $partDay <= 4:
                    return __('Good night');
                default:
                    return __('Good');
            }

        return '';
    }
}
