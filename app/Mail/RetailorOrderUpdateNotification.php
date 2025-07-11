<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\RetailorOrder;

class RetailorOrderUpdateNotification extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */

    public $retailororder;
    public $retailor_order_items; 

    public function __construct(RetailorOrder $retailororder,$retailor_order_items)
    {
        //
        $this->retailororder = $retailororder;
         $this->retailor_order_items = $retailor_order_items;
    }

    public function build()
    {
        return $this->subject('Your Order Status Update')
                    ->view('emails.retailor_order_items_modification');
    }

    /**
     * Get the message envelope.
     */
 /*   public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Retailor Order Update Notification',
        );
    }*/

    /**
     * Get the message content definition.
     */
 /*   public function content(): Content
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
