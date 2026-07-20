<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApiDetailVendor extends Model
{
    use HasFactory;

    protected $table = "api_contract_purchase";
    protected $primaryKey = null;
    public $incrementing = false;
    public $timestamps = false;
}
