@extends('head')
@section('morecontent')
{!! Html::script('https://checkout.stripe.com/checkout.js') !!}
{!! Html::script('js/checkout.js') !!}

<ul class="nav nav-pills">
    <li role="presentation" class="active"><a href="#">Applications</a></li>
    <li role="presentation"><a href="subscriptions">Subscriptions</a></li>    
    <li role="presentation"><a href="profile">Profile</a></li>
</ul>

<div class="panel panel-primary">
    <div class="panel-heading">
        <h3 class="panel-title">Subscribe to XpressTek SC Medicare Converter</h3>
    </div>

    <div class="panel-body">
        {!! Form::open(['url'=>'trial', 
        'id'=>'subscribeForm']) !!}
        @if( isset($allow_trial) && $allow_trial)
        <div class="form-group">
        {!! Form::submit('Start Your Free Trial', ['type'=>'button', 'class'=>'btn btn-info']) !!}
        </div>
        @endif
        {!! Form::close() !!}


        <button id="checkout" type="button" class="btn btn-primary">Sign Up for Monthly Subscription</button>
    </div>
</div>


@endsection
