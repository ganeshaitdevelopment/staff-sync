<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\Leave;

class LeaveStatusUpdated extends Mailable
{
    use Queueable, SerializesModels;

    public $leave;

    public function __construct(Leave $leave)
    {
        $this->leave = $leave;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Leave Request ' . ucfirst($this->leave->status) . ' - StaffSync',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.leave_status',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}