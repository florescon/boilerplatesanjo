<?php

namespace App\Http\Livewire\Backend\Charts;

use Livewire\Component;

class GraphEfficiency extends Component
{

public $tasks = [];

public function mount()
{
    $this->tasks = [
        [
            'id' => 'task-1',
            'name' => 'Diseño',
            'startTime' => '03-01-2026',
            'endTime' => '03-15-2026',
            'progress' => 100,
        ],
        [
            'id' => 'task-2',
            'name' => 'Desarrollo',
            'startTime' => '03-10-2026',
            'endTime' => '04-05-2026',
            'progress' => 70,
            'dependency' => 'task-1',
        ],
        [
            'id' => 'task-3',
            'name' => 'Pruebas',
            'startTime' => '04-01-2026',
            'endTime' => '04-15-2026',
            'progress' => 30,
            'dependency' => 'task-2',
        ],
        [
            'id' => 'task-4',
            'name' => 'Producción',
            'startTime' => '04-10-2026',
            'endTime' => '04-20-2026',
            'progress' => 0,
            'dependency' => 'task-3',
        ],
    ];
}
    public function render()
    {
        return view('backend.charts.graph-efficiency');
    }
}
