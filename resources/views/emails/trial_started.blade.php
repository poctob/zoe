<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>XpressTek Software Trial Confirmation</title>
</head>
<body>
    <h3>Hello {{ $user->name }}!</h3>
    <div>
        <span>
            This is a confirmation of you recently requested a trial for XpressTek Software.             
        </span>
    </div>
    <div>
        Your trial is now active and will expire on {{ $expiration }}.
    </div>
    <div>
        Upon the expiration, if you would like to continue using the application,
        you will be required to purchase standard subscription.
    </div>
    <hr />
    <div>
        Please contact sales@xpresstek.net if you have any concerns or questions 
        regarding your trial or subscription.
    </div>
     <hr />
     <div>
         Sincerely,
         
         
         XpressTek Support Team
     </div>
</body>
</html>
