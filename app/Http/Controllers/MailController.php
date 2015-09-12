<?php

namespace Zoe\Http\Controllers;

use Zoe\User;
use Mail;

/**
 * Description of MailController
 *
 * @author Alex Pavlunenko <alexp@xpresstek.net>
 */
class MailController {

    private $from_name = 'XpressTek Support';
    private $from_sender = 'support@xpresstek.net';

    public function sendWelcomeEmail(User $user) {
        Mail::send('emails.welcome', ['user' => $user],
                function ($m) use ($user) {
            $m
                    ->to($user->email, $user->name)
                    ->bcc($this->from_sender, $this->from_name)
                    ->subject('Welcome to XpressTek Software!')
                    ->from($this->from_sender, $this->from_name)
                    ->sender($this->from_sender, $this->from_name);
        });
    }

    public function sendTrialStartEmail(User $user, $expiration) {
        Mail::send('emails.trial_started',
                ['user' => $user,
            'expiration' => $expiration],
                function ($m) use ($user) {
            $m
                    ->to($user->email, $user->name)
                    ->bcc($this->from_sender, $this->from_name)
                    ->subject('XpressTek Software Trial Information')
                    ->from($this->from_sender, $this->from_name)
                    ->sender($this->from_sender, $this->from_name);
        });
    }

    public function sendTrialEndEmail(User $user) {
        Mail::send('emails.trial_ended', ['user' => $user],
                function ($m) use ($user) {
            $m
                    ->to($user->email, $user->name)
                    ->bcc($this->from_sender, $this->from_name)
                    ->subject('XpressTek Software Trial Expired')
                    ->from($this->from_sender, $this->from_name)
                    ->sender($this->from_sender, $this->from_name);
        });
    }

    public function sendSubscriptionStartEmail(User $user) {
        Mail::send('emails.subscription_started', ['user' => $user],
                function ($m) use ($user) {
            $m
                    ->to($user->email, $user->name)
                    ->bcc($this->from_sender, $this->from_name)
                    ->subject('XpressTek Software Subscription Information')
                    ->from($this->from_sender, $this->from_name)
                    ->sender($this->from_sender, $this->from_name);
        });
    }

    public function sendSubscriptionEndEmail(User $user) {
        Mail::send('emails.subscription_ended', ['user' => $user],
                function ($m) use ($user) {
            $m
                    ->to($user->email, $user->name)
                    ->bcc($this->from_sender, $this->from_name)
                    ->subject('XpressTek Software Subscription Information')
                    ->from($this->from_sender, $this->from_name)
                    ->sender($this->from_sender, $this->from_name);
        });
    }

}
