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
    
    
    public function sendWelcomeEmail(User $user)
    {
         Mail::send('emails.welcome', ['user' => $user], function ($m) use ($user) {
            $m
                    ->to($user->email, $user->name)
                    ->subject('Welcome to XpressTek Software!')
                    ->from($this->from_sender, $this->from_name)
                    ->sender($this->from_sender, $this->from_name);
        });
    }
    
}
