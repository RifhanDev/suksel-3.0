<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TenderPembukaEvaluation extends Model
{
    protected $table = 'tender_pembuka_evaluations';

    protected $fillable = [
        'tender_id',
        'vendor_id',
        'checklist_item_uuid',
        'status_pematuhan',
        'catatan',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'status_pematuhan' => 'integer',
    ];

    // ─────────────────────────────────────────────────────────────────
    // Relationships
    // ─────────────────────────────────────────────────────────────────

    public function tender()
    {
        return $this->belongsTo(\App\Tender::class);
    }

    public function vendor()
    {
        return $this->belongsTo(\App\Vendor::class);
    }

    // ─────────────────────────────────────────────────────────────────
    // Helpers
    // ─────────────────────────────────────────────────────────────────

    /**
     * Returns true if this evaluation marks the item as FAILED (Tiada).
     */
    public function isFailed(): bool
    {
        return (int) $this->status_pematuhan === 0;
    }

    /**
     * Returns true if this evaluation marks the item as PASSED (Ada).
     */
    public function isPassed(): bool
    {
        return (int) $this->status_pematuhan === 1;
    }

    // ─────────────────────────────────────────────────────────────────
    // Scopes
    // ─────────────────────────────────────────────────────────────────

    /**
     * Filter evaluations that failed (Tiada).
     */
    public function scopeFailed($query)
    {
        return $query->where('status_pematuhan', 0);
    }

    /**
     * Filter evaluations for a specific tender.
     */
    public function scopeForTender($query, int $tenderId)
    {
        return $query->where('tender_id', $tenderId);
    }

    /**
     * Filter evaluations for a specific vendor.
     */
    public function scopeForVendor($query, int $vendorId)
    {
        return $query->where('vendor_id', $vendorId);
    }
}
