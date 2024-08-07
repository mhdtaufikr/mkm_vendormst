<?php

namespace App\Mail;

use App\Models\CustomerMaster;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CustomerRemandMail extends Mailable
{
    use Queueable, SerializesModels;

    public $customerMaster;
    public $remandLink;
    public $remandName;
    public $remarks;

    /**
     * Create a new message instance.
     *
     * @param CustomerMaster $customerMaster
     * @param string $remandLink
     * @param string $remandName
     * @param string $remarks
     */
    public function __construct(CustomerMaster $customerMaster, $remandLink, $remandName, $remarks)
    {
        $this->customerMaster = $customerMaster;
        $this->remandLink = $remandLink;
        $this->remandName = $remandName;
        $this->remarks = $remarks;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Customer Remand Notification')
                    ->view('emails.customer_remand');
    }
}

