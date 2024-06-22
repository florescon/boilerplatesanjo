<?php

namespace App\Http\Livewire\Backend\Station;

use Livewire\Component;
use App\Models\Station;

class EditStation extends Component
{
    public $station_id;
    public $station;

    public function mount(Station $station)
    {
        $this->station_id = $station->id;
        $this->station = $station;
    }

    public function render()
    {
        return view('backend.station.livewire.edit-station');
    }
}
