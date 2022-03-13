@extends("template.app")
@section("title")
Usuários
@endsection
@section("content")
<div class="row">
    <a href="{{url("usuarios/novo")}}" class="btn btn-primary">Adicionar usuário</a>
</div>
<div class="row mt-3">
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>SITUAÇÃO</th>
                <th>NOME</th>
                <th>DELEGAÇÃO</th>
                <th>AÇÕES</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($usuarios as $user)
                <tr>
                    <td>{{$user->ativo ? "ATIVO" : "INATIVO"}}</td>
                    <td>{{$user->name}}</td>
                    <td>
                        @if($user->role == 1)
                            ADMINISTRADOR
                        @else
                            COORDENADOR
                        @endif
                    </td>
                    <td>
                        <a href="{{url("/usuarios/{$user->id}/editar")}}" class="btn btn-warning btn-sm">
                            <i class="fa fa-edit" aria-hidden="true"></i>
                        </a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection