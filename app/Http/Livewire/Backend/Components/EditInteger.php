<?php

namespace App\Http\Livewire\Backend\Components;

use Illuminate\Support\Str;
use Livewire\Component;

class EditInteger extends Component
{
    public $origName;
    public $entityId;
    public ?bool $textDanger = false;
    public $shortId;
    public ?string $text = '';
    public $newName; // dirty operation name state
    public $isName; // determines whether to display it in bold text
    public string $field; // this is can be column. It comes from the blade-view foreach($fields as $field)
    public string $model; // Eloquent model with full name-space

    public function mount($model, $entity, ?string $text = '')
    {
        $this->entityId = $entity->id;
        $this->shortId = $entity->short_id;
        $this->origName = $entity->{$this->field};
        $this->text = $text;

        $this->init($this->model, $entity); // initialize the component state
    }

    public function save()
    {
        $this->validate([
            'newName' => ['required', 'integer', 'min:0', 'not_in:0'],
        ]);

        $entity = $this->model::findOrFail($this->entityId);
        
        // Convertir a entero y luego a string para asegurar que es un número entero
        $newName = (int)$this->newName;
        
        // No necesitamos el trim ni substr para números enteros
        $newName = $newName === $this->shortId ? null : $newName; // don't save it as operation name it if it's identical to the short_id

        $entity->{$this->field} = $newName ?? null;
        $entity->save();
        $this->init($this->model, $entity); // re-initialize the component state with fresh data after saving

        $this->emit('cartUpdated');

        $this->emit('swal:alert', [
           'icon' => 'success',
            'title'   => __('Updated'), 
        ]);
    }

    public function updatedNewName($value)
    {
        // Validación en tiempo real para asegurar que solo sean números enteros
        if (!is_numeric($value) || strpos($value, '.') !== false) {
            $this->textDanger = true;
            $this->addError('newName', 'Solo se permiten números enteros.');
        } else {
            $this->textDanger = false;
            $this->resetErrorBag('newName');
        }
    }

    private function init($model, $entity)
    {
        $this->origName = $entity->{$this->field} ?: $this->shortId;
        $this->newName = $this->origName;
        $this->isName = $entity->{$this->field} ?? false;
    }

    public function render()
    {
        return view('backend.components.edit-field');
    }
}
