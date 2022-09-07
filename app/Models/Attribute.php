<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attribute extends Model
{
    use HasFactory;

    // protected $hidden = ['pivot'];
    protected $guarded = ['id'];

    //attributes belongs to many categories
    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }

    //attribute has many values
    public function attrvalues()
    {
        return $this->hasMany(Attrvalue::class);
    }
}
