<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\TaskAssignment;

class StartTaskNotification extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public $assignment;

    public function __construct(TaskAssignment $assignment)
    {
        //
        $this->assignment=$assignment;
    }


    public function build()
    {
        return $this->subject('Start Task Notification')
                    ->view('emails.start_task_notification');
    }

    /**
     * Get the message envelope.
     */
   /* public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Start Task Notification',
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
