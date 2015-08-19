@extends('app')
@section('content') 
    
{!! Html::style('css/profile.css') !!}


<div class="profile-page">

    @if ( \Session::has('growl'))
    <div class="alert alert-{{ \Session::get('growl')['type'] }} alert-dismissible" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">

            <span aria-hidden="true">&times;</span></button>

        <strong>Warning!</strong> {{ \Session::get('growl')['message'] }} </div>
    @endif
    

@yield('morecontent')

</div>

@endsection

