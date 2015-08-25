@extends('head')

@section('morecontent')

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
                <h3 class="panel-title">{{ $subscription['name'] }}</h3>
            </div>
            <div class="panel-body">
                
                <div class="well well-sm">Is trial?: @if ($subscription['trial'])
                    Yes
                    @else
                    No
                    @endif
                </div>


                <div class="well well-sm">Is active?: @if ($subscription['active'])
                    Yes
                    @else
                    No
                    @endif
                </div>

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
                
            </div>
        </div>

    </div>
</div>
@endif

@endsection
