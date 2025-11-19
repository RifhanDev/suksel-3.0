<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApiContractVendorDetail extends Model
{
    use HasFactory;

    protected $table = "api_tender_agency";
    protected $primaryKey = null;
    public $incrementing = false;
    public $timestamps = false;
}
