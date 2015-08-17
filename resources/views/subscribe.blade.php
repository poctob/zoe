@extends('app')

@section('content')
{!! Html::script('https://checkout.stripe.com/checkout.js') !!}
{!! Html::script('js/checkout.js') !!}

<span><h1>Subscribe to XpressTek SC Medicare Converter</h1></span>
<div class="panel-body">
    {!! Form::open(['url'=>'#', 
    'id'=>'subscribeForm']) !!}
    {!! Form::close() !!}
    
    <button id="checkout" type="button" class="btn btn-primary">Make Payment</button>
</div>

@endsection
