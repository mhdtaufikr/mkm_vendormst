<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApprovalLogVendor extends Model
{
    use HasFactory;

    public function approver()
    {
        return $this->belongsTo(User::class, 'approver_id');
    }
}
