<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class RetailorOrderNotification extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    /*public function __construct()
    {
        //

    }*/

      public $order; // Make the order available in the email view
      public $retailor_order_items; // Add this line

    public function __construct($order,$retailor_order_items)
    {
        $this->order = $order;
        $this->retailor_order_items = $retailor_order_items; // Add this line
    }

    public function build()
    {
        return $this->subject('New Milk Product Order from Retailor')
                    ->view('emails.retailor_order_notification');
    }

    /**
     * Get the message envelope.
     */

   /* public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Retailor Order Notification',
        );
    }*/

    /**
     * Get the message content definition.
     */

  /*  public function content(): Content
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
