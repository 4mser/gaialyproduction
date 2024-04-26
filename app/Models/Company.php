<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Company extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'parent_company_id',
        'parent_user_id',
    ];

    public static function getOptions()
    {
        return self::orderBy('name')->pluck('name', 'id');
    }

    public function operations()
    {
        return $this->hasMany(Operation::class);
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function parentCompany()
    {
        return $this->belongsTo(Company::class, 'parent_company_id');
    }

    public function parentUser()
    {
        return $this->belongsTo(User::class, 'parent_user_id');
    }
}
