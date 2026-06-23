<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;
use Illuminate\Support\Collection;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id', 
        'quantity',
        'price',
        'comment',
        'type',
        'user_id',
        'branch_id',
        'price_without_tax',
    ];

    /**
     * @return mixed
     */
    public function product()
    {
        return $this->belongsTo(Product::class)->withTrashed();
    }

    /**
     * @return mixed
     */
    public function user()
    {
        return $this->belongsTo(User::class)->withTrashed();
    }

    public function total()
    {
        return $this->price * $this->quantity;
    }

    public function getTotalAttribute()
    {
        return $this->total();
    }

    public function getLastRecordTable()
    {
        return DB::table('carts')->latest('id')->first();
    }


    /**
     * @return mixed
     */
    public function parent()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function consumption_filter()
    {
        return $this->hasManyThrough(Consumption::class, Product::class, 'id', 'product_id', 'product_id', 'parent_id')->with('material');
    }

    public function consumption_filter_grouped()
    {
        $this->consumption_filter()->where('color_id', null);
    }



// public function gettAllConsumptionUngrouped()
// {
//     if ($this->consumption_filter->isNotEmpty()) {
//         $groups = collect();

//         $conditions = [
//             ['color_id', $this->parent->color_id],
//             ['size_id', $this->parent->size_id],
//             [null, null]
//         ];

//         foreach ($conditions as $condition) {
//             $filtered = $this->consumption_filter;

//             if ($condition[0] !== null) {
//                 $filtered = $filtered->where('color_id', $condition[0]);
//             }

//             if ($condition[1] !== null) {
//                 $filtered = $filtered->where('size_id', $condition[1]);
//             } else {
//                 $filtered = $filtered->whereNull('color_id')->whereNull('size_id');
//             }

//             foreach ($filtered as $consumption) {
//                 $groups->push([
//                     'material_id' => $consumption->material_id,
//                     'material_part_number' => $consumption->material->part_number ?? null,
//                     'material_name' => $consumption->material->full_name_clear ?? null,
//                     'stock' => $consumption->material->stock,
//                     'quantity' => $consumption->quantity * $this->quantity,
//                     'unit' => $consumption->quantity,
//                     'unit_measurement' => $consumption->material->unit_measurement ?? null,
//                     'vendor' => $consumption->material->vendor->short_name ?? null,
//                     'family' => $consumption->material->family->name ?? null,
//                     'price' => $consumption->material->price ?? null,
//                 ]);
//             }
//         }

//         return $groups;
//     }

