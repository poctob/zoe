@extends('head')

@section('morecontent')

{!! Html::style('css/dropzone.css') !!}
{!! Html::script('js/app.js') !!}

<ul class="nav nav-pills">
    <li role="presentation" class="active"><a href="#">Applications</a></li>
    <li role="presentation"><a href="subscriptions">Subscriptions</a></li>    
    <li role="presentation"><a href="profile">Profile</a></li>
</ul>


<div class="panel panel-primary">
    <div class="panel-heading">
        <h3 class="panel-title">SC Medicaid Converter</h3>
        <span>Upload your file below:</span>
    </div>
    <div class="panel-body">
        {!! Form::open(['url'=>'convert_file', 
        'id'=>'uploadForm', 
        'class'=>'dropzone', 
        'name'=> 'dropzoneForm', 'files'=>true]) !!}
        {!! Form::close() !!}
    </div>
</div>
@endsection
