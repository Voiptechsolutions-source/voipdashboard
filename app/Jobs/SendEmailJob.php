<?php
namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use App\Models\EmailTemplate;

class SendEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $emailTemplate;
    protected $recipientEmail;

    public function __construct(EmailTemplate $emailTemplate, $recipientEmail = null)
    {
        $this->emailTemplate = $emailTemplate;
        $this->recipientEmail = $recipientEmail ?: 'recipient@example.com'; // Default if not provided
    }

    public function handle()
    {
        // Replace placeholders with dummy values (customize as needed)
        $body = $this->emailTemplate->body;
        $body = str_replace('{username}', 'user123', $body);
        $body = str_replace('{email}', $this->recipientEmail, $body);
        $body = str_replace('{date}', now()->format('Y-m-d'), $body);
        $body = str_replace('{first_name}', explode('@', $this->recipientEmail)[0], $body); // Simple first name
        $body = str_replace('{order_id}', 'ORD' . rand(100000, 999999), $body);
        $body = str_replace('{unsubscribe_link}', 'https://example.com/unsubscribe', $body);

        // Send email
        Mail::html($body, function ($message) {
            $message->to($this->recipientEmail)->subject($this->emailTemplate->subject)->from('admin@example.com', 'Admin');
        });
    }
}
?>