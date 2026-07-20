<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApiToken extends Model
{
    use HasFactory;

    protected $fillable = [
        'organization_unit_id',
        'token',
        'status',
    ];

    public static function canList()
    {
        if (auth()->check()) {
            return (auth()->user() && auth()->user()->ability(['Admin'], ['Api:list']));
        } else {
            return false;
        }
    }

    public static function canCreate()
    {
        if (auth()->check()) {
            return (auth()->user() && auth()->user()->ability(['Admin'], ['Api:create']));
        } else {
            return false;
        }
    }

    /* Relationship */

    public function agency()
    {
        return $this->belongsTo('App\Models\OrganizationUnit','organization_unit_id','id');
    }
}
