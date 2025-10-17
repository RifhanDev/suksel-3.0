<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Request as Input;

class ExceptionTender extends Model
{
    use HasFactory;

    protected $fillable = [
        'rejection_reason',
        'rejection_template_id',
        'status',
        'tender_id',
        'vendor_id',
        'user_id',
    ];

    public function getStatus()
    {
        switch ($this->status) {
            case 0:
                return 'Belum Diproses';
                break;
            case 1:
                return 'Lulus';
                break;
            case 2:
                return 'Ditolak';
                break;
            
            default:
                # code...
                break;
        }
    }

    /* Relationship */
    
    public function vendor()
    {
        return $this->belongsTo('App\Models\Vendor');
    }

    public function files()
    {
        return $this->morphMany('App\Models\Upload', 'uploadable');
    }

    /* Permission */

    public static function canList()
    {
        if (auth()->check()) {
            return (auth()->user() && auth()->user()->ability(['Admin', 'Agency Admin', 'Agency User'], ['ExceptionTender:list']));
        } else {
            return false;
        }
    }

    public static function canApprove()
    {
        if (auth()->check()) {
            return (auth()->user() && auth()->user()->ability(['Admin', 'Agency Admin', 'Agency User'], ['ExceptionTender:approve']));
        } else {
            return false;
        }
    }

    public static function boot()
    {
        parent::boot();

        self::saved(function ($request) {
            // dd($request);
            Upload::setRules('store');
            $hash = md5($request->vendor->registration);

            $file = Input::file('exception_letter');
            $name = 'Surat Kebenaran Khas';
            $filename = 'exception_letter';

            if ($file && $file->isValid()) {

                $upload = [];
                $upload['path']     = public_path() . '/uploads/' . $hash . '/';
                $upload['url']      = url('uploads/' . $hash);
                $upload['name']     = 'exc_' . $request->vendor->registration . '_' . $request->id . '_' . $filename . '.' . $file->extension();
                $upload['size']     = $file->getSize();
                $upload['type']     = $file->getMimeType();
                $upload['public']   = 0;
                $upload['label']    = $name;

                $upload['cr_approved']      = 0;
                $upload['uploadable_type']  = 'App\Models\ExceptionTender';
                $upload['uploadable_id']    = $request->id;

                $file->move($upload['path'], $upload['name']);

                $new_upload = Upload::where('name', $upload['name'])->where('uploadable_id', $request->id)->first();

                if (!$new_upload) {
                    $new_upload = new Upload;
                }
                $new_upload->fill($upload);
                $new_upload->save();
            }
        });
    }
}
