<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{

    const SUPER_ADMIN = 1;
    const OWNER = 2;
    const USER = 3;
    const AUDITOR = 4;

    use HasFactory;

    protected $fillable = [
        'name',
        'description',
    ];

    public static function getOptions()
    {
        return self::orderBy('name')->pluck('name', 'id');
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }
}
