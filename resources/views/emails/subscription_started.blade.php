<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>XpressTek Software Subscription Confirmation</title>
</head>
<body>
    <h3>Hello {{ $user->name }}!</h3>
    <div>
        <span>
            This is a confirmation of you recently purchased subscription for XpressTek Software.             
        </span>
    </div>
    <div>
        Your application access is now active and will expire on {{ $expiration }}.
    </div>
    <div>
        Your subscription will renew automatically on its expiration date.
        Please visit your account subscription page to cancel at any time.
    </div>
    <hr />
    <div>
        Please contact sales@xpresstek.net if you have any concerns or questions 
        regarding your subscription.
    </div>
     <hr />
     <div>
         Sincerely,
         
         
         XpressTek Support Team
     </div>
</body>
</html>
