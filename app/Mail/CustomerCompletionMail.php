<?php

namespace App\Mail;

use App\Models\CustomerMaster;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CustomerCompletionMail extends Mailable
{
    use Queueable, SerializesModels;

    public $customerMaster;
    public $userName;

    public function __construct($customerMaster, $userName)
    {
        $this->customerMaster = $customerMaster;
        $this->userName = $userName;
    }

    public function build()
    {
        return $this->view('emails.customer_completion')
                    ->with([
                        'customerMaster' => $this->customerMaster,
                        'userName' => $this->userName,
                    ]);
    }
}

