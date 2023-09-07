<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Order extends Model
{
    use HasFactory;

    protected $fillable=['user_id','totalprice'];

    public function user() :BelongsTo{
        return $this->BelongsTo(User::class);
    }

    public function products() :BelongsToMany{

        return $this->belongsToMany(Product::class, 'orderproducts')
                    ->withPivot('quantity', 'price')
                    ->withTimestamps();

    }

}
