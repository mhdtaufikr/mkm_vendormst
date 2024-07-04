<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerMaster extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
     // Define the relationship to VendorChanges
     public function changes()
     {
         return $this->hasMany(CustomerChange::class, 'customer_id', 'id');
     }

     // Define a method to get the current status
     public function currentStatus()
     {
         return $this->changes()->orderBy('created_at', 'desc')->first();
     }
}
