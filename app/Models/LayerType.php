<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LayerType extends Model
{
    use HasFactory;

    const SHAPEFILE = 1;
    const KML = 2;
    const ORTHOPHOTO = 3;
    const IMAGE = 4;
    const DRAWN = 5;
    const THERMO = 6;

    protected $fillable = [
        'name',
        'description',
    ];

    public function layers()
    {
        return $this->hasMany(Layer::class);
    }
}
