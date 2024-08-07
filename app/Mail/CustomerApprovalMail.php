<?php

namespace App\Mail;

use App\Models\CustomerMaster;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CustomerApprovalMail extends Mailable
{
    use Queueable, SerializesModels;

    public $customerMaster;
    public $approvalLink;
    public $approvalName;

    /**
     * Create a new message instance.
     *
     * @param CustomerMaster $customerMaster
     * @param string $approvalLink
     * @param string $approvalName
     */
    public function __construct(CustomerMaster $customerMaster, $approvalLink, $approvalName)
    {
        $this->customerMaster = $customerMaster;
        $this->approvalLink = $approvalLink;
        $this->approvalName = $approvalName;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Customer Approval Required')
                    ->view('emails.customer_approval')
                    ->with([
                        'customerMaster' => $this->customerMaster,
                        'approvalLink' => $this->approvalLink,
                        'approvalName' => $this->approvalName,
                    ]);
    }
}
