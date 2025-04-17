<?php

namespace App\Services;

use App\Models\EmailTemplate;
use Illuminate\Support\Facades\Mail;
use Log;

class EmailService
{
    protected $fromEmail = 'test@voiptechsolution.com';
    protected $fromName = 'Admin';
    protected $batchSize = 20; // Default batch size
    protected $sleepDuration = 1200; // Default 20 minutes in seconds

    public function sendEmail($to, $templateName, $data = [])
    {
        $template = EmailTemplate::where('name', $templateName)->where('is_active', true)->first();

        if (!$template) {
            throw new \Exception('Template not found or inactive');
        }

        $subject = $this->replacePlaceholders($template->subject, $data);
        $body = $this->replacePlaceholders($template->body, $data);

        Mail::send([], [], function ($message) use ($to, $subject, $body) {
            $message->to($to)->subject($subject)->from($this->fromEmail, $this->fromName)->setBody($body, 'text/html');
        });

        Log::info("Email sent to {$to} with subject: {$subject} (Attempted delivery)");
    }

    public function sendBatchEmails(array $recipients, $templateName, $data = [], $batchSize = null, $sleepDuration = null)
    {
        $batchSize = $batchSize ?? $this->batchSize;
        $sleepDuration = $sleepDuration ?? $this->sleepDuration;
        $totalRecipients = count($recipients);
        $batches = ceil($totalRecipients / $batchSize);

        $template = EmailTemplate::where('name', $templateName)->where('is_active', true)->first();

        if (!$template) {
            Log::warning("Template not found or inactive for name: {$templateName}");
            throw new \Exception('Template not found or inactive');
        }

        for ($batch = 0; $batch < $batches; $batch++) {
            $start = $batch * $batchSize;
            $end = min(($batch + 1) * $batchSize, $totalRecipients);
            $batchRecipients = array_slice($recipients, $start, $end - $start);

            foreach ($batchRecipients as $recipient) {
                $email = is_array($recipient) ? $recipient['email'] : $recipient;
                $recipientData = array_merge($data, [
                    'first_name' => $recipient['name'] ?? 'User',
                    'username' => $recipient['name'] ?? 'user123',
                    'email' => $email,
                    'date' => now()->format('Y-m-d'),
                    'unsubscribe_link' => 'https://example.com/unsubscribe'
                ]);

                $subject = $this->replacePlaceholders($template->subject, $recipientData);
                $body = $this->replacePlaceholders($template->body, $recipientData);

                Log::debug('Mail config: ' . json_encode(config('mail')));
                try {
                    Mail::html($body, function ($message) use ($email, $subject) {
                        $message->to($email)->subject($subject)->from($this->fromEmail, $this->fromName);
                    });
                    Log::info("Email sent to {$email} with subject: {$subject} (Attempted delivery)");
                } catch (\Exception $e) {
                    Log::error("Failed to send email to {$email}: " . $e->getMessage());
                }
            }

            $emails = array_map(function ($recipient) {
                return is_array($recipient) ? $recipient['email'] : $recipient;
            }, $batchRecipients);

            Log::info("Sent batch " . ($batch + 1) . " of {$batches} (Emails: " . implode(', ', $emails) . ")");

            if ($batch < $batches - 1) {
                Log::info("Sleeping for {$sleepDuration} seconds...");
                sleep($sleepDuration);
            }
        }

        return "Completed sending {$totalRecipients} emails in {$batches} batches.";
    }

    public function testEmail($to, $templateName, $data = [])
    {
        $template = EmailTemplate::where('name', $templateName)->where('is_active', true)->first();

        if (!$template) {
            throw new \Exception('Template not found or inactive');
        }

        $subject = $this->replacePlaceholders($template->subject, $data);
        $body = $this->replacePlaceholders($template->body, $data);

        Log::debug('Mail config: ' . json_encode(config('mail')));
        try {
            Mail::html($body, function ($message) use ($to, $subject) {
                $message->to($to)->subject($subject)->from($this->fromEmail, $this->fromName);
            });
            Log::info("Test email sent to {$to} with subject: {$subject} (Attempted delivery)");
        } catch (\Exception $e) {
            Log::error("Failed to send test email to {$to}: " . $e->getMessage());
        }
    }

    private function replacePlaceholders($text, $data)
    {
        Log::debug('Input text for replacement: ', ['text' => $text]);
        Log::debug('Available data for replacement: ', $data);

        $result = $text;
        // Extract all placeholders from the text (e.g., {first_name}, {email})
        preg_match_all('/{([^}]+)}/', $text, $matches);
        $placeholders = $matches[1] ?? [];

        foreach ($placeholders as $placeholder) {
            $fullPlaceholder = '{' . $placeholder . '}';
            $value = $data[$placeholder] ?? $fullPlaceholder; // Use data value or keep placeholder if missing
            if ($placeholder === 'date') {
                $value = now()->format('Y-m-d');
            }
            $result = str_replace($fullPlaceholder, $value, $result);
            Log::debug('Replaced ' . $fullPlaceholder . ' with ' . $value);
        }

        Log::debug('Final replaced text: ', ['text' => $result]);
        return $result;
    }
}