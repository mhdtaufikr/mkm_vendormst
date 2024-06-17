<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VendorMaster extends Model
{
    protected $guarded = ['id'];

    // Define the relationship to VendorChanges
    public function changes()
    {
        return $this->hasMany(VendorChange::class, 'vendor_id', 'id');
    }

    // Define a method to get the current status
    public function currentStatus()
    {
        return $this->changes()->orderBy('created_at', 'desc')->first();
    }
}
