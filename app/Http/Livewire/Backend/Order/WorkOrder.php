<?php

namespace App\Http\Livewire\Backend\Order;
use App\Models\Order;
use App\Models\Status;
use App\Models\Station;
use App\Models\ProductStation;

use Livewire\Component;

class WorkOrder extends Component
{
    public Status $status;
    public Order $order;

    public $parent_id;
    public $color;
    public $size;

    public $quantities = [];
    public $initialQuantities = []; // Almacena los valores iniciales
    public $saving = []; // Para controlar el estado de los botones

    public bool $floatButton = false;

    protected $queryString = [
        'floatButton' => ['except' => FALSE],
    ];

    protected $listeners = ['quantitiesUpdated' => 'handleQuantitiesUpdate'];

    public function mount($order)
    {
        $this->initializeQuantities();
    }

    protected function initializeQuantities()
    {
        $tablesData = $this->order->getSizeTablesData();
        
        foreach ($tablesData as $parentId => $tableData) {
            foreach ($tableData['rows'] as $rowIndex => $row) {
                foreach ($tableData['headers'] as $header) {
                    if (isset($row['sizes'][$header['id']])) {
                        $initialQty = $row['sizes'][$header['id']]['quantity'] ?? 0;
                        
                        // Guardar en ambos arrays
                        // $this->quantities[$parentId][$rowIndex][$header['id']] = $initialQty;
                        $this->initialQuantities[$parentId][$rowIndex][$header['id']] = $initialQty;
                    }
                }
            }
        }
    }

    public function updatedQuantities($value, $keyPath)
    {
        // Parsear la clave multidimensional (ej: "quantities.1.0.2")
        $keys = explode('.', $keyPath);


        if (count($keys) === 3) {
            $parentId = $keys[0];
            $rowIndex = $keys[1];
            $sizeId = $keys[2];
            
            // Validar que sea un entero
            if (!is_numeric($value) || (int)$value != $value) {
                $this->addError('quantities.'.$keyPath, 'Debe ser un número entero');
                $this->quantities[$parentId][$rowIndex][$sizeId] = 0;
                return;
            }
            
            $value = (int)$value;
            
            // Validar que no sea negativo
            if ($value < 0) {
                // dd('no negativo');
                $this->addError('quantities.'.$keyPath, 'No puede ser negativo');
                $this->quantities[$parentId][$rowIndex][$sizeId] = 0;
                return;
            }
            
            // Validar que no exceda la cantidad inicial
            $initialQty = $this->initialQuantities[$parentId][$rowIndex][$sizeId] ?? 0;
            
            if ($value > $initialQty) {
                // dd('mayor');
                $this->addError('quantities.'.$keyPath, 'No puede exceder la cantidad inicial ('.$initialQty.')');
                $this->quantities[$parentId][$rowIndex][$sizeId] = null;
                // $this->quantities[$parentId][$rowIndex][$sizeId] = $initialQty;
                return;
            }
            
            // Si pasa todas las validaciones, actualizar el valor
            $this->quantities[$parentId][$rowIndex][$sizeId] = $value;
            $this->resetErrorBag('quantities.'.$keyPath);
        }
        else{
            // dd('s');
        }
    }


    public function saveByParent($parentId)
    {
        $this->saving[$parentId] = true;
        

        // dd($this->quantities[$parentId]);

        try {
            // Buscar o crear la estación
            $station = Station::firstOrCreate(
                ['order_id' => $this->order->id, 'parent_code' => $parentId],
                ['status' => 'pending']
            );
            
            // Eliminar registros anteriores para este parent_code
            ProductStation::where('station_id', $station->id)->delete();
            
            // Guardar los nuevos registros
            if (isset($this->quantities[$parentId])) {
                foreach ($this->quantities[$parentId] as $rowIndex => $sizes) {
                    foreach ($sizes as $sizeId => $quantity) {
                        if ($quantity > 0) {
                            ProductStation::create([
                                'station_id' => $station->id,
                                'size_id' => $sizeId,
                                'quantity' => $quantity,
                                'color' => $this->getColorForRow($parentId, $rowIndex),
                                'product_id' => $this->getProductIdForRow($parentId, $rowIndex, $sizeId)
                            ]);
                        }
                    }
                }
            }
            
            // $this->emit('showAlert', 'success', 'Datos guardados correctamente para '.$parentId);

            return $this->emit('swal:modal', [
                'icon' => 'info',
                'title' => 'Datos guardados correctamente para '.$parentId,
            ]);

        } catch (\Exception $e) {
            // $this->emit('showAlert', 'error', 'Error al guardar: '.$e->getMessage());

            return $this->emit('swal:modal', [
                'icon' => 'info',
                'title' => 'Error al guardar: '.$e->getMessage(),
            ]);

        }
        
        $this->saving[$parentId] = false;
    }
    public function render()
    {
        $model = array();

        return view('backend.order.livewire.works')->with([
            'model' => $model,
        ]);
    }
}
