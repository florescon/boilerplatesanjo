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
     * 
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

if (! function_exists('priceIncludeIva')) {
    /**
     * 
     *
     * @return string
     */
    function priceIncludeIva($price)
    {
        if(is_numeric($price)){

            return number_format($price + ((setting('iva') / 100) * $price), 2);

        }
    }
}

if (! function_exists('priceWithoutIvaIncluded')) {
    /**
     * 
     *
     * @return string
     */
    function priceWithoutIvaIncluded($price)
    {
        if(is_numeric($price)){

            $iva = (setting('iva') / 100) + 1;

            return number_format(($price / $iva), 2);
        }
    }
}

if (! function_exists('ivaPrice')) {
    /**
     * 
     *
     * @return string
     */
    function ivaPrice($price)
    {
        $iva = (setting('iva') / 100) + 1;

        return number_format($price - ($price / $iva), 2);
    }
}


if (! function_exists('typeInOrder')) {
    /**
     * 
     *
     * @return string
     */
    function typeInOrder($type)
    {
        switch ($type) {
            case 'quotation':
                return 6;
            case 'request':
                return 5;
            case 'sale':
                return 2;
        }

        return 1;
    }
}

if (! function_exists('printed')) {
    /**
     * Return the printed date.
     *
     * @return string
     */
    function printed()
    {
        $printed = now()->isoFormat('D, MMM h:mm:ss a');

        return __('Printed').': '.$printed;        
    }
}

if (! function_exists('generated')) {
    /**
     * Return the generated date.
     *
     * @return string
     */
    function generated()
    {
        $generated = now()->isoFormat('D, MMM h:mm:ss a');

        return __('Generated').': '.$generated;        
    }
}
