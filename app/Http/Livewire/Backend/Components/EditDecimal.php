<?php

namespace App\Http\Livewire\Backend\Components;

use Illuminate\Support\Str;
use Livewire\Component;

class EditDecimal extends Component
{
    public $origName;
    public $entityId;
    public ?bool $textDanger = false;
    public $shortId;
    public ?string $text = '';
    public $newName; // dirty operation name state
    public $isName; // determines whether to display it in bold text
    public string $field; // this can be column. It comes from the blade-view foreach($fields as $field)
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
            'newName' => ['required', 'numeric', 'regex:/^\d+(\.\d{1,2})?$/', 'not_in:0'],
        ]);

        $entity = $this->model::findOrFail($this->entityId);
        
        // Convertir a float y redondear a 2 decimales
        $newName = round((float)$this->newName, 2);
        
        $newName = $newName == $this->shortId ? null : $newName; // don't save if identical to short_id

        if($newName > 0){
            $pricewithIva = priceIncludeIvaFormat($newName);
            $entity->update(['price' => $pricewithIva]);
        }

        $entity->{$this->field} = $newName ?? null;
        $entity->save();
        $this->init($this->model, $entity); // re-initialize the component state

        $this->emit('cartUpdated');

        $this->emit('swal:alert', [
           'icon' => 'success',
            'title' => __('Updated'), 
        ]);
    }

    public function updatedNewName($value)
    {
        // Validación en tiempo real para 2 decimales
        if (!is_numeric($value) || !preg_match('/^\d+(\.\d{0,2})?$/', $value)) {
            $this->textDanger = true;
            $this->addError('newName', 'Solo se permiten números con hasta 2 decimales.');
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