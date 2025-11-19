<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApiTransactionContract extends Model
{
    use HasFactory;

    protected $table = "api_contract_purchase";
    protected $primaryKey = null;
    public $incrementing = false;
    public $timestamps = false;

    public function tender(){
        return $this->belongsTo(Tender::class, 'tender_id');
    }

    public function vendor(){
        return $this->belongsTo(Vendor::class, 'vendor_id');
    }

    public function transaction(){
        return $this->belongsTo(Transaction::class, 'transaction_id');
    }
}
