<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SmtpMails extends Model
{
    use HasFactory;
    use SoftDeletes;

    public $timestamps = true;
    protected $dates = ['deleted_at'];

    protected $fillable = [
        "mail_server",
        "mail_port",
        "mail_crypto",
        "mail_username",
        "mail_password",
        "mail_message_ratelimit",
        "created_by",
        "updated_by",
        "deleted_by",
    ];

    public static function canList()
    {
        if (auth()->check() && auth()->user()->ability(['Admin'], ['SmtpMails:list'])) {
            return true;
        } else {
            return false;
        }
    }

    public static function canShow()
    {
        if (auth()->check() && auth()->user()->ability(['Admin'], ['SmtpMails:show'])) {
            return true;
        } else {
            return false;
        }
    }

    public static function canCreate()
    {
        if (auth()->check() && auth()->user()->ability(['Admin'], ['SmtpMails:create'])) {
            return true;
        } else {
            return false;
        }
    }

    public static function canUpdate()
    {
        if (auth()->check() && auth()->user()->ability(['Admin'], ['SmtpMails:update'])) {
            return true;
        } else {
            return false;
        }
    }

    public static function canDelete()
    {
        if (auth()->check() && auth()->user()->ability(['Admin'], ['SmtpMails:delete'])) {
            return true;
        } else {
            return false;
        }
    }

    public function getMailCryptoDesc()
    {
        $desc = "";

        switch ($this->mail_crypto) {
            case '1':
                $desc = "tls";
                break;

            case '2':
                $desc = "ssl";
                break;
            
            default:
                # code...
                break;
        }

        return $desc;
    }
}
