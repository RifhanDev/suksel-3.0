<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VendorHistory extends Model
{
   public $timestamps = false;

    protected $fillable = [
        'action',
        'vendor_id',
        'user_id',
        'remarks'
    ];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function vendor()
    {
        return $this->belongsTo('App\Vendor');
    }

    public static function log($action, $vendor_id, $user_id, $remarks=null)
    {
        if(array_key_exists($action, self::$types))
        {
            $history = new self([
                'action' => $action,
                'vendor_id' => $vendor_id,
                'user_id' => $user_id,
                'remarks' => $remarks
            ]);

            $history->save();
        }
    }

    public static function boot()
    {

    	parent::boot();
        static::creating( function ($model) {
            $model->setCreatedAt($model->freshTimestamp());
        });
    }

    public function getLabelAttribute()
    {
        if(array_key_exists($this->action, self::$types)) {
            return self::$types[$this->action];
        } else {
            boolean_icon(null);
        }
    }

    static $types = [
			'create'    => 'Daftar Ke Dalam Sistem',
			'edit'      => 'Kemaskini Maklumat',
			'delete'    => 'Hapus Dari Sistem',
			'blacklist' => 'Disenarai Hitam',
			'edit-2'    => 'Kemaskini Maklumat Emel/No. Pendaftaran',
			'edit-3'    => 'Kemaskini Nama Syarikat',
			'approve'   => 'Kelulusan Pendaftaran',
			'reject'    => 'Penolakan Pendaftaran',
        '' => null,
        null => null
    ];
}
