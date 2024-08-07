<?php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CustomerFormCompleted extends Mailable
{
    use Queueable, SerializesModels;

    public $customerMaster;
    public $customerChange;
    public $pdfPath;
    public $userName;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($customerMaster, $customerChange, $pdfPath, $userName)
    {
        $this->customerMaster = $customerMaster;
        $this->customerChange = $customerChange;
        $this->pdfPath = $pdfPath;
        $this->userName = $userName;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Customer Form Completed')
                    ->view('emails.customer_completion')
                    ->attach($this->pdfPath, [
                        'as' => 'customer_form.pdf',
                        'mime' => 'application/pdf',
                    ]);
    }
}
