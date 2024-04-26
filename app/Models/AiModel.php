<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AiModel extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'code',
        'name',
        'description',
        'price',
    ];

    static function getModels()
    {
        return self::all()->map(function ($item) {
            $item->status = false;
            return $item;
        })->toArray();
    }
}
