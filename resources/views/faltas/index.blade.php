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
                            <button onclick="anularFalta('{{$falta->faltasID}}')" class="btn btn-danger btn-sm" id="anular_falta_btn" data-id="{{$falta->id}}">
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
@endsection
@section("script")
<script>
	function anularFalta(id) {
		$("#id_falta_anular").val(id);
		$("#anular_falta_modal").modal("show");
	}
</script>
@endsection