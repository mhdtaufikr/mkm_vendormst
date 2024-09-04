<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerChange extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    // Define the relationship to approver
    public function approver()
    {
        return $this->belongsTo(User::class, 'approver_id');
    }

    // Define the relationship to logs
    public function logs()
    {
        return $this->hasMany(ApprovalLogCustomer::class, 'customer_change_id', 'id');
    }
    public function approvalLogs()
    {
        return $this->hasMany(ApprovalLogCustomer::class, 'customer_change_id', 'id');
    }
}

