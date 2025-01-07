<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Slider extends Model
{
    use HasFactory;

    public function getimageAttribute($value)
    {

        $data = ('/slider') . "/" . $value;

        if (Storage::disk('public')->has($data)) {
            return asset('/storage/slider') . "/" .$value;
        } else {
            return "";
        }
    }
}
