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

if (! function_exists('changeFormatStringDate')) {

    function changeFormatStringDate($dateString)
    {
        // Crear un objeto DateTime desde la cadena de fecha
        $date = DateTime::createFromFormat('Y-m-d', $dateString);

        // Formatear la fecha al nuevo formato
        $formattedDate = $date->format('d-m-Y');

        return $formattedDate;  // Salida: 01-08-2024
    }
}

if (! function_exists('getProductsTableData')) {

    function getProductsTableData($relationData): array
    {
        $products = $relationData;
            
        $productIds = $products->pluck('product_id')->toArray();
        
        // $activeValues = $this->getAvailable($relationData);

        // Agrupar por parent_id primero
        $groupedByParent = $products->groupBy(function($item) {
            return $item->product->parent_id ?? 'no_parent';
        });

        $result = [];


        foreach ($groupedByParent as $parentId => $parentProducts) {
            // Obtener tallas únicas para este parent
            $uniqueSizes = $parentProducts->filter(fn($item) => $item->product->size_id)
                ->map(fn($item) => [
                    'id' => $item->product->size_id,
                    'sort' => $item->product->size->sort ?? $item->product->parent->size->sort ?? 0,
                    'name' => $item->product->size->name ?? $item->product->parent->size->name ?? $item->product->size_id,
                    // 'active' => $activeValues[$item->product_id]->active ?? 0 // Agregar active desde la consulta DB
                ])
                ->unique('id')
                ->sortBy('sort')
                ->values();

            // Agrupar productos por nombre base para este parent
            $groupedProducts = [];
            foreach ($parentProducts as $item) {
                $fullName = $item->product->full_name_clear_sort;
                $baseName = preg_replace('/\s\d+$/', '', $fullName);

                $onlyName = $item->product->only_name;
                $baseNameOnly = preg_replace('/\s\d+$/', '', $onlyName);
                
                if (!isset($groupedProducts[$baseName])) {
                    $groupedProducts[$baseName] = [
                        'name' => $baseNameOnly,
                        'color' => $item->product->parent_id ? $item->product->color_name_clear : '',
                        'color_id' => $item->product->parent_id ? $item->product->color_id : '',
                        'general_code' => $item->product->parent_id ? $item->product->parent->code : $item->product->name,
                        'items' => collect(),
                        'no_size' => null,
                        'no_size_items' => collect() // Nueva colección para almacenar todos los items sin talla
                    ];
                }
                
                if ($item->product->size_id) {
                    $sizeId = $item->product->size_id;
                    // $item->active = 0;

                    // Obtener el valor active para este item
                    // $active = $activeValues[$item->product_id]->active ?? 0;
                    
                    // Si ya existe un producto con este size_id, sumamos las cantidades
                    if (isset($groupedProducts[$baseName]['items'][$sizeId])) {
                        $existingItem = $groupedProducts[$baseName]['items'][$sizeId];
                        $existingItem->input_quantity += $item->input_quantity;
                        $groupedProducts[$baseName]['items'][$sizeId] = $existingItem;
                    } else {
                        // $item->active = $active; // Asignar el valor active al item
                        $groupedProducts[$baseName]['items'][$sizeId] = $item;
                    }
                    
                    // Asegurar que el array sizes tenga el valor active
                    if (!isset($groupedProducts[$baseName]['sizes'][$sizeId])) {
                        $groupedProducts[$baseName]['sizes'][$sizeId] = [
                            // 'active' => $active,
                            // otros campos que necesites
                        ];
                    }
                } else {
                    // Almacenar todos los items sin talla en la colección
                    $groupedProducts[$baseName]['no_size_items']->push($item);
                }
            }

            $parentName = $parentId === 'no_parent' 
                ? 'Servicios' 
                : ($parentProducts->first()->product->parent->name ?? 'Parent '.$parentId);

            $parentCode = $parentId === 'no_parent' 
                ? '' 
                : ($parentProducts->first()->product->parent->code ?? 'Parent '.$parentId);

            // dd($groupedProducts);
            $result[$parentId] = [
                'parent_name' => $parentName,
                'parent_code' => $parentCode,
                'uniqueSizes' => $uniqueSizes,
                'groupedProducts' => $groupedProducts
            ];
        }
        return $result;
    }
}

