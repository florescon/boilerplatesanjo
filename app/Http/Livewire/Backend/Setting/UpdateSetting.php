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
        'state.site_facebook' => 'required|url',
        'state.days_orders' => 'required|integer|min:1|max:999',
    ];

    protected $validationAttributes = [
        'state.site_phone' => 'teléfono',
        'state.site_email' => 'email',
        'state.site_address' => 'dirección',
        'state.site_whatsapp' => 'whatsapp',
        'state.site_facebook' => 'facebook',
        'state.days_orders' => 'días de órdenes',
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
