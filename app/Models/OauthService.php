<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OauthService extends Model
{
    use HasFactory;

    protected $fillable = [
        'oauth_id',
        'oauth_service',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
