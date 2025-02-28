<?php

namespace App\Http\Livewire\Backend\Product;

use Illuminate\Support\Collection;
use Livewire\Component;
use App\Models\Material;
use App\Models\Product;
use App\Models\Color;
use App\Models\Size;
use App\Models\Consumption;
use DB;

class ConsumptionProduct extends Component
{
    public $product_id, $updateQuantity, $inputquantities, $inputquantities_difference, $name_color, $name_size, $product_general;

    public $material_id = [];
    public $filters_c = [];
    public $filters_s = [];

    protected $queryString = [
        'updateQuantity' => ['except' => FALSE],
    ];

    protected $listeners = ['filterByColor' => 'filterByColor', 'filterBySize' => 'filterBySize', 'store', 'deletePuntualConsumptions', 'delete' => '$refresh', 'deleteRelationsFeedstock' => '$refresh', 'clearAll' => '$refresh'];

    public function mount(Product $product)
    {
        $this->product_id = $product->id;
        $this->product_general = $product;
    }

    public function quantities(int $product_id): void
    {
        $this->validate([
            'inputquantities.*.consumption' => 'numeric|sometimes',
            'inputquantities_difference.*.consumption' => 'numeric|sometimes',
        ]);

        // dd($this->inputquantities);

        if($this->inputquantities){
            foreach($this->inputquantities as $key => $productos){
                if(isset($productos['consumption']))
                {
                    $consumption_update = Consumption::where('id', $key)->first();
                    $consumption_update->update(['quantity' => $productos['consumption']]);
                }
            }
        }

        if($this->inputquantities_difference){
            foreach($this->inputquantities_difference as $key => $productos){
                if(isset($productos['consumption']))
                {

                    $cons_dif_UpOrCre = Consumption::where('id', $key)->first();

                    $cons_upd_quant = Consumption::updateOrCreate([
                        'product_id' => $cons_dif_UpOrCre->product_id,
                        'material_id' => $cons_dif_UpOrCre->material_id,
                        'color_id' => $this->filters_c[0] ?? null,
                        'size_id' => $this->filters_s[0] ?? null,
                    ]);

                    // dd($cons_upd_quant);

                    $cons_upd_quant->update(['quantity' => $productos['consumption']]);
                }
            }
        }

        $this->emit('clearAll');
        $this->clearAll();

        $this->emit('swal:alert', [
            'icon' => 'success',
            'title'   => __('Amount changed'), 
        ]);
    }

    public function clearAll()
    {
        $this->inputquantities = [];
        $this->inputquantities_difference = [];
        // $this->updateQuantity = FALSE;
    }

public function deletePuntualConsumptions($params)
{
    $product = Product::findOrFail($this->product_id);

    // Eliminar consumos puntuales existentes para este material
    $product->consumption()
        ->where('material_id', $params)
        ->where('puntual', true)
        ->delete();

    // $this->material_id = array_diff($this->material_id, [$params]);

    // Llamar nuevamente a la función store()
    $this->store();

    // Emitir mensaje de éxito
    // $this->emit('swal:alert', [
    //     'icon' => 'success',
    //     'title' => __('Éxito'),
    //     'text' => __('Los consumos puntuales se han eliminado correctamente.'),
    // ]);
}


public function store()
{
    $product = Product::findOrFail($this->product_id);

    $this->validate([
        'material_id' => 'required',
    ]);

    $invalidMaterials = []; // Array para acumular los material_id no válidos
    $validMaterials = [];   // Array para acumular los material_id válidos

    foreach($this->material_id as $material){

        $getMaterial = Material::whereId($material)->first();
        $messageMaterial = $getMaterial->full_name_and_code;

        $materialExists = $product->consumption->contains('material_id', $material);

        $materialExistsSecond = $product->consumption
            ->where('material_id', $material)
            ->where('puntual', false) // Solo considerar materiales no puntuales como existentes
            ->isNotEmpty();

        $isPuntual = (isset($this->filters_c[0]) || isset($this->filters_s[0]));

        // dd(!$materialExistsSecond);

        if(!$materialExistsSecond && $materialExists && !$isPuntual){
            return $this->emit('swal:confirm', [
                'icon' => 'question',
                'title' => 'Confirmación',
                'html' => 'No deben existir consumos puntuales cuando quiero agregarlo como general:<br><br> '.$messageMaterial.'<br> ¿Desea eliminar los consumos puntuales existentes y agregar un consumo general?',
                'confirmText' => 'Confirmar',
                // 'cancelButtonText' => 'Cancelar',
                'method' => 'deletePuntualConsumptions', // Método a ejecutar si se confirma
                'params' => $material,
            ]);
        }

        if (!$materialExists || !$materialExistsSecond) {
            // Verificar si ya existe un registro con los mismos product_id, material_id y color_id
            $existingConsumption = $product->consumption()
                ->where('product_id', $this->product_id)
                ->where('material_id', $material)
                ->where('color_id', $this->filters_c[0] ?? null)
                ->first();

            $existingConsumptionSize = $product->consumption()
                ->where('product_id', $this->product_id)
                ->where('material_id', $material)
                ->where('size_id', $this->filters_s[0] ?? null)
                ->first();

            if (!$existingConsumption || !$existingConsumptionSize) {
                // Si no existe un registro con los mismos valores, guardar el nuevo registro
                $product->consumption()->saveMany([
                    new Consumption([
                        'product_id' => $this->product_id,
                        'material_id' => $material,
                        'color_id' => $this->filters_c[0] ?? null,
                        'size_id' => $this->filters_s[0] ?? null,
                        'puntual' => (isset($this->filters_c[0]) || isset($this->filters_s[0])) ? TRUE : 0,
                    ]),
                ]);

                $validMaterials[] = $messageMaterial; // Acumular los material_id válidos
            } else {
                $invalidMaterials[] = $messageMaterial; // Acumular los material_id no válidos
            }
        } else {
            $invalidMaterials[] = $messageMaterial; // Acumular los material_id no válidos
        }              
    }

    $this->emit('materialReset');


    // Construir el mensaje según los resultados
    $message = '';
    $icon = 'success';
    $title = __('Saved');

    if (!empty($invalidMaterials)) {
        $message .= __('Los siguientes materiales ya existen en el consumo: ') . '<br><br>' . implode('<br> ', $invalidMaterials);
        $icon = !empty($validMaterials) ? 'info' : 'error';
        $title = !empty($validMaterials) ? __('Info') : __('Error');
    }

    if (!empty($validMaterials)) {
        if (!empty($invalidMaterials)) {
            $message .= '<br><br>';
        }
        $message .= __('Los siguientes materiales se agregaron correctamente: ') . '<br><br>' . implode('<br> ', $validMaterials);
    }

    // Emitir el mensaje
    $this->emit('swal:modal', [
        'icon' => $icon,
        'title' => $title,
        'html' => $message,
    ]);
}

