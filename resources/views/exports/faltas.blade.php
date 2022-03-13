<table>
    <thead>
        <tr>
            <th>PROFESSOR</th>
            <th>MATRICULA</th>
            <th>COORDENADOR</th>
            <th>DATA DA FALTA</th>
            <th>AULA º</th>
        </tr>
    </thead>
    <tbody>
        @foreach ( $faltas as $f )
            <tr>
                <td>{{$f->nome}}</td>
                <td>{{$f->matricula}}</td>
                <td>{{$f->name}}</td>
                <td>{{date("d/m/Y", strtotime($f->data_falta))}}</td>
                <td>{{$f->horario}}</td>
            </tr>
        @endforeach
    </tbody>
</table>