<?php

namespace App\Http\Livewire\Backend\Product;

use Livewire\Component;
use App\Models\Product;
use App\Models\Color;
use App\Models\Picture;
use Livewire\WithFileUploads;

class PicturesProduct extends Component
{
    use WithFileUploads;

    public $product_id, $product_general, $name_color, $color;
    public $filters_c = [];
    public $files = [];

    public $origAllPictures = [];

    protected $listeners = ['filterByColor' => 'filterByColor'];

    public function mount(Product $product)
    {
        $this->product_id = $product->id;
        $this->product_slug = $product->slug;
        $this->product_general = $product;
    }

    private function init()
    {
        $this->origAllPictures = Product::where('id', $this->product_id)->with('pictures')->get()->pluck('pictures')[0];
    }

    public function filterByColor($color)
    {
        if (in_array($color, $this->filters_c)) {
            $ix = array_search($color, $this->filters_c);
            unset($this->filters_c[$ix]);

                $this->name_color = '';
                $this->color = 'white';

        } else {
            $this->filters_c[] = $color;

            if(count($this->filters_c) >= 2){
                array_shift($this->filters_c);
            };
        }
    }

    public function applyColorFilter($product)
    {
        if ($this->filters_c) {

            foreach ($this->filters_c as $filter) {     
                $product->with(['childrenOnlyColors', 'pictures' => function ($query) use ($filter) {
                    $query->where('color_id', $filter);
                }]);
            }

            $this->name_color = Color::find($this->filters_c[0])->name;
            $this->color = Color::find($this->filters_c[0])->color;
        }

        return null;
    }

    public function savePictures()
    {
        $pictureToDB = Product::find($this->product_id);

        if($this->files){
            foreach($this->files as $phot){
                $imageName = $phot->store("images",'public');
                $pictureToDB->pictures()->save(new Picture(["color_id" => $this->filters_c[0] ?? null, "picture" => $imageName]));
            }
        }
        
        $this->init();

        $this->emit('swal:alert', [
            'icon' => 'success',
            'title'   => __('Saved pictures'), 
        ]);

        $this->redirectHere();
    }

    public function redirectHere()
    {
        return redirect()->route('admin.product.pictures', $this->product_id);
    }

    public function removeFromPicture(int $imageId): void
    {
        $picProduct = Picture::find($imageId);
        $picProduct->delete(); 

        $this->emit('swal:alert', [
            'icon' => 'success',
            'title'   => __('Deleted'), 
        ]);

        $this->init();
    }

    public function render()
    {
        $model = Product::with(['childrenOnlyColors', 'pictures' => function($query){
                    return $query->whereNull('color_id');
                }]);

        $this->applyColorFilter($model);

        $model = $model
                ->findOrFail($this->product_id);

        $origAllPic = $model->pictures;

        return view('backend.product.livewire.pictures')->with(compact('model', 'origAllPic'));
    }
}
