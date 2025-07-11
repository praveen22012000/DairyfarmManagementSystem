<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\RetailorOrder;

class AssignDeliveryPersonNotification extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */

    public $retailor_order;

    public function __construct(RetailorOrder $retailor_order)
    {
        //
          $this->retailor_order = $retailor_order;
    }

    
    public function build()
    {
        return $this->subject('Assigned To New Delivery')
                    ->view('emails.new_order_assignment_notification');
    }


    /**
     * Get the message envelope.
     */
 /*   public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Assign Delivery Person Notification',
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
