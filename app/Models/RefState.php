<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RefState extends Model
{
    use HasFactory;
    use SoftDeletes;

    
    public $timestamps = true;

    protected $fillable = [
        "description",
        "display_status",
        "created_by",
        "updated_by",
        "deleted_by",
    ];
}
