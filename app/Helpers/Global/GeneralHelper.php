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
        return config('app.name', 'Laravel');
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

            return number_format($price + ((setting('iva') / 100) * $price), 2, '.', '');

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

            return number_format(($price / $iva), 2, '.', '');
        }
    }
}

if (! function_exists('priceWithoutIvaIncludedNon')) {
    /**
     * 
     *
     * @return string
     */
    function priceWithoutIvaIncludedNon($price)
    {
        if(is_numeric($price)){

            $iva = (setting('iva') / 100) + 1;

            return $price / $iva;
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

        return number_format($price - ($price / $iva), 2, '.', '');
    }
}

if (! function_exists('calculateIva')) {
    /**
     * 
     *
     * @return string
     */
    function calculateIva($price)
    {
        $iva = setting('iva') / 100;

        return number_format($price * $iva, 2, '.', '');
    }
}

if (! function_exists('calculateIvaNon')) {
    /**
     * 
     *
     * @return string
     */
    function calculateIvaNon($price)
    {
        $iva = setting('iva') / 100;

        return $price * $iva;
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
            case 'output_products':
                return 7;
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

if (! function_exists('typeOutOrder')) {
    /**
     * 
     *
     * @return string
     */
    function typeOutOrder($type)
    {
        switch ($type) {
            case 7:
                return 'output_products';
            case 6:
                return 'quotation';
            case 5:
                return 'request';
            case 2:
                return 'sale';
        }

        return 'order';
    }
}


if (! function_exists('typeOrderCharacters')) {
    /**
     * 
     *
     * @return string
     */
    function typeOrderCharacters($type)
    {
            switch ($type) {
                case 2:
                    return 'VEN';
                case 3:
                    return 'MIX';
                case 4:
                    return 'OUT';
                case 5:
                    return 'PED';
                case 6:
                    return 'COT';
                case 7:
                    return 'OUTP';
                default:
                    return 'ORD';
            }

        return '';
    }
}

if (! function_exists('typeOrderColor')) {
    /**
     * 
     *
     * @return string
     */
    function typeOrderColor($type)
    {
        switch ($type) {
            case 2:
                return '#DEFFDF';
            case 3:
                return '#FFFFDE';
            case 4:
                return '#F7DEFF';
            case 5:
                return '#FFDBD3';
            case 6:
                return '#86FFCF';
            case 7:
                return '#86FFCF';
            default:
                return '#DEE4FF';
        }

    return '';
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
        $printed = now()->isoFormat('D, MMM, YY - HH:mm');

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
        $generated = now()->isoFormat('D, MMM, YY HH:mm');

        return __('Generated').': '.$generated;        
    }
}

if (! function_exists('number_formatted')) {
    /**
     * Return the generated number formatted.
     *
     * @return boolean
     */
    function number_formatted($number)
    {
        return number_format($number, strlen(substr(strrchr($number, "."), 2, '.', '')));
    }
}

if (! function_exists('formatNumberToTime')) {

    function formatNumberToTime($decimalMinutes)
    {
        // Convertir el número decimal a segundos
        $totalSeconds = round($decimalMinutes * 60);

        // Obtener horas, minutos y segundos
        $hours = floor($totalSeconds / 3600);
        $minutes = floor(($totalSeconds % 3600) / 60);
        $seconds = $totalSeconds % 60;

        // Formatear horas, minutos y segundos
        $formattedTime = sprintf('%d:%02d:%02d', $hours, $minutes, $seconds);

        return $formattedTime;
    }
}

if (! function_exists('formatTime')) {

    function formatTime($time)
    {
        $pattern = '/^([01]?[0-9]|2[0-3]):[0-5][0-9]:[0-5][0-9]$/';

        if (preg_match($pattern, $time)) {
            // Convertir el tiempo a una instancia de Carbon
            $time = \Carbon\Carbon::createFromFormat('H:i:s', $time);

            // Si la hora es 0, devolver solo minutos y segundos
            if ($time->hour == 0) {
                return $time->format('i:s');
            }

            // De lo contrario, devolver horas, minutos y segundos
            return $time->format('H:i:s');
        }

        return $time;
    }
}