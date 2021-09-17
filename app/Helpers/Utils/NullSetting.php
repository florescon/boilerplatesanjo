<?php

namespace App\Helpers\Utils;

use App\Models\Setting;

class NullSetting extends Setting
{
    protected $attributes = [
        'site_phone' => 'phone',
        'site_email' => 'email',
        'site_address' => 'address',
        'site_whatsapp' => 'whatsapp',
        'site_facebook' => 'facebook',
        'days_orders' => 'days',
    ];
}
