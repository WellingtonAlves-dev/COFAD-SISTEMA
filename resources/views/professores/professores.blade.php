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
    <div class="col-lg-6 row d-flex justify-content-end align-items-center">
        Total ativo: {{$totalAtivo}}
        Total inativo: {{$totalInativo}}
    </div>
    <div class="col-lg-12" style="overflow: auto">
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
        @php
            $paginaAtual = $professores->currentPage() - 2;
            $rangerLimit = $professores->currentPage() + 2;
            $max = $professores->total() / $professores->perPage();
        @endphp
        <nav aria-label="Page navigation example">
            <ul class="pagination">
              <li class="page-item"><a class="page-link" href="{{$professores->previousPageUrl()}}">Anterior</a></li>
                @foreach($professores->getUrlRange($paginaAtual, $rangerLimit) as $key => $prof)
                    @if($key > 0 && $key <= ( $max + 1))
                        <li class="page-item"><a class="page-link" href="{{$prof}}">{{$key}}</a></li>
                    @endif
                @endforeach
              <li class="page-item"><a class="page-link" href="{{$professores->nextPageUrl()}}">Próxima</a></li>
            </ul>
          </nav>
    </div>
</div>
@endsection