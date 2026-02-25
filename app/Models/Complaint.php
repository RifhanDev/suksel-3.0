<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Complaint extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'email',
        'subject',
        'module',
        'content',
        'admin_reply',
        'replied_by',
        'replied_at'
    ];

    /**
     * Get the display label for the complaint module (main issue).
     */
    public function getModuleLabelAttribute(): ?string
    {
        if (empty($this->module)) {
            return null;
        }
        $modules = config('complaint_modules.modules', []);
        return $modules[$this->module] ?? $this->module;
    }

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

    /**
     * Get the user that owns the complaint.
     */
    public function user()
    {
        return $this->belongsTo('App\User', 'user_id');
    }

    /**
     * Get the admin user who replied to the complaint.
     */
    public function repliedBy()
    {
        return $this->belongsTo('App\User', 'replied_by');
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
