<?php

namespace App\Http\Livewire\Backend\Logged;

use Livewire\Component;
use App\Models\Logged;

class ShowLogged extends Component
{
    protected $listeners = ['show'];

    public $logged;

    public $user, $email, $last_login_at, $last_login_ip, $city, $state_name, $postal_code, $lat, $lon, $timezone;

    public function show(Logged $logged)
    {
        $geoip = geoip()->getLocation($logged->last_login_ip);
        $this->user = $logged->user_id ? $logged->user->name : ''; 
        $this->email = $logged->user_id ? $logged->user->email : ''; 
        $this->last_login_at = $logged->last_login_at;
        $this->last_login_ip = $logged->last_login_ip;
        $this->city = $geoip->city;
        $this->state_name = $geoip->state_name;
        $this->postal_code = $geoip->postal_code;
        $this->lat = $geoip->lat;
        $this->lon = $geoip->lon;
        $this->timezone = $geoip->timezone;
    }

    public function render()
    {
        return view('backend.logged.show-logged');
    }
}
