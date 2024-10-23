<?php

namespace App\Mail;

use App\Models\VendorMaster;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class VendorApprovalMail extends Mailable
{
    use Queueable, SerializesModels;

    public $vendorMaster;
    public $approvalLink;
    public $approvalName;

    /**
     * Create a new message instance.
     *
     * @param VendorMaster $vendorMaster
     * @param string $approvalLink
     * @return void
     */
    public function __construct(VendorMaster $vendorMaster, $approvalLink,$approvalName)
    {
        $this->vendorMaster = $vendorMaster;
        $this->approvalLink = $approvalLink;
        $this->approvalName = $approvalName;
;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Approval Required: Supplier Submission')
                    ->view('emails.vendor-approval');
    }
}
