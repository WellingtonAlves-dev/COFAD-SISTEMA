@extends("template.app")
@section("title")
Quadro de faltas
@endsection
@section("content")
<div class="container">
    <div class="row shadow-sm p-3">
            <div class="col-lg-12">
                <h3>Informações do professor</h3>
            </div>
            <div class="col-lg-4">
                <label>SITUAÇÃO</label>
                <input class="form-control" type="text" disabled value="{{$professor->ativo ? "ATIVO" : "INATIVO"}}">
            </div>
            <div class="col-lg-4">
                <label>MATRÍCULA</label>
                <input class="form-control" type="text" disabled value="{{$professor->matricula}}">
            </div>
            <div class="col-lg-4">
                <label>NOME</label>
                <input class="form-control" type="text" disabled value="{{$professor->nome}}">
            </div>
    </div>
    <div class="row shadow-sm p-3">
        <div class="col-lg-3">
            <a href="{{url("faltas/registrar?id_professor=".request()->route('id_professor'))}}" class="btn btn-primary">Registrar falta</a>
        </div>
    </div>
    <div class="row shadow-sm p-3">
        <form method="GET" class="row w-100">
            <div class="col-sm-4">
                <label>Pesquisar faltas por data</label>
                <input type="date" class="form-control" name="data_falta" value="{{
                    Request::get("data_falta") ?? date("Y-m-d")
                }}">
            </div>
            <div class="col-sm-3 d-flex align-items-end">
                <button class="btn btn-primary">Pesquisar</button>
            </div>
        </form>
    </div>
    <div class="row shadow-sm p-3 mb-5">
        <table class="table">
            <thead>
                <tr>
                    <th>AULA</th>
                    <th>PERÍODO</th>
                    @if(Auth::user()->role == 1)
                    <th>AÇÕES</th>
                    @endif    
                </tr>
            </thead>
            <tbody>
                @foreach($faltas as $falta)
                    <tr>
                        <td>{{$falta->horario}}</td>
                        <td>
                            @if($falta->periodo == "M")
                                MANHÃ
                            @elseif($falta->periodo == "T")
                                TARDE
                            @else
                                NOITE
                            @endif
                        </td>
                        @if(Auth::user()->role == 1)
                        <td>
                            <button class="btn btn-success btn-sm" onclick="observacaoFalta('{{$falta->nome}}', '{{$falta->observacao}}')">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-eye" viewBox="0 0 16 16">
                                    <path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8zM1.173 8a13.133 13.133 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13.133 13.133 0 0 1 14.828 8c-.058.087-.122.183-.195.288-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5c-2.12 0-3.879-1.168-5.168-2.457A13.134 13.134 0 0 1 1.172 8z"/>
                                    <path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5zM4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0z"/>
                                  </svg>
                                  Observação
                            </button>
                            <button onclick="anularFalta('{{$falta->faltasID}}')" class="btn btn-danger btn-sm" id="anular_falta_btn" data-id="{{$falta->id}}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash" viewBox="0 0 16 16">
                                    <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z"/>
                                    <path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z"/>
                                </svg>
                                Anular falta
                            </button>   
                        </td>
                        @endif    
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
<!-- Modalzinho -->
<div class="modal" id="anular_falta_modal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Anular falta</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <p>Está ação é irreversivel. Tem certeza que deseja anular está falta???</p>
          <form method="POST" action="{{url("/faltas/anular")}}" id="form-anular">
              @csrf
              <input type="hidden" id="id_falta_anular" name="id_falta"/>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-primary" onclick="$('#form-anular').submit()">Anular</button>
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
        </div>
      </div>
    </div>
  </div> 
<!-- Observação -->
<div class="modal" id="obs_modal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Observação falta: <span id="observacao_falta_professor_nome"></span></h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <p id="observacao_falta"></p>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
          </div>
        </div>
      </div>
</div>
@endsection
@section("script")
<script>
    $("#anular_falta_btn").on("click", function (evt) {
        let id = evt.target.getAttribute("data-id");
        if(id) {
            $("#id_falta_anular").val(id);
            $("#anular_falta_modal").modal("show");
        }
    });
    function observacaoFalta(nome_professor, observacao) {
        $("#observacao_falta_professor_nome").text(nome_professor);
        if(observacao.trim().length == 0) {
            $("#observacao_falta").text("Não existe nenhuma observação para esta falta");
        } else {
            $("#observacao_falta").text(observacao);
        }
        $("#obs_modal").modal("show");
    }

</script>
@endsection