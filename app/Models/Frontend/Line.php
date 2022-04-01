<?php

namespace App\Models\Frontend;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Frontend\Product;

class Line extends Model
{
    use HasFactory;

    /**
     * The products that belong to the line.
     */
    public function products()
    {
        return $this->hasMany(Product::class);
    }

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'lines';    

}
