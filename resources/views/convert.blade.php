<html>
    <head>
        <title>XpressTek Converter</title>

        <link href='css/app.css' rel='stylesheet' type='text/css'>

        {!! Html::script('js/vendor.js') !!}
        {!! Html::style('css/dropzone.css') !!}


    </head>
    <body>
        <div class="container">
            <div class="content">
                <div><h1>XpressTek Converter</h1></div>
                <div><h2>Upload your file below:</h2></div>

                <hr/>
                <div>
                {!! Form::open(['url'=>'convert_file', 'class'=>'dropzone', 'name'=> 'dropzoneForm']) !!}
                {!! Form::close() !!}
                </div>
                <hr/>               

                <h3>Progress</h3>
                <div class="progress">

                    <div class="progress-bar" 
                         role="progressbar" 
                         aria-valuenow="0" 
                         aria-valuemin="0" 
                         aria-valuemax="0" style="width: 0%;">
                        Progress: 0%
                    </div>
                </div>
            </div>
    </body>
</html>
