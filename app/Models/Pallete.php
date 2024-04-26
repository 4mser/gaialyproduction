<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pallete extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'code',
        'name',
    ];

    static function getPalletes()
    {
        return self::all()->map(function ($item) {
            $item->status = false;
            return $item;
        })->toArray();
    }
}
