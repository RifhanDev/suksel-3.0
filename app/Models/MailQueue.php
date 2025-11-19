<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MailQueue extends Model
{
    use HasFactory;
    use SoftDeletes;

    public $timestamps = true;
    protected $dates = ['email_send_at', 'created_at', 'deleted_at'];

    protected $fillable = [
        "smtp_mail_id",
        "content",
        "config",
        "payload",
        "status",
        "email_send_at",
        "created_by",
        "updated_by",
        "deleted_by",
    ];

    public static function canList()
    {
        if (auth()->check() && auth()->user()->ability(['Admin'], ['MailQueue:list'])) {
            return true;
        } else {
            return false;
        }
    }

    public static function canShow()
    {
        if (auth()->check() && auth()->user()->ability(['Admin'], ['MailQueue:show'])) {
            return true;
        } else {
            return false;
        }
    }

    public static function canCreate()
    {
        if (auth()->check() && auth()->user()->ability(['Admin'], ['MailQueue:create'])) {
            return true;
        } else {
            return false;
        }
    }

    public static function canUpdate()
    {
        if (auth()->check() && auth()->user()->ability(['Admin'], ['MailQueue:update'])) {
            return true;
        } else {
            return false;
        }
    }

    public static function canDelete()
    {
        if (auth()->check() && auth()->user()->ability(['Admin'], ['MailQueue:delete'])) {
            return true;
        } else {
            return false;
        }
    }

    public function SmtpMails()
    {
        return $this->belongsTo(SmtpMails::class, "smtp_mail_id", "id");
    }
}
