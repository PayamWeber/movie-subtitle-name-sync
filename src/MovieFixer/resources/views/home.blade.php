<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Hello World!</title>
    <link rel="stylesheet" href="{{ url('md/css/bootstrap.css') }}">
    <link rel="stylesheet" href="{{ url('md/css/mdb.min.css') }}">
    <link rel="stylesheet" href="{{ url('fonts/jost/style.css') }}">
    <link rel="stylesheet" href="{{ url('renderer.css') }}">
</head>
<body>
@include('common.topbar')
<div class="content-wrap">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12 text-center align-content-center align-items-center">
                <h3>Easily sync your movie file names and subtitles
                </h3>
            </div>
            <div class="col-12 text-center align-content-center align-items-center mt-5">
                <button class="btn blue-gradient btn-choose-folder">Choose Folder</button>
            </div>
        </div>
    </div>
</div>
</body>
<script src="{{ url('renderer.js') }}"></script>
<script src="{{ url('md/js/bootstrap.min.js') }}"></script>
<script src="{{ url('md/js/mdb.min.js') }}"></script>
</html>
