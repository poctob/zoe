<html>
    <head>
        <title>XpressTek Converter</title>

        <link href='css/app.css' rel='stylesheet' type='text/css'>

        {!! Html::script('js/vendor.js') !!}
        {!! Html::style('css/dropzone.css') !!}
        {!! Html::style('css/dz.css') !!}
        {!! Html::script('js/app.js') !!}


    </head>
    <body>
        <div class="container">
            <div class="content">
                <div><h1>XpressTek Converter</h1></div>
                <div><h2>Upload your file below:</h2></div>

                <hr/>
                <div>
                    {!! Form::open(['url'=>'convert_file', 'id'=>'uploadForm', 'class'=>'dropzone', 'name'=> 'dropzoneForm', 'files'=>true]) !!}
                    {!! Form::close() !!}
                </div>
                <hr/>  

                </body>
                </html>
