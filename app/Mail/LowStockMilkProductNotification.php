<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\DisposeMilkProducts;

class LowStockMilkProductNotification extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public $available_stock;
    public $dispose_milk_product;

    public function __construct($available_stock,DisposeMilkProducts $dispose_milk_product,)
    {
        //
        $this->available_stock = $available_stock;
        $this->dispose_milk_product = $dispose_milk_product;
    }

    public function build()
    {
        return $this->subject('Low Stock Milk Products')
                    ->view('emails.low_stock_milk_products_notification');
    }
    /**
     * Get the message envelope.
     */
  /*  public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Low Stock Milk Product Notification',
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
