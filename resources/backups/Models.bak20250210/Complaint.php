<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Complaint extends Model
{
    use HasFactory;

    protected $fillable = [
        'email',
        'subject',
        'content'
    ];

    public function complaintStatus()
    {
        switch ($this->status) {
            case 0:
                return 'Baru';
                break;

            case 1:
                return 'Ambil Maklum';
                break;

            case 2:
                return 'Dalam Tindakan';
                break;

            case 3:
                return 'Selesai';
                break;

            case 4:
                return 'Ditolak';
                break;

            default:
                # code...
                break;
        }
    }


    /* Permission */

    public static function canList()
    {
        if (auth()->check() && auth()->user()->ability(['Admin'], [])) {
            return true;
        } else {
            return false;
        }
    }

    public static function canShow()
    {
        if (auth()->check() && auth()->user()->ability(['Admin'], [])) {
            return true;
        } else {
            return false;
        }
    }

    public static function canApprove()
    {
        if (auth()->check() && auth()->user()->ability(['Admin'], [])) {
            return true;
        } else {
            return false;
        }
    }
}
