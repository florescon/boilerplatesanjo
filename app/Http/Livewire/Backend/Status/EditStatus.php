<?php

namespace App\Http\Livewire\Backend\Status;

use Livewire\Component;
use App\Models\Process;
use App\Models\Operation;
use DB;
class EditStatus extends Component
{
    public $processId;
    
    public $routes = [];
    public $operations = [];

    protected $rules = [
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
                    'sequence' => $route->sequence,
                ];
            })
            ->toArray();

        if (empty($this->routes)) {
            $this->routes[] = [
                'operation_id' => '',
                'sequence' => 1,
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
        unset($this->routes[$index]);

        $this->routes = array_values($this->routes);
    }


    public function save()
    {
        $this->validate([
            'routes.*.operation_id' => 'required|exists:operations,id|distinct',
            'routes.*.sequence' => 'required|integer|min:1|distinct',
        ], [
            'routes.*.sequence.distinct' => 'La secuencia no puede repetirse.',
            'routes.*.operation_id.distinct' => 'La estación no puede repetirse.',
        ]);
        
        DB::transaction(function () {
            $process = Process::findOrFail($this->processId);
            $process->routes()->delete();
            foreach ($this->routes as $route) {
                $process->routes()->create([
                    'operation_id' => $route['operation_id'],
                    'sequence' => $route['sequence'],
                ]);
            }
        });

        $this->emit('swal:alert', [
           'icon' => 'success',
            'title'   => __('Updated at'), 
        ]);

    }

    public function render()
    {
        return view('backend.status.livewire.edit-status');
    }
}
