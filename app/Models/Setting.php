<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'site_phone',
        'site_email',
        'site_address',
        'site_whatsapp',
        'site_facebook',
        'days_orders',
        'retail_price_percentage',
        'average_wholesale_price_percentage',
        'wholesale_price_percentage',
        'iva',
        'round',
        'special_price_percentage',
        'footer',
        'footer_quotation',
        'footer_quotation_production',
    ];
}
