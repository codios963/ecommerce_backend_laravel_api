<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;
    protected $fillable =['name', 'description', 'price', 'sub_category_id','user_id','created_at'];

    public function subcategory(){
        
        return $this->belongsTo(Sub_Category::class);
    }
    public function user(){
        
        return $this->belongsTo(User::class);
    }
    public function reviews(){
        return $this->hasMany(Review::class);
    }
    public function getname($value){
        return ucfirst($value);
    }

    public function getCreatedAtAttribute($value){
        return Carbon::parse($value)->diffForHumans();
    }

    
}
