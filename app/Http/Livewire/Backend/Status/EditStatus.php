<?php

namespace App\Http\Livewire\Backend\Status;

use Livewire\Component;
use App\Models\Process;
use App\Models\Operation;
use DB;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Validator;

class EditStatus extends Component
{
    public $processId;
    
    public $routes = [];
    public $operations = [];

    protected $rules = [
        'routes' => 'required|array|min:2',
        'routes.*.operation_id' => 'required|exists:operations,id|distinct',
        'routes.*.sequence' => 'required|integer|min:1|distinct',
    ];

    public function mount($process)
    {
        $this->processId = $process;

        $processModel = Process::findOrFail($this->processId);

        $this->operations = Operation::orderBy('name')->get();

        $this->routes = $processModel->routes
            ->map(function ($route) {
                return [
                    'operation_id' => $route->operation_id,
                    'sequence' => intdiv($route->sequence, 10),
                ];
            })
            ->toArray();


        if (empty($this->routes)) {
            $this->routes = [
                [
                    'operation_id' => '',
                    'sequence' => 1,
                ],
                [
                    'operation_id' => '',
                    'sequence' => 2,
                ],
            ];
        }
    }

    public function addRoute()
    {
        $this->routes[] = [
            'operation_id' => '',
            'sequence' => '',
        ];
    }

    public function removeRoute($index)
    {
        if (count($this->routes) <= 2) {
            $this->emit('swal:alert', [
                'icon' => 'warning',
                'title' => 'Debes tener al menos 2 rutas.',
            ]);

            return;
        }

        unset($this->routes[$index]);

        $this->routes = array_values($this->routes);
    }

    public function save()
    {
        $validator = Validator::make(
            [
                'routes' => $this->routes,
            ],
            [
                'routes' => 'required|array|min:2',
                'routes.*.operation_id' => 'required|exists:operations,id|distinct',
                'routes.*.sequence' => 'required|integer|min:1|max:100|distinct',
            ],
            [
                'routes.min' => 'Debes agregar al menos 2 rutas.',
                'routes.*.operation_id.required' => 'Debes seleccionar una estación.',
                'routes.*.operation_id.exists' => 'La estación seleccionada no es válida.',
                'routes.*.operation_id.distinct' => 'La estación no puede repetirse.',
                'routes.*.sequence.required' => 'Debes indicar una secuencia.',
                'routes.*.sequence.integer' => 'La secuencia debe ser un número entero.',
                'routes.*.sequence.min' => 'La secuencia debe ser mayor o igual a 1.',
                'routes.*.sequence.max' => 'La secuencia debe ser menor o igual a 100.',
                'routes.*.sequence.distinct' => 'La secuencia no puede repetirse.',
            ]
        );

        if ($validator->fails()) {
            $this->emit('swal:alert', [
                'icon' => 'warning',
                'title' => $validator->errors()->first(),
            ]);

            return;
        }

        DB::transaction(function () {
            $process = Process::findOrFail($this->processId);

            $process->routes()->delete();

            foreach ($this->routes as $route) {
                $process->routes()->create([
                    'operation_id' => $route['operation_id'],
                    'sequence' => $route['sequence'] * 10,
                ]);
            }
        });

        $this->emit('swal:alert', [
            'icon' => 'success',
            'title' => __('Updated at'),
        ]);
    }

    public function render()
    {
        return view('backend.status.livewire.edit-status');
    }
}
