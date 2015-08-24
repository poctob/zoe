@extends('head')

@section('morecontent')

    
    <ul class="nav nav-pills">
        <li role="presentation" class="active"><a href="#">Applications</a></li>
        <li role="presentation"><a href="subscriptions">Subscriptions</a></li>    
        <li role="presentation"><a href="profile">Profile</a></li>
    </ul>


    <div class="panel panel-primary">
        <div class="panel-heading">
            <h3 class="panel-title">User Applications</h3>
        </div>
        <div class="panel-body">

            @foreach ($apps as $app)
            <div class="well well-sm">
                <span class="glyphicon glyphicon-cloud-download"> {{ $app->name }}</span>
                <a href="{{ $app->url }}" class="btn btn-primary" role="button">Launch</a>              
            </div>
            @endforeach
        </div>
    </div>

@endsection