if (! function_exists('getTableData')) {

    function getTableData($relationData): array
    {

        $groupedData = getProductsTableData($relationData);
        
        $sortedGroups = collect($groupedData)->sortBy(function($group) {
            return strtolower($group['parent_name']);
        });
        
        $tables = [];
        
        foreach ($sortedGroups as $parentId => $data) {
            $sortedRows = collect($data['groupedProducts'])->sortBy(function($product) {
                return strtolower($product['name']);
            })->values();
            
            $sizeTotals = [];
            foreach ($data['uniqueSizes'] as $size) {
                $sizeTotals[$size['id']] = [
                    'quantity' => 0,
                    'amount' => 0
                ];
            }
            $noSizeTotal = ['quantity' => 0, 'amount' => 0];
            $grandTotal = 0;
            $rowQuantity = 0;
            
            $preparedRows = $sortedRows->map(function($product) use ($data, &$sizeTotals, &$noSizeTotal, &$grandTotal, &$rowQuantity) {
                $row = [
                    'name' => $product['name'],
                    // 'product_id' => $product['product_id'],
                    'general_code' => $product['general_code'],
                    'color_product' => $product['color'] ?: 'N/A',
                    'color_id' => $product['color_id'] ?: 'N/A',
                    'sizes' => [],
                    'no_size' => null,
                    'row_total' => 0,
                    'row_quantity' => 0
                ];
            
                // Procesar productos con talla
                foreach ($data['uniqueSizes'] as $size) {
                    if (isset($product['items'][$size['id']])) {
                        $item = $product['items'][$size['id']];
                        $quantity = $item->input_quantity;
                        $active = $item->active;
                        $amount = $quantity * $item->price;
                        
                        $row['sizes'][$size['id']] = [
                            'quantity' => $quantity,
                            'active' => $active,
                            'amount' => $amount,
                            'only_display' => $quantity,
                            'display' => "{$quantity} &nbsp; <small class='font-italic text-primary'>".priceWithoutIvaIncluded($item->price)."</small>"
                        ];
                        
                        $sizeTotals[$size['id']]['quantity'] += $quantity;
                        $sizeTotals[$size['id']]['amount'] += $amount;
                        $row['row_total'] += $amount;
                        $row['row_quantity'] += $quantity;
                    }
                }
                
                // Procesar productos sin talla (ahora manejamos múltiples items)
                if ($product['no_size_items']->isNotEmpty()) {
                    $quantity = 0;
                    $amount = 0;
                    $displayParts = [];
                    
                    foreach ($product['no_size_items'] as $item) {
                        $itemQuantity = $item->input_quantity;
                        $itemAmount = $itemQuantity * $item->price;
                        
                        $quantity += $itemQuantity;
                        $amount += $itemAmount;
                        
                        $displayParts[] = "{$itemQuantity} &nbsp; <small class='font-italic text-primary'>".priceWithoutIvaIncluded($item->price)."</small>";
                    }
                    
                    $row['no_size'] = [
                        'quantity' => $quantity,
                        'amount' => $amount,
                        'only_display' => $quantity,
                        'display' => implode(' + ', $displayParts)
                    ];
                    
                    $noSizeTotal['quantity'] += $quantity;
                    $noSizeTotal['amount'] += $amount;
                    $row['row_total'] += $amount;
                    $row['row_quantity'] += $quantity;
                }
                
                $row['row_total_display'] = priceWithoutIvaIncluded($row['row_total']);
                $grandTotal += $row['row_total'];
                $rowQuantity += $row['row_quantity'];
                
                return $row;
            });
            
            // dd($preparedRows);

            $tables[$parentId] = [
                'parent_name' => $data['parent_name'],
                'parent_code' => $data['parent_code'],
                'headers' => $data['uniqueSizes']->map(fn($size) => [
                    'id' => $size['id'],
                    'name' => $size['name'],
                    'sort' => $size['sort']
                ]),
                'rows' => $preparedRows->toArray(),
                'totals' => [
                    'size_totals' => $sizeTotals,
                    'no_size_total' => $noSizeTotal,
                    'grand_total' => priceWithoutIvaIncluded($grandTotal),
                    'row_quantity' => $rowQuantity,
                ]
            ];
        }
        
        return $tables;
    }

}