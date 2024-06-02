<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
class Company extends Model
{
    use HasFactory;

    public function getimageAttribute($value)
    {

        $data = ('/company') . "/" . $value;

        if (Storage::disk('public')->has($data)) {
            return asset('/storage/company') . "/" .$value;
        } else {
            return "";
        }
    }
}
