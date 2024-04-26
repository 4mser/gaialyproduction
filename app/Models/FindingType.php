<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FindingType extends Model
{
    use HasFactory, SoftDeletes;

    const CRACK = 1;
    const DEFECT_FAILURE = 2;
    const DIRT = 3;
    const CORROSION_WEAR = 4;
    const UNIDENTIFIED_OBJECT = 5;
    const DANGEROUS_OBJECT = 6;
    const OTHER = 7;

    protected $fillable = [
        'id',
        'name',
        'price',
        'currency',
        'parent_finding_type_id',
        'parent_user_id'
    ];

    public function parentFindingType()
    {
        return $this->belongsTo(FindingType::class, 'parent_finding_type_id');
    }
    
    public function parentUser()
    {
        return $this->belongsTo(User::class, 'parent_user_id');
    }
    
    public static function getOptions()
    {
        return self::orderBy('name')->pluck('name', 'id');
    }
}
