@extends('head')

@section('morecontent')

    
    <ul class="nav nav-pills">
        <li role="presentation" class="active"><a href="#">Applications</a></li>
        <li role="presentation"><a href="subscriptions">Subscriptions</a></li>    
        <li role="presentation"><a href="profile">Profile</a></li>
    </ul>


    <div class="panel panel-primary">
        <div class="panel-heading">
            <h3 class="panel-title">User Appications</h3>
        </div>
        <div class="panel-body">
            @if (isset($error))
            You have no active applications.  <a href="subscriptions" class="btn btn-info btn-lg active" role="subscribe">Subscribe</a>
            @else
            <div class="well well-sm">
                <a href="{{ url('/convert') }}" class="glyphicon glyphicon-cloud-download"> SC Medicaid Converter</a>

                {!! Form::open(['url'=>'trial', 
                'id'=>'trialForm']) !!}

                {!! Form::hidden('application', 'SC Medicaid Converter') !!}
                {!! Form::hidden('type', 'Standard 14 weeks') !!}

                {!! Form::submit('Start Your 14 Day Trial', ['type'=>'button', 'class'=>'btn btn-primary']) !!}

                {!! Form::close() !!}

            </div>
            @endif
        </div>
    </div>

@endsection
