<?php

namespace App\Traits;

use App\Models\Student;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Exceptions\GeneralException;
use Exception;

trait withProducts 
{
    public Product $product;

    public function updateCodes(Product $product) {

        $product->load('children');

        DB::beginTransaction();

        try {
            foreach ($product->children as $prod) {
                if($prod->size->short_name && $prod->color->short_name){
                    $prod->update(['code' => $product->code.optional($prod->color)->short_name.optional($prod->size)->short_name]);
                }
            }

        } catch (Exception $e) {
            DB::rollBack();

            throw new GeneralException(__('There was a problem creating codes.'));
        }

        DB::commit();
    }
}