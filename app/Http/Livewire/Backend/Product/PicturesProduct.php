<?php

namespace App\Http\Livewire\Backend\Product;

use Livewire\Component;
use App\Models\Product;
use App\Models\Picture;
use Livewire\WithFileUploads;

class PicturesProduct extends Component
{

    use WithFileUploads;

    public $product_id;
    public $filters_c = [];
    public $files = [];

    public $origAllPictures = [];

    public function mount(Product $product)
    {
        $this->product_id = $product->id;
        $this->product_slug = $product->slug;

        $productModel = Product::with('pictures')->find($this->product_id);

        $this->origAllPictures =  $productModel->pictures;
        
        // $this->origAllPictures = Product::where('id', $this->product_id)->with('pictures')->get()->pluck('pictures')[0];


    }

    private function init()
    {
        $this->origAllPictures = Product::where('id', $this->product_id)->with('pictures')->get()->pluck('pictures')[0];
    }


    public function savePictures()
    {

        // $this->validate([
        //     'files' => 'image|max:4096', // 4MB Max
        // ]);


        $pictureToDB = Product::find($this->product_id);

        if($this->files){
            foreach($this->files as $phot){
                $imageName = $phot->store("images",'public');
                $pictureToDB->pictures()->save(new Picture(["picture" => $imageName]));
            }

        }
        
        // $product = Product::findOrFail($this->product_id);
        // $this->initphoto($product);
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
        // dd($imageId);

        $picProduct = Picture::find($imageId);
        $picProduct->delete(); 

        // $productImg = Product::find($this->product_id)->where('id', $imageId)->first();

        // dd($productImg);

        // $product->pictures->where('id', $imageId)->dd();

        $this->emit('swal:alert', [
            'icon' => 'success',
            'title'   => __('Deleted'), 
        ]);

        $this->init();
    }


    public function render()
    {

        $model = Product::with(['children'])->findOrFail($this->product_id);

        // dd($this->origAllPictures);

        return view('backend.product.livewire.pictures')->with(compact('model'));
    }
}
