<?php
namespace App\Http\Controllers\Email;

use App\Http\Controllers\Controller;
use App\Models\Lead; // Use the Lead model
use App\Models\EmailTemplate;
use Illuminate\Http\Request;
use App\Services\EmailService;
use Log;

class EmailTemplateController extends Controller
{
    public function index()
    {
        $templates = EmailTemplate::all();
        return view('email-templates.index', compact('templates'));
    }

    public function create()
    {
        $placeholders = config('email.placeholders');
        return view('email-templates.create', compact('placeholders'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:email_templates,name',
            'subject' => 'required|string|max:255',
            'body' => 'required|string',
        ]);

        $emailTemplate = EmailTemplate::create([
            'name' => $request->name,
            'subject' => $request->subject,
            'body' => $request->body,
            'is_active' => $request->boolean('is_active', true),
            'send_after_2_hours' => $request->has('send_after_2_hours'),
        ]);

        return redirect()->route('email-templates.index')->with('success', 'Template created successfully');
    }

    public function edit(EmailTemplate $emailTemplate)
    {
        $placeholders = config('email.placeholders');
        Log::debug('Template body from database:', ['body' => $emailTemplate->body]);
        return view('email-templates.edit', compact('emailTemplate', 'placeholders'));
    }

    public function update(Request $request, EmailTemplate $emailTemplate)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:email_templates,name,' . $emailTemplate->id,
            'subject' => 'required|string|max:255',
            'body' => 'required|string',
        ]);

        $emailTemplate->update([
            'name' => $request->name,
            'subject' => $request->subject,
            'body' => $request->body,
            'is_active' => $request->boolean('is_active', true),
            'send_after_2_hours' => $request->has('send_after_2_hours'),
        ]);

        return redirect()->route('email-templates.index')->with('success', 'Template updated successfully');
    }

    public function destroy(EmailTemplate $emailTemplate)
    {
        $emailTemplate->delete();
        return redirect()->route('email-templates.index')->with('success', 'Template deleted successfully');
    }

    public function showSendEmailForm()
    {
        $templates = EmailTemplate::all();
        $leads = Lead::where('status', 1)->get(); // Fetch leads with lead_status = 1 (customers)
        return view('email-templates.send-email', compact('templates', 'leads'));
    }
    public function sendEmail(Request $request)
    {
        $request->validate([
            'template_id' => 'required|exists:email_templates,id',
            'lead_ids' => 'required|array', // Changed from customer_ids to lead_ids
            'lead_ids.*' => 'exists:leads,id',
        ]);

        $template = EmailTemplate::findOrFail($request->template_id);
        $leadIds = $request->input('lead_ids');
        $leads = Lead::whereIn('id', $leadIds)->where('status', 1)->get()->map(function ($lead) {
            return ['name' => $lead->name, 'email' => $lead->email];
        })->all();

        $emailService = new EmailService();
        $result = $emailService->sendBatchEmails(
            $leads,
            $template->name, // Use template name as per your existing method
            [] // Additional data if needed
        );

        Log::info($result);

        return redirect()->back()->with('success', $result);
    }
    public function showScheduleReminderForm()
    {
        $templates = EmailTemplate::all();
        return view('email-templates.schedule-reminder', compact('templates'));
    }

    public function scheduleReminder(Request $request)
    {
        $request->validate([
            'template_id' => 'required|exists:email_templates,id',
            'recipient_email' => 'required|email',
            'reminder_delays' => 'required|array',
            'reminder_delays.*' => 'integer|min:1',
        ]);

        $template = EmailTemplate::findOrFail($request->template_id);
        $recipientEmail = $request->recipient_email;
        $delays = array_filter(array_map('trim', explode("\n", $request->input('reminder_delays'))));

        foreach ($delays as $delay) {
            $sendAt = now()->addHours((int)$delay);
            SendEmailJob::dispatch($template, $recipientEmail)->delay($sendAt);
            Log::info("Scheduled reminder for {$recipientEmail} at {$sendAt} with delay {$delay} hours");
        }

        return redirect()->back()->with('success', 'Reminders scheduled successfully for ' . $recipientEmail);
    }
}