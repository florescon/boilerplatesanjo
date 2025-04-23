<?php

namespace App\Http\Livewire\Backend\Material;

use Livewire\Component;
use App\Models\Material;
use App\Models\MaterialHistory;
use App\Models\MaterialOrder;
use Illuminate\Support\Collection;
use Carbon\Carbon;
use Excel;
use App\Exports\KardexMaterialExport;
use Symfony\Component\HttpFoundation\Response;

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

    public function clearFilterDate()
    {
        $this->dateInput = '';
        $this->dateOutput = '';
    }

    public function exportMaatwebsiteCustom($extension)
    {   
        abort_if(!in_array($extension, ['csv','xlsx', 'html', 'xls', 'tsv', 'ids', 'ods']), Response::HTTP_NOT_FOUND);
        return Excel::download(new KardexMaterialExport($this->material, $this->dateInput, $this->dateOutput), 'kardex-material-'.Carbon::now().'.'.$extension);
    }

    public function render()
    {
        // $materialHistory = MaterialHistory::with('audi')->where('material_id', $this->material->id)->orderByDesc('created_at')->get();
        // $materialOrder = MaterialOrder::with('audi','order.user')->where('material_id', $this->material->id)->orderByDesc('created_at')->get();

        // $kardex = $materialHistory->merge($materialOrder)->sortByDesc('created_at');

        $kardex = $this->material->kardexRecords();

        if ($this->dateInput) {
            $inputDate = Carbon::parse($this->dateInput);

            if ($inputDate < Carbon::today()->subYear()) {
                $this->emit('swal:alert', [
                    'icon' => 'warning',
                    'title' => 'Limitado a un año',
                ]);
                $this->clearFilterDate();
            } elseif ($inputDate >= Carbon::tomorrow()) {
                $this->emit('swal:alert', [
                    'icon' => 'warning',
                    'title' => 'No puedes consultar datos del futuro',
                ]);
                $this->clearFilterDate();
            } else {
                // Filtrar la colección por fecha
                $kardex = $kardex->filter(function ($day) {
                    $date = Carbon::parse($day['date']);
                    if (empty($this->dateOutput)) {
                        return $date->greaterThanOrEqualTo($this->dateInput);
                    } else {
                        $outputDate = Carbon::parse($this->dateOutput);
                        return $date->between($this->dateInput, $outputDate);
                    }
                });
            }
        } else {
            // Filtrar por el mes actual
            $kardex = $kardex->filter(function ($day) {
                return Carbon::parse($day['date'])->isCurrentMonth();
            });
        }

        return view('backend.material.livewire.kardex-material', [
            'kardex' => $kardex,
        ]);
    }
}
