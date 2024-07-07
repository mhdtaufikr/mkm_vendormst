<?php

namespace App\Mail;

use App\Models\VendorMaster;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class VendorRemandMail extends Mailable
{
    use Queueable, SerializesModels;

    public $vendorMaster;
    public $remandLink;
    public $remandName;
    public $remarks;

    /**
     * Create a new message instance.
     *
     * @param VendorMaster $vendorMaster
     * @param string $remandLink
     * @param string $remandName
     * @param string $remarks
     */
    public function __construct(VendorMaster $vendorMaster, $remandLink, $remandName, $remarks)
    {
        $this->vendorMaster = $vendorMaster;
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
        return $this->subject('Vendor Remand Notification')
                    ->view('emails.vendor_remand');
    }
}
