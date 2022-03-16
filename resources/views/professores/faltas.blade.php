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
                            <button class="btn btn-danger" id="anular_falta_btn" data-id="{{$falta->id}}">
                                Anular Falta
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
</script>
@endsection