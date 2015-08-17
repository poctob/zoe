@extends('app')

@section('content')
{!! Html::style('css/profile.css') !!}
<div class="profile-page">
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
            <div class="well well-sm">Subscription Plan: {{ $subscription['plan'] }}</div>
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
</div>

@endsection
