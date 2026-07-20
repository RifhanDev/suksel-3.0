<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VendorSubUser extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'vendor_id',
        'name',
        'email',
        'phone',
        'username',
        'start_date',
        'end_date',
        'password',
        'confirmed',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'confirmed' => 'boolean',
    ];

    /**
     * Get the vendor that owns the sub user.
     */
    public function vendor()
    {
        return $this->belongsTo(\App\Vendor::class);
    }
}
