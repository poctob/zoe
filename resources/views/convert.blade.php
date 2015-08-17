@extends('app')

@section('content')
{!! Html::style('css/dropzone.css') !!}
{!! Html::script('js/app.js') !!}
{!! Html::style('css/profile.css') !!}
<div class="profile-page">
    <span><h1>SC Medicare Converter</h1></span>
    <span><h2>Upload your file below:</h2></span>

    <hr/>
    <div class="panel-body">
        {!! Form::open(['url'=>'convert_file', 
        'id'=>'uploadForm', 
        'class'=>'dropzone', 
        'name'=> 'dropzoneForm', 'files'=>true]) !!}
        {!! Form::close() !!}
    </div>
    <hr/>  
</div>
@endsection
