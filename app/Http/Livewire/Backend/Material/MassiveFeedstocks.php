<?php

namespace App\Http\Livewire\Backend\Material;

use Livewire\Component;
use App\Models\Material;
use DB;

class MassiveFeedstocks extends Component
{
    protected $listeners = ['massivemodal', 'postColorSecond' => 'getColorSecond', 'postVendorSecond' => 'getVendorSecond', 'postFamilySecond' => 'getFamilySecond'];

    public ?int $color_second_id = null;
    public ?int $family_second_id = null;
    public ?int $vendor_second_id = null;

    public function getColorSecond($id){
        $this->color_second_id = $id;
    }

    public function getFamilySecond($id){
        $this->family_second_id = $id;
    }

    public function getVendorSecond($id){
        $this->vendor_second_id = $id;
    }

    public $selectedMassive = [];
    public $selectedMassivelabel = [];

    public function massivemodal($selectedMassive)
    {
        $this->selectedMassivelabel = [];

        $this->selectedMassive = $selectedMassive;

        foreach($this->selectedMassive as $mass){
            $value = Material::where('id', $mass)->first();
            array_push($this->selectedMassivelabel, $value->full_name);            
        }
    }

    public function clearSecondVendor()
    {
        $this->emit('clear-second-vendor');
    }

    public function clearSecondFamily()
    {
        $this->emit('clear-second-family');
    }
    public function clearSecondColor()
    {
        $this->emit('clear-second-color');
    }

    public function store()
    {
        $update = null;

        if($this->color_second_id){
            $update['color_id'] = $this->color_second_id;
        }
        if($this->family_second_id){
            $update['family_id'] = $this->family_second_id;
        }
        if($this->vendor_second_id){
            $update['vendor_id'] = $this->vendor_second_id;
        }
        foreach($this->selectedMassive as $massive){

            DB::table('materials')
              ->where('id', $massive)
              ->update(
                $update
            );
        }

        $this->emit('swal:alert', [
            'icon' => 'success',
            'title'   => __('Saved'), 
        ]);

        $this->emit('massiveStore');

        $this->clearSecondVendor();
        $this->clearSecondFamily();
        $this->clearSecondColor();

        return redirect()->route('admin.material.index');
    }

    public function render()
    {
        return view('backend.material.livewire.massive-feedstocks');
    }
}
