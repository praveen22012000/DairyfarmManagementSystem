<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\RetailorOrder;
use Illuminate\Support\Facades\Mail;


class CancelOrderAfterApprovedNotification extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
       public $order;

    public function __construct(RetailorOrder $order)
    {
        //
           $this->order=$order;
    }

    
     public function build()
    {
        return $this->subject('Order cancel by retailor After approval')
                    ->view('emails.order_cancel_after_approval_notification');
    }

    /**
     * Get the message envelope.
     */
 /*   public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Cancel Order After Approved Notification',
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
