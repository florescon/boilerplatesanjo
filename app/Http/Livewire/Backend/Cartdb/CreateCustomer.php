<?php

namespace App\Http\Livewire\Backend\Cartdb;

use Livewire\Component;
use App\Domains\Auth\Models\User;
use App\Exceptions\GeneralException;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CreateCustomer extends Component
{
    public ?string $type = '';

    public ?int $branchId = 0;

    public bool $isMain = false;

    public $name, $phone, $address, $rfc, $email;

    protected $rules = [
        'name' => 'required|min:3|max:100',
        'phone' => 'required|digits:10|numeric',
        'address' => 'nullable|max:100',
        'rfc' => 'nullable|max:100',
        'email' => 'email|min:3|max:255|unique:users',
    ];

    public function mount(string $type, ?int $branchId = 0, ?bool $isMain = false)
    {
        $this->type = $type;
        $this->branchId = $branchId;
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    private function resetInputFields()
    {
        $this->name = '';
        $this->phone = '';
        $this->address = '';
        $this->rfc = '';
        $this->email = '';
    }

    public function createcustomer()
    {
        $this->resetInputFields();
    }

    public function redirectLink()
    {
        if($this->isMain){
            return redirect()->route('admin.order.quotation');
        }

        $link = 'admin.store.'.$this->type;

        return redirect()->route($link);
    }

    public function store()
    {
        $this->validate();

        $user = User::create([
            'type' => User::TYPE_USER,
            'name' => $this->name,
            'email' => $this->email,
            'password' => Str::random(8),
        ]);

        $user->customer()->create([
            'phone' => $this->phone, 
            'address' => $this->address, 
            'rfc' => $this->rfc,
        ]);

        $this->redirectLink();
    }

    public function render()
    {
        return view('backend.cartdb.create-customer');
    }
}
