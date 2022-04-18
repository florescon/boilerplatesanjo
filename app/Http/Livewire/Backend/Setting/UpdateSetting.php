<?php

namespace App\Http\Livewire\Backend\Setting;

use App\Models\Setting;
use Illuminate\Support\Facades\Cache;
use Livewire\Component;

class UpdateSetting extends Component
{
    public $state = [];

    protected $rules = [
        'state.site_phone' => 'required|integer|digits:10',
        'state.site_email' => 'required|email',
        'state.site_address' => 'required|max:200',
        'state.site_whatsapp' => 'required|integer|digits:10',
        'state.site_facebook' => 'required|regex:/^(https?:\/\/)?([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w \.-]*)*\/?$/',
        'state.days_orders' => 'required|integer|min:1|max:999',
        'state.iva' => 'required|integer|min:0|max:20',
        'state.retail_price_percentage' => 'required|integer|min:0|max:50',
        'state.average_wholesale_price_percentage' => 'required|integer|min:0|max:50',
        'state.wholesale_price_percentage' => 'required|integer|min:0|max:50',
        'state.round' => 'required|min:0|max:1',
    ];

    protected $validationAttributes = [
        'state.site_phone' => 'teléfono',
        'state.site_email' => 'email',
        'state.site_address' => 'dirección',
        'state.site_whatsapp' => 'whatsapp',
        'state.site_facebook' => 'facebook',
        'state.days_orders' => 'días de órdenes',
        'state.iva' => 'iva',
        'state.retail_price_percentage' => 'Porcentaje precio menudeo',
        'state.average_wholesale_price_percentage' => 'Porcentaje precio medio mayoreo',
        'state.wholesale_price_percentage' => 'Porcentaje precio mayoreo',
        'state.round' => 'Redondeo',
    ]; 

    public function mount()
    {
        $setting = Setting::first();

        if ($setting) {
            $this->state = $setting->toArray();
        }
    }

    public function updateSetting()
    {
        $setting = Setting::first();

        $this->validate();

        if ($setting) {
            $setting->update($this->state);
        } else {
            Setting::create($this->state);
        }

        Cache::forget('setting');

        $this->emit('swal:alert', [
            'icon' => 'success',
            'title'   => __('Saved settings'), 
        ]);
    }

    public function render()
    {
        return view('backend.setting.livewire.update-setting');
    }
}
