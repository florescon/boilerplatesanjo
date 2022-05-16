<?php

namespace App\Http\Livewire\Backend\Departament;

use Livewire\Component;
use App\Models\Departament;
use App\Domains\Auth\Models\User;

class CreateDepartament extends Component
{
    public $name, $email, $comment, $user_id;

    public ?string $phone = null;
    public ?string $address = null;
    public ?string $rfc = null;

    public ?string $type_price = User::PRICE_RETAIL;

    protected $rules = [
        'user_id' => ['required'],
        'name' => ['required', 'min:3'],
        'email' => ['required', 'email', 'min:3', 'regex:/^\S*$/u', 'unique:departaments'],
        'comment' => ['nullable', 'min:3', 'max:100'],
        'phone' => ['nullable', 'digits:10'],
        'address' => ['sometimes', 'max:100'],
        'rfc' => ['sometimes', 'max:50'],
        'type_price' => ['nullable'],
    ];

    public function store()
    {
        $validatedData = $this->validate();

        Departament::create($validatedData);

        $this->resetInputFields();
        $this->emit('departamentStore');

        $this->emit('swal:alert', [
            'icon' => 'success',
            'title'   => __('Created'), 
        ]);
    }

    private function resetInputFields()
    {
        $this->name = '';
        $this->email = '';
        $this->comment = '';
        $this->phone = '';
        $this->address = '';
        $this->rfc = '';
    }

    public function render()
    {
        return view('backend.departament.livewire.create-departament');
    }
}
