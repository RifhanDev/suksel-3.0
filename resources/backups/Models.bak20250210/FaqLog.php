<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FaqLog extends Model
{
    use HasFactory;
    use SoftDeletes;

    public $timestamps = true;
    protected $dates = ['deleted_at'];

    protected $fillable = [
        "faq_category_id",
        "faq_id",
        "user_response",
        "created_by",
        "updated_by",
        "deleted_by",
    ];

    public static function canList()
    {
        if (auth()->check() && auth()->user()->ability(['Admin'], ['Faq:list'])) {
            return true;
        } else {
            return false;
        }
    }

    public static function canShow()
    {
        if (auth()->check() && auth()->user()->ability(['Admin'], ['Faq:show'])) {
            return true;
        } else {
            return false;
        }
    }

    public static function canCreate()
    {
        if (auth()->check() && auth()->user()->ability(['Admin'], ['Faq:create'])) {
            return true;
        } else {
            return false;
        }
    }

    public static function canUpdate()
    {
        if (auth()->check() && auth()->user()->ability(['Admin'], ['Faq:update'])) {
            return true;
        } else {
            return false;
        }
    }

    public static function canDelete()
    {
        if (auth()->check() && auth()->user()->ability(['Admin'], ['Faq:delete'])) {
            return true;
        } else {
            return false;
        }
    }

    public function FaqCategory()
    {
        return $this->belongsTo(FaqCategory::class, "faq_category_id", "id");
    }

    public function Faq()
    {
        return $this->belongsTo(Faq::class, "faq_id", "id");
    }
}
