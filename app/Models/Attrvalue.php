<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attrvalue extends Model
{
    use HasFactory;

    // protected $hidden = ['pivot'];
    protected $guarded = [];

    // attribute values belong to attribute
    public function attribute()
    {
        return $this->belongsTo(Attribute::class);
    }

    public function products()
    {
        return $this->belongsToMany(Product::class);
    }
}
