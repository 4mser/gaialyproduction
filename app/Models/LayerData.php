<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LayerData extends Model
{
    use HasFactory;

    protected $table = 'layer_data';

    protected $fillable = [
        'value',
        'layer_id',
        'layer_data_type_id',
    ];

    public function layer()
    {
        return $this->belongsTo(Layer::class);
    }

    public function layerDataType()
    {
        return $this->belongsTo(LayerDataType::class);
    }
}
