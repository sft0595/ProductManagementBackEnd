<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    //not showing the pivot while returning data  
    // protected $hidden = ['pivot'];
    protected $guarded = [
        'id', 'category_id'
    ];

    //products belongs to many categories
    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }

    //products has many categories
    public function varities()
    {
        return $this->hasMany(Varity::class);
    }

    //product belongs to many attribute values
    public function attrvalues()
    {
        return $this->belongsToMany(Attrvalue::class);
    }


    //products can have many images in image table morphologically 
    public function images()
    {
        return $this->morphMany(Image::class, 'imageable');
    }
}
