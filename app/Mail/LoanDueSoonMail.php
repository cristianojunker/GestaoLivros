<?php

namespace App\Mail;

use App\Models\Loan;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class LoanDueSoonMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(public Loan $loan) 
    {
        $this->loan->loadMissing(['user', 'book']);
    }

    /**
     * Define o assunto do e-mail.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Aviso de vencimento do empréstimo',
        );
    }

    /**
     * Define o conteúdo do e-mail.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.loans.due-soon',
        );
    }

    /**
     * Define anexos do e-mail.
     */
    public function attachments(): array
    {
        return [];
    }
}