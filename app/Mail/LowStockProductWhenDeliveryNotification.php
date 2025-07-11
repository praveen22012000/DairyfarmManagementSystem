<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\MilkProduct;

class LowStockProductWhenDeliveryNotification extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public $available_stock;
    public $milk_product;

    public function __construct(MilkProduct $milk_product,$available_stock)
    {
        //
        $this->milk_product = $milk_product;
        $this->available_stock=  $available_stock;
    }

    public function build()
    {
        return $this->subject('Low Stock Product When Delivery Notification')
                    ->view('emails.low_stock_product_when_delivery_notification');
    }

    /**
     * Get the message envelope.
     */
  /*  public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Low Stock Product When Delivery Notification',
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
