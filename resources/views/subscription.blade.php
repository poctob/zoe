@extends('app')

@section('content')
{!! Html::script('https://checkout.stripe.com/checkout.js') !!}
{!! Html::script('js/checkout.js') !!}
{!! Html::style('css/profile.css') !!}
<div class="profile-page">
    <ul class="nav nav-pills">
        <li role="presentation"><a href="applications">Applications</a></li>
        <li role="presentation" class="active"><a href="#">Subscriptions</a></li>    
        <li role="presentation"><a href="profile">Profile</a></li>
    </ul>

    @if (isset($success))
    <div class="alert alert-success" role="alert">{{ $success }}</div>
    @elseif (isset($error))
    <div class="alert alert-danger" role="alert">{{ $error }}</div>
    @endif
    <div class="panel panel-primary">
        <div class="panel-heading">
            <h3 class="panel-title">User Subscriptions</h3>
        </div>
        <div class="panel-body">
            @if (isset($subscription))
            <div class="panel panel-info">
                <div class="panel-heading">
                    <h3 class="panel-title">{{ $subscription['plan'] }}</h3>
                </div>
                <div class="panel-body">
                    <div class="well well-sm">Trial period?: @if ($subscription['is_trial'])
                        Yes
                        @else
                        No
                        @endif
                    </div>

                    <div class="well well-sm">Trial ends: @if ($subscription['is_trial'])
                        {{ $subscription['trial_end']->toFormattedDateString()  }}
                        @else
                        N/A
                        @endif
                    </div>

                    <div class="well well-sm">Subscription expired?: @if ($subscription['expired'])
                        Yes
                        @else
                        No
                        @endif
                    </div>

                    <div class="well well-sm">Subscription ends: @if ($subscription['is_trial'])
                        N/A
                        @else
                        {{ $subscription['subscription_end']->toFormattedDateString()  }}
                        @endif
                    </div>

                    <div class="well well-sm">Credit Card (last four): {{ $subscription['last_four'] }}</div>
                </div>
            </div>
            @else
            <div class="panel panel-info">
                <div class="panel-heading">
                    <h3 class="panel-title">SC Medicare Converter</h3>
                </div>
                <div class="panel-body">
                    {!! Form::open(['url'=>'#', 
                    'id'=>'subscribeForm']) !!}
                    {!! Form::close() !!}
                    <button id="trial" type="button" class="btn btn-info">Start Your Free Trial</button>
                    <button id="checkout" type="button" class="btn btn-primary">Purchase One Year Subscription</button>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
