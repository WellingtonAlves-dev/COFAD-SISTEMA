@extends("template.app")
@section("title")
Quadro de faltas
@endsection
@section("content")
<div class="row">
    <a href="{{url("faltas/registrar")}}" class="btn btn-primary">Registrar falta</a>
</div>
<div class="row mt-3">
    <form method="GET" class="row w-100 shadow-sm p-3 m-1">
            <div class="col-sm-3">
                <label>Período inicial</label>
                <input type="date" value="{{Request::get("periodo_inicial")}}" class="form-control" name="periodo_inicial">
            </div>    
            <div class="col-sm-3">
                <label>Período final</label>
                <input type="date" value="{{Request::get("periodo_final")}}" class="form-control" name="periodo_final">
            </div>    
            <div class="col-sm-3 d-flex align-items-end">
                <button class="btn btn-primary">Buscar
                    <i class="fa fa-search" aria-hidden="true"></i>
                </button>
            </div>
    </form>
    <div class="row shadow w-100 p-3 m-1">
        @if($faltas)
        <a 
        href="{{url("/faltas/excel/".Request::get("periodo_inicial")."/".Request::get("periodo_final"))}}"
        class="btn btn-success btn-sm">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-file-earmark-spreadsheet" viewBox="0 0 16 16">
            <path d="M14 14V4.5L9.5 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2zM9.5 3A1.5 1.5 0 0 0 11 4.5h2V9H3V2a1 1 0 0 1 1-1h5.5v2zM3 12v-2h2v2H3zm0 1h2v2H4a1 1 0 0 1-1-1v-1zm3 2v-2h3v2H6zm4 0v-2h3v1a1 1 0 0 1-1 1h-2zm3-3h-3v-2h3v2zm-7 0v-2h3v2H6z"/>
            </svg>
        </a>
        <a 
        href="{{url("/faltas/pdf/".Request::get("periodo_inicial")."/".Request::get("periodo_final"))}}"
        class="btn btn-danger btn-sm" target="_blank">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-filetype-pdf" viewBox="0 0 16 16">
                <path fill-rule="evenodd" d="M14 4.5V14a2 2 0 0 1-2 2h-1v-1h1a1 1 0 0 0 1-1V4.5h-2A1.5 1.5 0 0 1 9.5 3V1H4a1 1 0 0 0-1 1v9H2V2a2 2 0 0 1 2-2h5.5L14 4.5ZM1.6 11.85H0v3.999h.791v-1.342h.803c.287 0 .531-.057.732-.173.203-.117.358-.275.463-.474a1.42 1.42 0 0 0 .161-.677c0-.25-.053-.476-.158-.677a1.176 1.176 0 0 0-.46-.477c-.2-.12-.443-.179-.732-.179Zm.545 1.333a.795.795 0 0 1-.085.38.574.574 0 0 1-.238.241.794.794 0 0 1-.375.082H.788V12.48h.66c.218 0 .389.06.512.181.123.122.185.296.185.522Zm1.217-1.333v3.999h1.46c.401 0 .734-.08.998-.237a1.45 1.45 0 0 0 .595-.689c.13-.3.196-.662.196-1.084 0-.42-.065-.778-.196-1.075a1.426 1.426 0 0 0-.589-.68c-.264-.156-.599-.234-1.005-.234H3.362Zm.791.645h.563c.248 0 .45.05.609.152a.89.89 0 0 1 .354.454c.079.201.118.452.118.753a2.3 2.3 0 0 1-.068.592 1.14 1.14 0 0 1-.196.422.8.8 0 0 1-.334.252 1.298 1.298 0 0 1-.483.082h-.563v-2.707Zm3.743 1.763v1.591h-.79V11.85h2.548v.653H7.896v1.117h1.606v.638H7.896Z"/>
            </svg>
        </a>
        <table class="table table-bordered mt-3 text-center" style="font-size: 14px">
            <thead>
                <tr>
                    <th>PROFESSOR</th>
                    <th>MATRÍCULA</th>
                    <th>REQUERENTE</th>
                    <th>DATA DA FALTA</th>
                    <th>AULA</th>
                    <th>PERÍODO</th>
                    <th>AÇÕES</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($faltas as $falta)
                    <tr>
                        <td>{{ $falta->nome }}</td>
                        <td>{{ $falta->matricula }}</td>
                        <td>{{ $falta->name }}</td>
                        <td>{{ date("d/m/Y", strtotime($falta->data_falta)) }}</td>
                        <td>{{ $falta->horario }}ª</td>
                        <td>
                            @if($falta->periodo == "M")
                                MANHÃ
                            @elseif($falta->periodo == "T")
                                TARDE
                            @else
                                NOITE
                            @endif
                        </td>
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
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
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
          <p>Esta ação é irreversível. Tem certeza que deseja anular essa falta?</p>
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
	function anularFalta(id) {
		$("#id_falta_anular").val(id);
		$("#anular_falta_modal").modal("show");
	}
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