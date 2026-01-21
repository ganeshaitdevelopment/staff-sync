<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\Leave; // <--- Import Model Leave

class LeaveSubmitted extends Mailable
{
    use Queueable, SerializesModels;

    public $leave; // Variabel untuk membawa data cuti ke email

    // Terima data Leave saat class ini dipanggil
    public function __construct(Leave $leave)
    {
        $this->leave = $leave;
    }

    // Judul Email
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'New Leave Request - StaffSync',
        );
    }

    // Isi Email (View)
    public function content(): Content
    {
        return new Content(
            view: 'emails.leave_submitted', // Kita akan buat file view ini nanti
        );
    }

    public function attachments(): array
    {
        return [];
    }
}