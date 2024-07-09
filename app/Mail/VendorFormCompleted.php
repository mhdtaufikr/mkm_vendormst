<?php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class VendorFormCompleted extends Mailable
{
    use Queueable, SerializesModels;

    public $vendorMaster;
    public $vendorChange;
    public $pdfPath;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($vendorMaster, $vendorChange, $pdfPath)
    {
        $this->vendorMaster = $vendorMaster;
        $this->vendorChange = $vendorChange;
        $this->pdfPath = $pdfPath;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Vendor Form Completed')
                    ->view('emails.vendor_form_completed')
                    ->attach($this->pdfPath, [
                        'as' => 'vendor_form.pdf',
                        'mime' => 'application/pdf',
                    ]);
    }
}
