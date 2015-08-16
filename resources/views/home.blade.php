@extends('app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Home</div>

                <div class="panel-body">
                    You are logged in!

                    <ul class="nav navbar-nav navbar-right">
                        <li><a href="{{ url('/convert') }}">SC Medicaid Converter</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
