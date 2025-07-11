<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\TaskAssignment;

class ReAssignFarmLaboreNotification extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public $taskassignment;

    public function __construct(TaskAssignment $taskassignment)
    {
        //
        $this->taskassignment=$taskassignment;
    }

    public function build()
    {
        return $this->subject('ReAssign Farm Labore Notification')
                    ->view('emails.re_assign_farm_labore_tasks');
    }

    /**
     * Get the message envelope.
     */
 /*   public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Re Assign Farm Labore Notification',
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
