<?php

namespace App\Http\Livewire\Backend\Components;

use Illuminate\Support\Str;
use Livewire\Component;

class EditField extends Component
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
        $entity = $this->model::findOrFail($this->entityId);
        $newName = (string)Str::of($this->newName)->trim()->substr(0, 200); // trim whitespace & more than 100 characters
        $newName = $newName === $this->shortId ? null : $newName; // don't save it as operation name it if it's identical to the short_id

        $entity->{$this->field} = $newName ?? null;
        $entity->save();
        $this->init($this->model, $entity); // re-initialize the component state with fresh data after saving

        $this->emit('swal:alert', [
           'icon' => 'success',
            'title'   => __('Updated'), 
        ]);
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
