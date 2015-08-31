@extends('head')

@section('morecontent')
{!! Html::script('js/app.js') !!}

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
                <div class="well well-sm">Expires on: 
                    @if ( isset($subscription['expires']))
                    {{ $subscription['expires'] }}
                    @else
                    N/A
                    @endif
                </div>

                <div class="well well-sm">Created on: 
                    @if ( isset($subscription['created']))
                    {{ $subscription['created']->toFormattedDateString()  }}
                    @else
                    N/A
                    @endif
                </div>

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

    </div>
</div>
@endif

@endsection
