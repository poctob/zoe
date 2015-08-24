@extends('head')

@section('morecontent')
{!! Html::script('https://checkout.stripe.com/checkout.js') !!}
{!! Html::script('js/checkout.js') !!}
<ul class="nav nav-pills">
    <li role="presentation"><a href="applications">Applications</a></li>
    <li role="presentation" class="active"><a href="#">Subscriptions</a></li>    
    <li role="presentation"><a href="profile">Profile</a></li>
</ul>


@if (isset($subscription))
<div class="panel panel-primary">
    <div class="panel-heading">
        <h3 class="panel-title">Subscriptions</h3>
    </div>

    <div class="panel-body">
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

    </div>
</div>
@endif


@if (isset($trials) && count($trials) > 0)
<div class="panel panel-primary">
    <div class="panel-heading">
        <h3 class="panel-title">Trials</h3>
    </div>
    @foreach ($trials as $trial)
    <div class="panel-body">


        <div class="panel panel-info">
            <div class="panel-heading">
                <h3 class="panel-title">{{ $trial['plan'] }}</h3>
            </div>
            <div class="panel-body">
                <div class="well well-sm">Trial end: {{ $trial['trial_end'] }}</div>
                <div class="well well-sm">Expired? : @if ($trial['expired']) Yes @else No @endif </div>
                <div class="well well-sm">
                    {!! Form::open(['url'=>'#', 
                    'id'=>'subscribeForm']) !!}
                    {!! Form::close() !!}
                    <button id="checkout" type="button" class="btn btn-primary">Purchase One Year Subscription</button>
                </div>
            </div>
        </div>          

    </div>
    @endforeach
</div>
@endif           

@if (isset($untried) && count($untried) > 0)
<div class="panel panel-primary">
    <div class="panel-heading">
        <h3 class="panel-title">Available Subscriptions</h3>
    </div>
    @foreach ($untried as $ut)
    <div class="panel-body">
        <div class="panel panel-info">
            <div class="panel-heading">
                <h3 class="panel-title">{{ $ut['app']->name }}</h3>
            </div>
            <div class="panel-body">

                @foreach ($ut['trial_type'] as $tt)
                <div class="well well-sm">
                    {!! Form::open(['url'=>'trial', 
                    'id'=>'trialForm']) !!}
                    {!! Form::hidden('application', $ut['app']->name) !!}
                    {!! Form::hidden('type', $tt->name) !!}                    
                    {!! Form::submit('Start Your Free Trial', ['type'=>'button', 'class'=>'btn btn-info'])!!}
                    {{ $tt->name  }}
                    {!! Form::close() !!}                  
                </div>
                <div class="well well-sm">
                    {!! Form::open(['url'=>'#', 
                    'id'=>'subscribeForm']) !!}
                    {!! Form::close() !!}
                    <button id="checkout" type="button" class="btn btn-primary">Purchase One Year Subscription</button>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endforeach
</div>
@endif

@endsection
