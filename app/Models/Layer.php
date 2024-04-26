<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Layer extends Model
{
    use HasFactory, SoftDeletes;

    const SEVERITY_COLORS = [
        1 => '#f44336',
        2 => '#f49236',
        3 => '#ffeb3b',
        4 => '#34D399',
        5 => '#047857',
        6 => '#673ab7',
    ];

    protected $fillable = [
        'name',
        'description',
        'visible',
        'geom',
        'symbology',
        'file_name',
        'file_size',
        'file_extension',
        'hallazgo',
        'layer_type_id',
        'operation_id',
        'user_id',
        'data',
        'width',
        'height',
        'metadata_lat',
        'metadata_lng',
        'metadata_date',
        'metadata_original_name',
        'metadata_model',
        'thermal_data'
    ];

    protected $casts = [
        'thermal_data' => 'array',
    ];

    public function layerData()
    {
        return $this->hasMany(LayerData::class);
    }

    public function layerType()
    {
        return $this->belongsTo(LayerType::class);
    }

    public function operation()
    {
        return $this->belongsTo(Operation::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
