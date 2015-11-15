@extends('head')

@section('morecontent')
{!! Html::script('js/app.js') !!}
@if ($subscription['trial'])
{!! Html::script('https://checkout.stripe.com/checkout.js') !!}
{!! Html::script('js/checkout.js') !!}
@endif

<ul class="nav nav-pills">
    <li role="presentation"><a href="applications">Applications</a></li>
    <li role="presentation"><a href="options">Options</a></li>
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
                <h3 class="panel-title">{{ $subscription['name'] }} *
                    <span>
                        @if ($subscription['trial'])
                        Trial
                        @else
                        Standard
                        @endif
                    </span>
                    *
                    <span>
                        @if ($subscription['active'])
                        Active
                        @else
                        Not Active
                        @endif
                    </span>
                    *
                    <span>
                        @if ($subscription['cancelled'])
                        Canceled, will not renew
                        @endif
                    </span>

                </h3>
            </div>
            <div class="panel-body">
                @if ( isset($subscription['expires']))
                <div class="well well-sm">Expires on:                     
                    {{ $subscription['expires'] }}                    
                </div>
                @endif

                @if ( isset($subscription['created']))
                <div class="well well-sm">Created on:                     
                    {{ $subscription['created']->toFormattedDateString()  }}
                </div>
                @endif


                @if( !$subscription['trial'] && $subscription['active'] && !$subscription['cancelled'])

                {!! Form::open(['url'=>'cancelSubscription', 
                'id'=>'cancelForm']) !!}

                <div class="form-group">
                    {!! Form::button('Cancel Subscription', ['type'=>'button', 
                    'class'=>'btn btn-warning',
                    'id'=> 'submitBtn',
                    'data-toggle' => 'modal',
                    'data-target' => '#confirm-submit']) !!}
                </div>

                {!! Form::close() !!}

                <div class="modal fade" id="confirm-submit" tabindex="-1" 
                     role="dialog" aria-labelledby="confirm-submit">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                                <h4 class="modal-title" id="myModalLabel">Confirm Subscription Cancellation</h4>
                            </div>

                            <div class="modal-body">
                                Please note that by canceling the subscription, 
                                your application access will still be valid 
                                until the end of the current subscription period.
                                When your current period expires, you will 
                                no longer have access to the application.
                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                <button type="button" id="cancelConfirmButton" class="btn btn-primary">Confirm</button>
                            </div>
                        </div>
                    </div>
                </div>

                @endif
            </div>
        </div>
        @if ($subscription['trial'])
        {!! Form::open(['url'=>'#', 
        'id'=>'subscribeForm']) !!}               
        {!! Form::close() !!}
        <button id="checkout" type="button" class="btn btn-primary">Sign Up for Monthly Subscription</button>
        @endif

    </div>
</div>
@endif

@endsection
