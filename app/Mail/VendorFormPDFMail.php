<?php

// app/Mail/VendorFormPDFMail.php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class VendorFormPDFMail extends Mailable
{
    use Queueable, SerializesModels;

    public $vendorMaster;
    public $pdfPath;

    public function __construct($vendorMaster, $pdfPath)
    {
        $this->vendorMaster = $vendorMaster;
        $this->pdfPath = $pdfPath;
    }

    public function build()
    {
        return $this->view('emails.vendor_form_pdf')
                    ->attach($this->pdfPath)
                    ->with([
                        'vendorMaster' => $this->vendorMaster,
                    ]);
    }
}