    // private function initmodel(Product $product)
    // {
    //  $this->model = Product::with('children', 'consumption')
    //         ->findOrFail($product->id);
    // }

    public function filterBySize($size)
    {
        if (in_array($size, $this->filters_s)) {
            $is = array_search($size, $this->filters_s);
            unset($this->filters_s[$is]);

                $this->name_size = '';

        } else {
            $this->filters_s[] = $size;

            array_shift($this->filters_c);
            $this->name_color = '';

            if(count($this->filters_s) >= 2){
                array_shift($this->filters_s);
            };

            $this->clearAll();
        }
    }

    public function filterByColor($color)
    {
        if (in_array($color, $this->filters_c)) {
            $ix = array_search($color, $this->filters_c);
            unset($this->filters_c[$ix]);

                $this->name_color = '';

        } else {
            $this->filters_c[] = $color;

            array_shift($this->filters_s);
            $this->name_size = '';

            if(count($this->filters_c) >= 2){
                array_shift($this->filters_c);
            };

            $this->clearAll();
        }
    }

    public function applySizeFilter($product)
    {
        if ($this->filters_s) {
            foreach ($this->filters_s as $filter) {     
                // $filter = $this->filters_s;

                $product->with(['consumption' => function ($query) use ($filter) {
                    $query->where('size_id', $filter)->orWhere([['size_id', null], ['color_id', null]]);
                    // ->groupBy('material_id')
                    // ->selectRaw('*, sum(quantity) as sum');
                }]);
            }

            // dd($this->filters_s[0]);

            $this->name_size = Size::withTrashed()->find($this->filters_s[0])->name_strikethrough;

        }

        return null;
    }

    public function applyColorFilter($product)
    {
        if ($this->filters_c) {
            foreach ($this->filters_c as $filter) {     
                // $filter = $this->filters_c;

                $product->with(['consumption' => function ($query) use ($filter) {
                    $query->where('color_id', $filter)->orWhere([['size_id', null], ['color_id', null]]);
                    // ->groupBy('material_id')
                    // ->selectRaw('*, sum(quantity) as sum');
                }]);
            }


            $this->name_color = Color::withTrashed()->find($this->filters_c[0])->name_strikethrough;

        }

        return null;
    }

    public function delete(Consumption $consumption)
    {
        if($consumption)
            $consumption->delete();

       $this->emit('swal:alert', [
            'icon' => 'success',
            'title'   => __('Deleted'), 
        ]);
    }

    public function deleteRelationsFeedstock(Consumption $consumption)
    {
        $product_general = Product::find($consumption->product_id);
        $product_general->consumption()->where('material_id', $consumption->material_id)->delete();

       $this->emit('swal:alert', [
            'icon' => 'success',
            'title'   => __('Deleted'), 
        ]);
    }

    public function render()
    {
        $model = Product::with(['children', 'consumption'
                    => function ($query) {
                            $query->where('color_id', null)->where('size_id', null);
                            // ->selectRaw('*, quantity as sum');
                            // $query->where('color_id', null);
                        }
                ]);

        $this->applyColorFilter($model);

        $this->applySizeFilter($model);

        $model = $model
                ->findOrFail($this->product_id);

        $grouped = $model->consumption->groupBy('material_id');

        $groups = new Collection;
        foreach($grouped as $key => $item) {
            $groups->push([
                'material_id' => $item[0]->material->full_name,
                'part_number' => $item[0]->material->part_number,
                'quantity' => rtrim(rtrim(sprintf('%.8F', $item->sum('quantity')), '0'), "."),
            ]);
        }

        return view('backend.product.livewire.consumption')->with(compact('model', 'groups', 'grouped'));
    }
}
