<?php

namespace App\Helpers\Utils;

use App\Models\Setting;

class NullSetting extends Setting
{
    protected $attributes = [
        'site_phone' => '000',
        'site_email' => 'email@email.com',
        'site_address' => 'Dirección',
        'site_whatsapp' => 'whatsapp',
        'site_facebook' => 'facebook.com',
        'days_orders' => '30',
    ];
}
