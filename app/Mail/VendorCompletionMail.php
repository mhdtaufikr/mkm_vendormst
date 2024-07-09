<?php

// app/Mail/VendorCompletionMail.php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class VendorCompletionMail extends Mailable
{
    use Queueable, SerializesModels;

    public $vendorMaster;
    public $userName;

    public function __construct($vendorMaster, $userName)
    {
        $this->vendorMaster = $vendorMaster;
        $this->userName = $userName;
    }

    public function build()
    {
        return $this->view('emails.vendor_completion')
                    ->with([
                        'vendorMaster' => $this->vendorMaster,
                        'userName' => $this->userName,
                    ]);
    }
}
