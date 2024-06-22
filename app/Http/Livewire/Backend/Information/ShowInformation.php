<?php

namespace App\Http\Livewire\Backend\Information;

use Livewire\Component;
use App\Models\Status;
use App\Models\ProductStation;

class ShowInformation extends Component
{
    public function render()
    {
        $statuses = Status::orderBy('level')->where('active', TRUE)->get();

        return view('backend.information.livewire.show-information')->with(compact('statuses'));
    }
}
