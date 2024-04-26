<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Operation extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'company_id',
        'operation_type_id',
    ];

    public static function findByProfile($operationId)
    {
        $auth = auth()->user();
        if ($auth->isSuperAdminProfile()) {
            return Operation::findOrFail($operationId);
        } elseif ($auth->isOwnerProfile()) {
            return Operation::where('id', $operationId)->firstOrFail();
        } elseif ($auth->isUserProfile()) {
            return Operation::select('operations.*')
                ->where('operations.id', $operationId)->firstOrFail();
        } else {
            return null;
        }
    }


    public function layers()
    {
        return $this->hasMany(Layer::class);
    }

    public function operationType()
    {
        return $this->belongsTo(OperationType::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class)->withTimestamps();
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
