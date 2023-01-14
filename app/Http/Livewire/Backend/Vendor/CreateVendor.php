<?php

namespace App\Http\Livewire\Backend\Vendor;

use App\Models\Vendor;
use Livewire\Component;

class CreateVendor extends Component
{
    public $name, $email, $phone, $city_id, $address, $rfc, $comment;

    protected $rules = [
        'name' => 'min:3|max:50|required',
        'email' => 'required|unique:vendors',
        'phone' => 'nullable|sometimes|numeric|regex:/^\d+(\.\d{1,2})?$/',
        'city_id' => 'required|numeric',
        'address' => 'sometimes|max:100',
        'rfc' => 'sometimes|max:50',
        'comment' => 'min:5|max:200|nullable',
    ];

    protected $validationAttributes = [
        'city_id' => 'ciudad'
    ];

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    private function resetInputFields()
    {
        $this->name = '';
        $this->email = '';
        $this->phone = '';
        $this->address = '';
        $this->rfc = '';
        $this->comment = '';
    }

    public function createmodal()
    {
        $this->resetInputFields();
    }

    public function store()
    {
        try {
            $this->validate();

            $vendor = Vendor::create([
                'name' => $this->name,
                'email' => $this->email,                
                'phone' => $this->phone,                
                'address' => $this->address,                
                'city_id' => $this->city_id,                
                'rfc' => $this->rfc,                
                'comment' => $this->comment ? $this->comment : null,                

            ]);

            // event(new MaterialCreated($vendor));

            session()->flash('message', 'The vendor was successfully created.');
         
            return redirect()->route('admin.vendor.index');

        } catch (Exception $e) {
            DB::rollBack();

            throw new GeneralException(__('There was a problem creating the vendor.'));
        }
    }

    public function render()
    {
        return view('backend.vendor.livewire.create-vendor');
    }
}
