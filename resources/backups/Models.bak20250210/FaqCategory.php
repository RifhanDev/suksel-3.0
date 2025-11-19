<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FaqCategory extends Model
{
    use HasFactory;
    use SoftDeletes;

    public $timestamps = true;
    protected $dates = ['deleted_at'];

    protected $fillable = [
        "name",
        "show_none_btn",
        "created_by",
        "updated_by",
        "deleted_by",
    ];

    public static function canList()
    {
        if (auth()->check() && auth()->user()->ability(['Admin'], ['FaqCategory:list'])) {
            return true;
        } else {
            return false;
        }
    }

    public static function canShow()
    {
        if (auth()->check() && auth()->user()->ability(['Admin'], ['FaqCategory:show'])) {
            return true;
        } else {
            return false;
        }
    }

    public static function canCreate()
    {
        if (auth()->check() && auth()->user()->ability(['Admin'], ['FaqCategory:create'])) {
            return true;
        } else {
            return false;
        }
    }

    public static function canUpdate()
    {
        if (auth()->check() && auth()->user()->ability(['Admin'], ['FaqCategory:update'])) {
            return true;
        } else {
            return false;
        }
    }

    public static function canDelete()
    {
        if (auth()->check() && auth()->user()->ability(['Admin'], ['FaqCategory:delete'])) {
            return true;
        } else {
            return false;
        }
    }
}
