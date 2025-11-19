<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Request as Input;
use Carbon\Carbon;

class Refund extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaction_id',
        'name',
        'ic',
        'tel',
        'address',
        'bank_id',
        'bank_acc',
        'bank_address',
        'amount',
        'application_letter',
        'bank_statement1',
        'bank_statement2',
        'screenshot',
        'remark',
        'rejection_reason',
        'rejection_template_id',
        'status',
        'vendor_id',
        'user_id',
    ];

    static $files = [
        'refund'      => ['application_letter', 'bank_statement1', 'bank_statement2', 'screenshot_problem'],
    ];

    static $file_names = [
        'application_letter' => 'Surat Permohonan Pemulangan Semula',
        'bank_statement1' => 'Resit Bank',
        'bank_statement2' => 'Penyata Akaun Bank Pemohon',
        'screenshot_problem' => 'Tangkapan Skrin',
    ];

    public function refundStatus()
    {
        switch ($this->status) {
            case 0:
                return 'Dalam Semakan BPM';
                break;

            case 1:
                return 'Lulus (BPM)';
                break;

            case 2:
                return 'Ditolak (BPM)';
                break;

            case 3:
                return 'Terima Bukti (BKP)';
                break;

            case 4:
                return 'Bukti Ditolak (BKP)';
                break;

            default:
                # code...
                break;
        }
    }

    /**
     * Relationships
     */

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function vendor()
    {
        return $this->belongsTo('App\Vendor');
    }

    public function transaction()
    {
        return $this->belongsTo('App\Models\Transaction');
    }

    public function banks()
    {
        return $this->belongsTo('App\Models\BankList', 'bank_id', 'id');
    }

    public function files()
    {
        return $this->morphMany('App\Models\Upload', 'uploadable');
    }

    public static function isRoleBKP()
    {
        if (auth()->check()) {
            return auth()->user()->ability(['Admin', 'Admin Kewangan'], []);
        } else {
            return false;
        }
    }

    public static function canApprove()
    {
        if (auth()->check()) {
            return auth()->user()->ability(['Admin', 'Admin Kewangan'], ['Refund:approve']);
        } else {
            return false;
        }
    }

    public static function canList()
    {
        if (auth()->check()) {
            return (auth()->user() && auth()->user()->ability(['Admin'], ['Refund:list']));
        } else {
            return false;
        }
    }

    public static function canCreate()
    {
        if (auth()->check()) {
            $user = auth()->user();

            if ($user->hasRole('Vendor')) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public function canShow()
    {
        if (auth()->check()) {
            $user = auth()->user();

            if ($user->ability(['Admin'], ['Refund:show'])) {
                return true;
            } elseif ($user->hasRole('Vendor') && $user->vendor_id == $this->vendor_id) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public function canUpdate()
    {
        if (auth()->check()) {
            $user = auth()->user();

            if ($user->ability(['Admin'], ['Refund:edit'])) {
                return true;
            } elseif ($user->hasRole('Vendor') && ($user->vendor_id == $this->vendor_id)) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public static function canDelete()
    {
        if (auth()->check()) {
            if (auth()->user() && auth()->user()->ability(['Admin'], ['Refund:delete']))
                return true;
        } else {
            return false;
        }
    }


    public static function pendingRefundRequest()
    {
        return self::where('status', 0);
    }

    public static function pendingRefundRequestCount()
    {
        return self::pendingRefundRequest()->count();
    }

    public static function pendingRefundComplaint()
    {
        return self::where('status', 1);
    }

    public static function pendingRefundComplaintCount()
    {
        return self::pendingRefundComplaint()->count();
    }

    public static function processRefundRequest()
    {
        return self::where('status', 1);
    }

    public static function processRefundRequestCount()
    {
        return self::processRefundRequest()->count();
    }

    public static function successRefundComplaint()
    {
        return self::where('status', 3);
    }

    public static function successRefundComplaintCount()
    {
        return self::successRefundComplaint()->count();
    }

    public static function rejectRefundRequest()
    {
        // return self::whereIn('status', [2, 4]);
        return self::where('status', 2);
    }

    public static function rejectRefundRequestCount()
    {
        return self::rejectRefundRequest()->count();
    }

    public static function rejectRefundComplaint()
    {
        // return self::whereIn('status', [2, 4]);
        return self::where('status', 4);
    }

    public static function rejectRefundComplaintCount()
    {
        return self::rejectRefundComplaint()->count();
    }

    public static function boot()
    {
        parent::boot();

        self::saved(function ($request) {
            // dd($request->all());
            Upload::setRules('store');
            $hash = md5($request->vendor->registration);

            foreach (self::$files['refund'] as $name) {
                $file = Input::file($name);

                if ($file && $file->isValid()) {
                    $upload = [];
                    $upload['path']     = public_path() . '/uploads/' . $hash . '/';
                    $upload['url']      = url('uploads/' . $hash);
                    $upload['name']     = 'ref_' . $request->vendor->registration . '_' . $request->id . '_' . $name . '.' . $file->extension();
                    $upload['size']     = $file->getSize();
                    $upload['type']     = $file->getMimeType();
                    $upload['public']   = 0;
                    $upload['label']    = self::$file_names[$name];

                    $upload['cr_approved']      = 0;
                    $upload['uploadable_type']  = 'App\Models\Refund';
                    $upload['uploadable_id']    = $request->id;

                    $file->move($upload['path'], $upload['name']);
                    
                    $new_upload = Upload::where('name',$upload['name'])->where('uploadable_id',$request->id)->first();

                    if (!$new_upload) {
                        $new_upload = new Upload;
                    }
                    $new_upload->fill($upload);
                    $new_upload->save();

                }
            }
        });
    }
}
