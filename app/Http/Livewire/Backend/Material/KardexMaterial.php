<?php

namespace App\Http\Livewire\Backend\Material;

use Livewire\Component;
use App\Models\Material;
use App\Models\MaterialHistory;

class KardexMaterial extends Component
{
    public Material $material;

    public $perPage = '10';

    public $sortField = 'created_at';
    public $sortAsc = false;
    
    public $searchTerm = '';

    public $dateInput = '';
    public $dateOutput = '';

    public $created, $updated, $selected_id, $deleted;

    public function render()
    {
        $materialHistory = MaterialHistory::with('audi')->where('material_id', $this->material->id)->orderByDesc('created_at')->get();

        return view('backend.material.livewire.kardex-material', [
            'materialHistory' => $materialHistory,
        ]);
    }
}
