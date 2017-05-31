<!doctype html>
<html lang="{{ config('app.locale') }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="{{asset('bower_components/bootstrap/dist/css/bootstrap.min.css')}}">
    <title>MergePdf</title>
    <style>
        .barout {
            border: 1px #ccc solid;
            height: 30px;
            width: auto;
        }

        .barinner {
            position: relative;
            border: none;
            width: 0%;
            background-color: #2ca02c;
            height: 24px;
            padding-top: 3px;
            padding-bottom: 5px;
            display: none;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12">
            <p>
            <h1>MergePdf</h1><br>
            Junção de vários PDFs<br>
            </p>
            @if (count($errors) > 0)
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12">
            <div class="well">
                <p>
                    <em>
                        Antes crie um arquivo com extensão <strong>CSV</strong> com o caminho para baixar cada arquivo
                        separado por
                        ";"<br>
                        <div class="text-mute"> Ex.:
                            http://www.blabla.com/arquivo1.pdf;http://www.blabla.com/arquivo3.pdf;http://www.blabla.com/arquivo4.pdf;http://www.blabla.com/arquivo5.pdf;
                        </div>
                        <br>
                        Agora selecione o arquivo e clique em enviar, aguarde que em seguida será exibido o link para
                        download do arquivo pdf onde foram mesclados os descritos anteriormente no arquivo.
                    </em>
                </p>


            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12">
            <form action="/uploadcsv" method="post" enctype="multipart/form-data" name="formcsv" id="formcsv">
                {{csrf_field()}}
                <div class="form-group">
                    <input type="file" name="csv" id="csv">
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-sm btn-primary">Enviar</button>
                </div>
            </form>
            <div class="form-group">
                <div class="barout">
                    <div class="barinner">&nbsp;&nbsp;Carregando...</div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="{{asset('bower_components/jquery/dist/jquery.js')}}"></script>
<script src="{{asset('bower_components/bootstrap/dist/js/bootstrap.js')}}"></script>
<script>
    $(document).ready(function () {
        var w = 0;
        $("#formcsv").submit(function () {
            $(".barinner").show();
            setInterval(function () {
                w += 0.5;
                if (w >= 100) {
                    w = 0;
                } else {
                    $('.barinner').css('width', w + "%");
                }
            }, 1000);
        });
    });
</script>
</body>
</html>
