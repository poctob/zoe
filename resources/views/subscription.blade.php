@extends('head')

@section('morecontent')
{!! Html::script('https://checkout.stripe.com/checkout.js') !!}
{!! Html::script('js/checkout.js') !!}
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
            <h3 class="panel-title">Subscriptions</h3>
        </div>

        <div class="panel-body">

            @if (isset($subscription))

            <div class="panel panel-info">
                <div class="panel-heading">
                    <h3 class="panel-title">{{ $subscription['plan'] }}</h3>
                </div>
                <div class="panel-body">

                    <div class="well well-sm">Subscription expired?: @if ($subscription['expired'])
                        Yes
                        @else
                        No
                        @endif
                    </div>

                    <div class="well well-sm">Subscription ends: 
                        @if ( isset($subscription['subscription_end']))
                        {{ $subscription['subscription_end']->toFormattedDateString()  }}
                        @else
                        N/A
                        @endif
                    </div>
                    <div class="well well-sm">Credit Card (last four): {{ $subscription['last_four'] }}</div>
                </div>
            </div>
            @endif
        </div>
    </div>
    
    <div class="panel panel-primary">
        <div class="panel-heading">
            <h3 class="panel-title">Trials</h3>
        </div>

        <div class="panel-body">
            
            @if (isset($trials) && count($trials) > 0)

                @foreach ($trials as $trial)
                <div class="panel panel-info">
                    <div class="panel-heading">
                        <h3 class="panel-title">{{ $trial['plan'] }}</h3>
                    </div>
                    <div class="panel-body">
                        <div class="well well-sm">Trial end: {{ $trial['trial_end'] }}</div>
                        <div class="well well-sm">Expired? : @if ($trial['expired']) Yes @else No @endif </div>
                    </div>
                </div>
                @endforeach
            @endif           

        </div>
    </div>
    
    <div class="panel panel-primary">
        <div class="panel-heading">
            <h3 class="panel-title">Available Subscriptions</h3>
        </div>

        <div class="panel-body">
            <div class="panel panel-info">
                <div class="panel-heading">
                    <h3 class="panel-title">Available Subscriptions</h3>
                </div>
                <div class="panel-body">
                    
                    <div class="well well-sm">
                        {!! Form::open(['url'=>'#', 
                        'id'=>'subscribeForm']) !!}
                        {!! Form::close() !!}
                        <button id="trial" type="button" class="btn btn-info">Start Your Free Trial</button>
                        <button id="checkout" type="button" class="btn btn-primary">Purchase One Year Subscription</button>
                    </div>

                    </div>
                </div>
            </div>
        </div>

    @endsection
