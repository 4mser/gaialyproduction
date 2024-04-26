<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BillingPlan extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'position',
        'code',
        'title',
        'subtitle',
        'description',
        'credits',
        'price',
    ];
}
