<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TransactionType extends Model
{

    use SoftDeletes;

    const IN = 1;
    const OUT = 2;

    protected $fillable = [
        'transaction_type',
    ];
}
