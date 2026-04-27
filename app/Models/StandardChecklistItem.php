<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StandardChecklistItem extends Model
{
    protected $fillable = [
        'uuid',
        'category',
        'type',
        'title',
        'mechanism_default',
        'vendor_action_default',
        'online_form_key',
        'online_form_route',
        'is_active',
        'sort_order',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function getRouteKeyName(): string
    {
        return 'uuid';
    }
}
