<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VendorChange extends Model
{
    protected $guarded = ['id']; // assuming your vendor_changes table name

    // Define the relationship to VendorMaster
    public function vendor()
    {
        return $this->belongsTo(VendorMaster::class, 'vendor_id', 'id');
    }
}
