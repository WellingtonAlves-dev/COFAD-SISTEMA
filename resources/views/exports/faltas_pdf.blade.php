<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Faltas</title>
    <style>
    .page-break {
        page-break-after: always;
    }
    * {
        font-family: Arial, Helvetica, sans-serif;
    }
    table {
        border: 1px solid;
        border-collapse: collapse;
    }
    th, td {
        border: 1px solid;
    }
    </style>
</head>
<body>
    <div style="text-align: center">
        <img style="height: 150px" src="{{public_path()."/COFAD.png"}}">
    </div>
    <div style="margin-top: 5px; text-align: center;">
        <h3>RELATÓRIO DE FALTAS ENTRE {{date("d/m/Y", strtotime($periodo_inicial))}} A {{date("d/m/Y", strtotime($periodo_final))}}</h3>
    </div>
    <table style="width: 100%; font-size: 11px; text-align: center;">
        <tr>
            <th>PROFESSOR</th>
            <th>MATRÍCULA</th>
            <th>COORDENADOR</th>
            <th>DATA DA FALTA</th>
            <th>AULA</th>
            <th>PERÍODO</th>
        </tr>
        @foreach($faltas as $f)
        <tr>
            <td>{{$f->nome}}</td>
            <td>{{$f->matricula}}</td>
            <td>{{$f->name}}</td>
            <td>{{date("d/m/Y", strtotime($f->data_falta))}}</td>
            <td>{{$f->horario}}ª</td>
            <td>
                @if($f->periodo == "M")
                    MANHÃ
                @elseif($f->periodo == "T")
                    TARDE
                @else
                    NOITE
                @endif
            </td>
        </tr>
        @endforeach
    </table>
</body>
</html>