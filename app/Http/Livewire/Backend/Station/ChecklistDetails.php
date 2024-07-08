<?php

namespace App\Http\Livewire\Backend\Station;

use Livewire\Component;
use App\Models\Station;
use App\Models\MaterialOrder;
use App\Models\StationPreconsumption;

class ChecklistDetails extends Component
{
    public $station_id;
    public $station;
    public $quantities = [];
    public $received = [];
    public $processed = [];
    protected $listeners = [
        'processPreconsumption', 
    ];


    public function mount(Station $station)
    {
        $this->station_id = $station->id;
        $this->station = $station;

        $this->station->load('material_order.material');

       // Initialize quantities with default values
        $this->initializeQuantities();
    }

    protected function initializeQuantities()
    {
        // Load existing preconsumptions
        $preconsumptions = StationPreconsumption::where('station_id', $this->station_id)->get();

        // Create a map of material_id => quantity
        $preconsumptionMap = $preconsumptions->pluck('quantity', 'material_id')->toArray();
        $preconsumptionRMap = $preconsumptions->pluck('received', 'material_id')->toArray();
        $preconsumptionPMap = $preconsumptions->pluck('processed', 'material_id')->toArray();

        // Initialize quantities
        $this->quantities = $this->station->material_order->groupBy('material_id')->mapWithKeys(function ($group) use ($preconsumptionMap) {
            $material_id = $group[0]->material_id;
            $quantity = $preconsumptionMap[$material_id] ?? null;
            // $quantity = $preconsumptionMap[$material_id] ?? $group->sum('quantity');
            return [$material_id => $quantity];
        })->toArray();

        $this->received = $this->station->material_order->groupBy('material_id')->mapWithKeys(function ($group) use ($preconsumptionRMap) {
            $material_id = $group[0]->material_id;
            $received = $preconsumptionRMap[$material_id] ?? null;
            return [$material_id => $received > 0 ? $received : null];
        })->toArray();

        $this->processed = $this->station->material_order->groupBy('material_id')->mapWithKeys(function ($group) use ($preconsumptionPMap) {
            $material_id = $group[0]->material_id;
            $processed = $preconsumptionPMap[$material_id] ?? null;
            return [$material_id => $processed > 0 ? $processed : null];
        })->toArray();

    }

    public function makeConsumptionManual($material_id, $processCons, $material_order)
    {
        return $this->emit('swal:confirm', [
            'icon' => 'question',
            'title' => 'El consumo se efectuará',
            'html' => 'Salida',
            'confirmText' => '¿Desea confirmar?',
            'method' => 'processPreconsumption',
            'params' => [$material_id, $processCons, $material_order],
        ]);
    }


    public function processPreconsumption($material_id, $processCons, $material_order)
    {
        if ($this->errorInput($material_id, $this->station_id)) {
            return; // Detener la ejecución si se retorna verdadero de errorInput
        }

        if($material_order){ 

            $materialOrder = MaterialOrder::whereId($material_order)->first();

            $stationPre = StationPreconsumption::updateOrCreate(
                [
                    'station_id' => $this->station_id,
                    'material_id' => $material_id,
                ],
                [
                    'processed' => true,
                ]
            );

            if($stationPre->processed == true){
                $this->station->material_order()->create([
                    'order_id' => $this->station->order_id,
                    'product_order_id' => $materialOrder->product_order_id,
                    'material_id' => $material_id,
                    'price' => $materialOrder->price,
                    'unit_quantity' => $materialOrder->unit_quantity,
                    'quantity' => $processCons,
                    'manual' => true,
                ]);
            }

            return redirect()->route('admin.station.checklist_details', $this->station_id);
        }

    }

    public function savePreconsumption($material_id, $getSumQuantity)
    {
        if ($this->errorInput($material_id, $this->station_id)) {
            return; // Detener la ejecución si se retorna verdadero de errorInput
        }

        if($getSumQuantity > $this->quantities[$material_id]){
            return $this->emit('swal:modal', [
                'icon' => 'error',
                'title' => __('La cantidad debe ser mayor'),
            ]);
        }

        $quantity = $this->quantities[$material_id] ?? 0;

        StationPreconsumption::updateOrCreate(
            [
                'station_id' => $this->station_id,
                'material_id' => $material_id,
                'original' => $getSumQuantity,
            ],
            [
                'quantity' => $quantity,
            ]
        );

        $this->emit('swal:modal', [
            'icon' => 'success',
            'title' => __('Saved'),
            'html' => __('Preconsumption saved successfully.'),
        ]);
    }


    public function saveRPreconsumption($material_id, ?float $getQuantity = null, $getSumQuantity)
    {
        $this->validate([
            "received.$material_id" => 'numeric|integer|gt:0',
        ]);

        if ($this->errorInput($material_id, $this->station_id)) {
            return; // Detener la ejecución si se retorna verdadero de errorInput
        }

        if(!$getQuantity){
            return $this->emit('swal:modal', [
                'icon' => 'error',
                'title' => __('La cantidad de entrega debe existir'),
            ]);
        }

        $difference = $getQuantity - $getSumQuantity;

        if($this->received[$material_id] > $difference){
            return $this->emit('swal:modal', [
                'icon' => 'error',
                'title' => __('La cantidad recibida es mayor a la diferencia'),
            ]);
        }

        $received = $this->received[$material_id] ?? 0;

        StationPreconsumption::updateOrCreate(
            [
                'station_id' => $this->station_id,
                'material_id' => $material_id,
            ],
            [
                'received' => $received,
            ]
        );

        $this->emit('swal:modal', [
            'icon' => 'success',
            'title' => __('Saved'),
            'html' => __('Preconsumption saved successfully.'),
        ]);
    }

    private function errorInput($materialId, $stationId)
    {
        $stationPre = StationPreconsumption::where('material_id', $materialId)->where('station_id', $stationId)->first();

        if($stationPre && $stationPre->processed){
            return $this->emit('swal:modal', [
                'icon' => 'warning',
                'title' => __('Not saved'),
                'html' => __('Quantity already processed'),
            ]);
        }
    }

    public function render()
    {
        $groupedMaterials = $this->station->material_order->where('manual', false)->groupBy('material_id')->map(function ($group) {
            return [
                'id' => $group[0]->id,
                'order_id' => $group[0]->order_id,
                'material' => $group[0]->material->full_name,
                'price' => $group[0]->price,
                'unit_quantity' => $group[0]->unit_quantity,
                'updated_at' => $group[0]->updated_at,
                'unit' => $group[0]->material->unit_name_label,
                'sum_quantity' => $group->sum('quantity'),
                'sum' => $group->sum('quantity').' '.$group[0]->material->unit_name_label,
            ];
        });

        return view('backend.station.livewire.checklist-details', [
            'groupedMaterials' => $groupedMaterials
        ]);
    }
}
