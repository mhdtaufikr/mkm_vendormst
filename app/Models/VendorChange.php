<?php

// VendorChange.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VendorChange extends Model
{
    protected $guarded = ['id'];

    // Define the relationship to VendorMaster
    public function vendor()
    {
        return $this->belongsTo(VendorMaster::class, 'vendor_id', 'id');
    }

    // Relationship to logs ordered by approval_timestamp
    public function logs()
    {
        return $this->hasMany(ApprovalLogVendor::class, 'vendor_change_id', 'id')->orderBy('approval_timestamp', 'desc');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }
}

