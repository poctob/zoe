<html>
    <head>
        <title>SC Medicaid Converter by XpressTek</title>
        <link href="{{ asset('/css/welcome.css') }}" rel="stylesheet">
        <link href="{{ asset('/css/app.css') }}" rel="stylesheet">
    </head>
    <body>

        <div id="wrap">
            <div id="main">
                <div class="page-header">
                    <h2>South Carolina Medicaid Report Converter</h2>
                    <p>This application allows you to convert SC Medicaid reports from PDF format into editable Excel spreadsheets.</p>
                </div>
                <div class="well well-lg">
                    <p>The converter is 100% HIPAA compliant.</p>
                    <ul>
                        <li>All communications are encrypted</li>
                        <li>All uploaded files are immediately purged</li>
                        <li>All output files are purged as soon as they are downloaded</li>
                        <li>We do not store any patient's information</li>
                    </ul>
                    <hr />
                    <p>The converter is available 24/7 from any computer with Internet connection.</p>
                    <hr />
                    <p>Affordable yearly subscription allows unlimited file conversions.</p>
                    <hr />
                    <p>The converter is 100% developed and maintained in South Carolina.</p>

                </div>
                <span><h3>Follow the link in the right panel to register for free 2 week trial.</h3></span>
            </div>

        </div>
        <div id="sidebar">
            <div class="panel panel-default">
                <div class="panel-heading">Login</div>
                <div class="panel-body">
                    @if (count($errors) > 0)
                    <div class="alert alert-danger">
                        <strong>Whoops!</strong> There were some problems with your input.<br><br>
                        <ul>
                            @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    <form role="form" method="POST" action="{{ url('/auth/login') }}">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">

                        <div class="form-group">
                            <label for="email">E-Mail Address</label>

                            <input type="email" class="form-control" name="email" value="{{ old('email') }}">

                        </div>

                        <div class="form-group">
                            <label for="password">Password</label>

                            <input type="password" class="form-control" name="password">

                        </div>

                        <div class="form-group">                             
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" name="remember"> Remember Me
                                </label>
                            </div>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">Login</button>                                                                                                       
                        </div>    
                        <a class="btn btn-link" href="{{ url('/password/email') }}">Forgot Your Password?</a>
                    </form>                       
                </div>                    
            </div>  
            <a class="btn btn-link" href="{{ url('/auth/register') }}">Register</a>
        </div>
        <div id="footer">Copyright 2015 XpressTek LLC.</div>
    </div>

</body>
</html>
