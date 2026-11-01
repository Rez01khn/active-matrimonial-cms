<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Subscriber;
use Mail;
use App\Mail\EmailManager;

class NewsletterController extends Controller
{
    public function __construct()
    {
        $this->middleware(['permission:newsletter'])->only('index');
    }

    public function index(Request $request)
    {
        $users = User::where('user_type', '!=', 'admin')->get();
        $subscribers = Subscriber::latest()->get();
        return view('admin.marketing.newsletters', compact('users', 'subscribers'));
    }

    public function send(Request $request)
    {
        if (env('MAIL_USERNAME') != null) {
            $user_emails = $request->input('user_emails', []);
            $subscriber_emails = $request->input('subscriber_emails', []);
            $all_emails = array_unique(array_filter(array_merge((array)$user_emails, (array)$subscriber_emails)));

            if (!empty($all_emails)) {
                foreach ($all_emails as $key => $email) {
                    $array['view'] = 'emails.newsletter';
                    $array['subject'] = $request->subject;
                    $array['from'] = env('MAIL_USERNAME');
                    $array['content'] = $request->content;

                    try {
                        Mail::to($email)->queue(new EmailManager($array));
                    } catch (\Exception $e) {
                        //
                    }
                }
            }
        } else {
            flash(translate('Please configure SMTP first'))->error();
            return back();
        }

        flash(translate('Newsletter has been sent'))->success();
        return redirect()->route('newsletters.index');
    }

    public function testEmail(Request $request)
    {
        $array['view'] = 'emails.newsletter';
        $array['subject'] = "SMTP Test";
        $array['from'] = env('MAIL_USERNAME');
        $array['content'] = "This is a test email.";

        try {
            Mail::to($request->email)->queue(new EmailManager($array));
        } catch (\Exception $e) {
            //
        }

        flash(translate('An email has been sent.'))->success();
        return back();
    }
}