//     return 'empty';
// }

    
    public function gettAllConsumptionUngrouped()
    {
        if($this->consumption_filter->isNotEmpty()){

            $groups0 = new Collection;
            $groups2 = new Collection;
            $groups3 = new Collection;

            // $grouped = $this->consumption_filter;

            foreach ($this->consumption_filter->where('color_id', $this->parent->color_id)->where('secondary_puntual', false) as $consumption) {
                $groups0->push([
                    'material_id' => $consumption->material_id,
                    'material_part_number' => $consumption->material->part_number ?? null,
                    'material_name' => $consumption->material->full_name_clear ?? null,
                    'stock' => $consumption->material->stock,
                    'quantity' => $consumption->quantity * $this->quantity,
                    'unit' => $consumption->quantity,
                    'unit_measurement' => $consumption->material->unit_measurement ?? null,
                    'vendor' => $consumption->material->vendor->short_name ?? null,
                    'family' => $consumption->material->family->name ?? null,
                    'price' => $consumption->material->price,
                ]);
            }

            foreach ($this->consumption_filter->where('size_id', $this->parent->size_id) as $consumption) {
                $groups2->push([
                    'material_id' => $consumption->material_id,
                    'material_part_number' => $consumption->material->part_number ?? null,
                    'material_name' => $consumption->material->full_name_clear ?? null,
                    'stock' => $consumption->material->stock,
                    'quantity' => $consumption->quantity * $this->quantity,
                    'unit' => $consumption->quantity,
                    'unit_measurement' => $consumption->material->unit_measurement ?? null,
                    'vendor' => $consumption->material->vendor->short_name ?? null,
                    'family' => $consumption->material->family->name ?? null,
                    'price' => $consumption->material->price ?? null,
                ]);
            }

            foreach ($this->consumption_filter->whereNull('color_id')->whereNull('size_id') as $consumption) {
                $groups3->push([
                    'material_id' => $consumption->material_id,
                    'material_part_number' => $consumption->material->part_number ?? null,
                    'material_name' => $consumption->material->full_name_clear ?? null,
                    'stock' => $consumption->material->stock,
                    'quantity' => $consumption->quantity * $this->quantity,
                    'unit' => $consumption->quantity,
                    'unit_measurement' => $consumption->material->unit_measurement ?? null,
                    'vendor' => $consumption->material->vendor->short_name ?? null,
                    'family' => $consumption->material->family->name ?? null,
                    'price' => $consumption->material->price ?? null,
                ]);
            }

            $groups = $groups0->concat($groups2)->concat($groups3);
            return $groups;
        }
        
        return 'empty';
    } 

    public function gettAllConsumption()
    {
        if($this->gettAllConsumptionUngrouped() != 'empty'){
            return $this->gettAllConsumptionUngrouped()
                    ->groupBy('material_id')
                    ->map(function ($item) {
                        return [
                            'material' => $item[0]['material_name'],
                            'part_number' => $item[0]['material_part_number'],
                            'price' => $item[0]['price'],
                            'unit' => $item->sum('unit'),
                            'unit_measurement' => $item[0]['unit_measurement'],
                            'vendor' => $item[0]['vendor'],
                            'family' => $item[0]['family'],
                            'quantity' => $item->sum('quantity'),
                            'stock' => $item[0]['stock'],
                        ];
                    }); 
        }

        return 'empty';                                                   
    }

    public function gettAllConsumptionUngroupedSecond($quantity)
    {
        if($this->consumption_filter->isEmpty()){
            return 'empty';
        }

        $groups = collect();

        foreach ($this->consumption_filter as $consumption) {
            $groupKey = null;

            if (($consumption->color_id === $this->parent->color_id)) {

                if($consumption->secondary_puntual === FALSE){
                    $groupKey = 'color';
                }

            } elseif ($consumption->size_id === $this->parent->size_id) {
                $groupKey = 'size';
            } elseif (is_null($consumption->color_id) && is_null($consumption->size_id)) {
                $groupKey = 'none';
            }

            if($consumption->secondary_puntual === TRUE){
                if (($consumption->size_id === $this->parent->size_id)) {
                    $groupKey = 'color';
                }
            }

            if ($groupKey) {
                $groups->push([
                    'material_id' => $consumption->material_id,
                    'material_part_number' => $consumption->material->part_number ?? null,
                    'material_name' => $consumption->material->full_name_clear ?? null,
                    'stock' => $consumption->material->stock,
                    'quantity' => $consumption->quantity * $quantity,
                    'unit' => $consumption->quantity,
                    'unit_measurement' => $consumption->material->unit_measurement ?? null,
                    'vendor' => $consumption->material->vendor->short_name ?? null,
                    'family' => $consumption->material->family->name ?? null,
                    'cloth_width' => $consumption->material->family->cloth_width ?? null,
                    'price' => $consumption->material->price ?? null,
                ]);
            }
        }

        return $groups;
    }

    public function gettAllConsumptionSecond($quantity)
    {
        $consumptions = $this->gettAllConsumptionUngroupedSecond($quantity);

        if ($consumptions === 'empty') {
            return 'empty';
        }

        return $consumptions->groupBy('material_id')->map(function ($items) {
            $firstItem = $items->first();

            return [
                'material' => $firstItem['material_name'],
                'part_number' => $firstItem['material_part_number'],
                'price' => $firstItem['price'],
                'unit' => $items->sum('unit'),
                'unit_measurement' => $firstItem['unit_measurement'],
                'vendor' => $firstItem['vendor'],
                'family' => $firstItem['family'],
                'cloth_width' => $firstItem['cloth_width'],
                'quantity' => $items->sum('quantity'),
                'stock' => $firstItem['stock'],
            ];
        });
    }

    public function setPriceAttribute($value)
    {
        $this->attributes['price'] =  str_replace(',', '', $value);
    }

    public function setPriceWithoutTaxAttribute($value)
    {
        $this->attributes['price_without_tax'] =  str_replace(',', '', $value);
    }

}
