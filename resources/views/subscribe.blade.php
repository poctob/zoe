@extends('app')

@section('content')
{!! Html::script('https://checkout.stripe.com/checkout.js') !!}
{!! Html::script('js/checkout.js') !!}
{!! Html::style('css/profile.css') !!}
<div class="profile-page">
     <div class="panel panel-primary">
        <div class="panel-heading">
            <h3 class="panel-title">Subscribe to XpressTek SC Medicare Converter</h3>
        </div>
    
    <div class="panel-body">
        {!! Form::open(['url'=>'#', 
        'id'=>'subscribeForm']) !!}
        {!! Form::close() !!}
        <button id="trial" type="button" class="btn btn-info">Start Your Free Trial</button>
        <button id="checkout" type="button" class="btn btn-primary">Purchase One Year Subscription</button>
    </div>
     </div>
</div>

@endsection
