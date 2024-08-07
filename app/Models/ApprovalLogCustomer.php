<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApprovalLogCustomer extends Model
{
    use HasFactory;

    protected $table = 'approval_log_customers';

    protected $fillable = [
        'customer_change_id',
        'approver_id',
        'approval_action',
        'approval_comments',
        'approval_timestamp',
        'approval_level'
    ];

    public function approver()
    {
        return $this->belongsTo(User::class, 'approver_id');
    }

    public function customerChange()
    {
        return $this->belongsTo(CustomerChange::class, 'customer_change_id');
    }
}
