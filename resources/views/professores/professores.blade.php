@extends("template.app")
@section("title")
Professores
@endsection

@section("content")
@if(Auth::user()->role == 1)
<div class="row">
    <a href="{{url("professores/novo")}}" class="btn btn-primary">Adicionar professor</a>
</div>
@endif
<div class="row mt-3">
    <div class="col-lg-6">
        <form method="GET">
            <div class="input-group">
                <input class="form-control" type="search" value="{{Request::get("search")}}" name="search" placeholder="Pesquisar: NOME ou MATRÍCULA"/>
                <button class="btn btn-primary">
                    <i class="fa fa-search" aria-hidden="true"></i>
                </button>
            </div>
        </form>
    </div>
    <div class="col-lg-12">
        <table class="table table-bordered mt-2">
            <thead>
                <tr>
                    <th>SITUAÇÃO</th>
                    <th>MATRÍCULA</th>
                    <th>NOME</th>
                    <th>AÇÕES</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($professores as $prof)
                    <tr>
                        <td>{{$prof->ativo ? "ATIVO" : "INATIVO"}}</td>
                        <td>{{$prof->matricula}}</td>
                        <td>{{$prof->nome}}</td>
                        <td>
                            <div class="btn-group">
                                @if(Auth::user()->role == 1)
                                <a href="{{url("professores/editar/".$prof->id)}}" class="btn btn-warning btn-sm">
                                    <i class="fa fa-edit" aria-hidden="true"></i>
                                </a>
                                @endif
                                <a href="{{url("/professores/{$prof->id}/faltas")}}" class="btn btn-success btn-sm">
                                    <i class="fa fa-eye" aria-hidden="true"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection