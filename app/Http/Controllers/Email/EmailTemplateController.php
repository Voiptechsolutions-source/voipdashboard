<?php
namespace App\Http\Controllers\Email;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use App\Models\EmailTemplate;
use App\Models\EmailGroup;
//use App\Models\SentEmail;
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
        return view('email-templates.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:email_templates,name',
            'subject' => 'required|string|max:255',
            'body' => 'required|string',
            'is_active' => 'boolean',
        ]);

        EmailTemplate::create([
            'name' => $request->name,
            'subject' => $request->subject,
            'body' => $request->body,
            'is_active' => $request->is_active ?? true,
        ]);

        return redirect()->route('email-templates.index')->with('success', 'Template created successfully.');
    }

    public function edit($templateId = null)
    {
        $template = $templateId ? EmailTemplate::findOrFail($templateId) : null;
        if (!$template) {
            Log::warning('Template not found for ID: ' . $templateId);
            abort(404, 'Template not found');
        }
        Log::info('Edit method called with template:', $template->toArray());
        return view('email-templates.edit', compact('template'));
    }

    public function update(Request $request, $templateId = null)
    {
        $template = $templateId ? EmailTemplate::findOrFail($templateId) : null;
        if (!$template) {
            Log::warning('Template not found for ID during update: ' . $templateId);
            abort(404, 'Template not found');
        }
        Log::info('Update method called with template:', $template->toArray());
        $request->validate([
            'name' => 'required|string|max:255|unique:email_templates,name,' . $template->id,
            'subject' => 'required|string|max:255',
            'body' => 'required|string',
            'is_active' => 'boolean',
        ]);

        $template->update([
            'name' => $request->name,
            'subject' => $request->subject,
            'body' => $request->body,
            'is_active' => $request->is_active ?? $template->is_active,
        ]);

        return redirect()->route('email-templates.index')->with('success', 'Template updated successfully.');
    }
    public function destroy(EmailTemplate $template)
    {
        $template->delete();
        return redirect()->route('email-templates.index')->with('success', 'Template deleted successfully.');
    }

    public function showSendEmailForm()
    {
        $templates = EmailTemplate::all();
        $leads = Lead::all();
        $groups = EmailGroup::all();
        return view('email-templates.send-email', compact('templates', 'leads', 'groups'));
    }

    public function storeGroup(Request $request)
    {
        $request->validate([
            'group_name' => 'required|string|max:255|unique:email_groups,name',
            'emails' => 'required|array',
            'emails.*' => 'email',
        ]);

        $emails = array_unique(array_filter($request->emails));
        EmailGroup::create([
            'name' => $request->group_name,
            'emails' => $emails,
        ]);

        return redirect()->back()->with('success', 'Group created successfully.');
    }

    public function updateGroup(Request $request, EmailGroup $group)
    {
        $request->validate([
            'emails' => 'required|array',
            'emails.*' => 'email',
        ]);

        $emails = array_unique(array_filter($request->emails));
        $group->update(['emails' => $emails]);

        return redirect()->back()->with('success', 'Group updated successfully.');
    }

    public function deleteGroup(EmailGroup $group)
    {
        $group->delete();
        return redirect()->back()->with('success', 'Group deleted successfully.');
    }

    public function sendEmail(Request $request)
    {
        $request->validate([
            'tab' => 'required|in:add-groups,send-group-email,compose-email',
            'template_id' => 'sometimes|required_if:tab,send-group-email|exists:email_templates,id',
            'group_id' => 'sometimes|required_if:tab,send-group-email|exists:email_groups,id',
            'to_email' => 'sometimes|required_if:tab,compose-email|string',
            'custom_subject' => 'sometimes|required_if:template_id,null,tab,compose-email|string|max:255',
            'custom_body' => 'sometimes|required_if:template_id,null,tab,compose-email|string',
        ]);

        $emailService = new EmailService();
        $result = '';

        Log::info("Processing request with tab: {$request->tab}, full request: ", $request->all());

        if ($request->tab === 'send-group-email') {
            $templateId = $request->template_id;
            Log::info("Attempting to find template with ID: {$templateId}");
            $template = EmailTemplate::where('id', $templateId)
                                    ->where('is_active', true)
                                    ->first();

            if (!$template) {
                Log::warning("Template not found or inactive for ID: {$templateId}. Active templates count: " . EmailTemplate::where('is_active', true)->count());
                return redirect()->back()->with('error', 'Selected template not found or inactive. Please select a valid, active template. Check logs for details.');
            }

            Log::info("Found template: ", $template->toArray());
            $group = EmailGroup::findOrFail($request->group_id);
            $recipients = array_map(function ($email) {
                return ['email' => $email];
            }, $group->emails);

            $result = $emailService->sendBatchEmails($recipients, $template->name);
            // SentEmail::create([
            //     'template_name' => $template->name,
            //     'group_id' => $group->id,
            //     'recipients' => $recipients,
            //     'sent_at' => now(),
            // ]);
        } elseif ($request->tab === 'compose-email') {
            Log::info("Entering compose-email flow");
            $recipients = array_filter(array_map('trim', explode(',', $request->input('to_email'))));
            $validRecipients = [];
            foreach ($recipients as $recipient) {
                if (filter_var($recipient, FILTER_VALIDATE_EMAIL)) {
                    $validRecipients[] = ['email' => $recipient];
                } else {
                    Log::warning("Invalid email skipped: {$recipient}");
                }
            }

            if (empty($validRecipients)) {
                return redirect()->back()->with('error', 'No valid recipients provided.');
            }

            $subject = $request->input('custom_subject');
            $body = $request->input('custom_body');

            if (!$subject || !$body) {
                return redirect()->back()->with('error', 'Subject and Body are required for custom email.');
            }

            Log::info("Sending custom email with subject: {$subject}, recipients count: " . count($validRecipients));
            $result = $emailService->sendBatchEmails($validRecipients, null, ['custom_subject' => $subject, 'custom_body' => $body]);
            // SentEmail::create([
            //     'subject' => $subject,
            //     'body' => $body,
            //     'recipients' => $validRecipients,
            //     'sent_at' => now(),
            // ]);
        }

        Log::info($result);
        return redirect()->back()->with('success', $result);
    }

    public function showSentEmails()
    {
        $sentEmails = SentEmail::orderBy('sent_at', 'desc')->get();
        return view('email-templates.sent-emails', compact('sentEmails'));
    }

    public function getTemplate($id)
    {
        $template = EmailTemplate::findOrFail($id);
        return response()->json([
            'subject' => $template->subject,
            'body' => $template->body,
        ]);
    }
}