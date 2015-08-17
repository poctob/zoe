@extends('app')

@section('content')
{!! Html::style('css/profile.css') !!}
{!! Html::script('js/validator.js') !!}
<div class="profile-page">
    @if (isset($success))
    <div class="alert alert-success" role="alert">{{ $success }}</div>
    @elseif (isset($error))
    <div class="alert alert-danger" role="alert">{{ $error }}</div>
    @endif
    <div class="panel panel-primary">
        <div class="panel-heading">
            <h3 class="panel-title">User Profile</h3>
        </div>
        <div class="panel-body">
            {!! Form::open(['url'=>'profile', 
            'id'=>'profileForm',
            'data-toggle'=>'validator',
            'class' => 'form-horizontal']) !!}

            <div class="form-group">
                {!! Form::label('name', 'Name',['class' => 'col-sm-2 control-label']) !!}
                <div class="col-sm-10">
                    {!! Form::text('name', $user['name'], 
                    ['class' => 'form-control',
                    'data-minlength'=> '1']) !!}
                    <div class="help-block with-errors">Minimum of 1 character</div>
                </div>
            </div>

            <div class="form-group">
                {!! Form::label('email', 'Email', ['class' => 'col-sm-2 control-label']) !!}
                <div class="col-sm-10">
                    {!! Form::text('email', $user['email'], 
                    ['class' => 'form-control', 
                    'type'=>'email',
                    'data-error'=> 'Email address is invalid']) !!}
                    <div class="help-block with-errors"></div>
                </div>
                
            </div>

            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10">
                    {!! Form::submit('Submit', ['type'=>'button', 'class'=>'btn btn-primary']) !!}
                </div>
            </div>


            {!! Form::close() !!}


        </div>
    </div>
</div>

@endsection
