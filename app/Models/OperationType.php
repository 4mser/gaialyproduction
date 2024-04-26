<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OperationType extends Model
{
    use HasFactory, SoftDeletes;

    const LINEAS_ELECTRICAS = 1;
    const SOLAR_PANELS = 2;
    const WIND_TURBINES = 3;
    const CIVIL_WORKS = 4;

    protected $fillable = [
        'id',
        'name',
    ];

    public static function getOptions()
    {
        return self::orderBy('name')->pluck('name', 'id');
    }

    public function operations()
    {
        return $this->hasMany(Operation::class);
    }
}
