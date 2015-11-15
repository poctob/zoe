@extends('head')

@section('morecontent')


<ul class="nav nav-pills">
    <li role="presentation"><a href="applications">Applications</a></li>
    <li role="presentation" class="active"><a href="#">Options</a></li>
    <li role="presentation"><a href="subscriptions">Subscriptions</a></li>    
    <li role="presentation"><a href="profile">Profile</a></li>
</ul>

<div class="panel panel-primary">
    <div class="panel-heading">
        <h3 class="panel-title">Application Options</h3>
    </div>
    <div class="panel-body">
      
        {!! Form::open(['url'=>'options',
        'id' => 'optionsForm',
        'name' => 'optionsForm',
        'class' => 'form-horizontal']) !!}

        @if(isset($columns) && count($columns) > 0)
        <div>Columns to include</div>
        @foreach ($columns as $col)

        <div class="col-md-4">
            <div class="checkbox">
                <label>
                    {!! Form::checkbox('option_columns[]', $col['name'], $col['checked']) !!}
                    {{ $col['name'] }}
                </label>
            </div>
        </div>

        @endforeach
        <div class="col-md-8">
            ---
        </div>
        <div class="col-md-8">

            {!! Form::submit('Update Options', ['type'=>'button', 'class'=>'btn btn-primary']) !!}

        </div>
        {!! Form::close() !!}
        @endif

    </div>
</div>
@endsection
