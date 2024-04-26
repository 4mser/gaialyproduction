<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Task extends Model
{
    use HasFactory, Notifiable;

    const STATUS_PENDING = 'pending';
    const TYPE_TILES = 'tiles';
    const TYPE_WEBODM = 'webodm';

    protected $fillable = [
        'status',
        'name',
        'status',
        'uuid',
        'attempts',
        'channel',
        'percent_complete',
        'name',
        'data',
        'exception',
        'user_id',
        'layer_id',
        'type',
    ];

    protected $casts = [
        'data' => 'array'
    ];

    function layer()
    {
        return $this->belongsTo(Layer::class);
    }

    function user()
    {
        return $this->belongsTo(User::class);
    }
}
