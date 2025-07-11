<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

use App\Models\Vaccine;

class LowStockVaccineNotification extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public $vaccine;
    public $available_stock;

    public function __construct(Vaccine $vaccine,$available_stock,)
    {
        //
        $this->vaccine=$vaccine;
        $this->available_stock=$available_stock;
    }

    public function build()
    {
        return $this->subject('LowVaccineNotification')
                    ->view('emails.low_stock_vaccine_notification');
    }
    /**
     * Get the message envelope.
     */
 /*   public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Low Stock Vaccine Notification',
        );
    }*/

    /**
     * Get the message content definition.
     */
   /* public function content(): Content
    {
        return new Content(
            view: 'view.name',
        );
    }*/

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
