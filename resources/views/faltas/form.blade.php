@extends("template.app")
@section("title")
Registrar falta
@endsection
@section("content")
<div class="container">
    <div class="row shadow-sm p-3 w-100">
        <form method="POST" class="w-100">
            @csrf
            <div class="form-group">
                <label>Professor</label>
                <select class="select-search" name="id_professor"
                    @if(Auth::user()->role == 2)
                        disabled
                    @endif                    
                >
                    <option selected disabled></option>
                    @foreach($professores as $prof)
                        <option value="{{$prof->id}}"
                            @if(Request::get("id_professor") == $prof->id)
                                selected
                            @endif
                            >{{$prof->nome}} - {{$prof->matricula}}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label>Data da falta</label>
                <input type="date" class="form-control" id="data_falta" value="{{Request::get("data_falta") ?? date("Y-m-d")}}" name="data_falta">
            </div>
            <div class="row justify-content-center w-100 mb-2 mt-2">
                <div class="col-lg-12">
                    <table class="table">
                        <tr>
                            <th>Manhã:</th>
                            @for($i = 0; $i < 6; $i++)
                                <td>
                                    <input type="checkbox" value="{{$i + 1}}" name="horarios_manha[]">
                                    <label>{{$i + 1}}ª aula</label>    
                                </td>
                            @endfor
                        </tr>
                        <tr>
                            <th>Tarde (médio):</th>
                            @for($i = 0; $i < 6; $i++)
                                <td>
                                    <input type="checkbox" value="{{$i + 1}}" name="horarios_tarde[]">
                                    <label>{{$i + 1}}ª aula</label>    
                                </td>
                            @endfor
                        </tr>
                        <tr>
                            <th>Tarde (tecnico):</th>
                            @for($i = 0; $i < 6; $i++)
                                <td>
                                    <input type="checkbox" value="{{$i + 1}}.25" name="horarios_tarde[]">
                                    <label>{{$i + 1}}.25ª aula</label>    
                                </td>
                            @endfor
                        </tr>
                        <tr>
                            <th>Noite:</th>
                            @for($i = 0; $i < 5; $i++)
                                <td>
                                    <input type="checkbox" value="{{$i + 1}}.25" name="horarios_noite[]">
                                    <label>{{$i + 1}}.25ª aula</label>    
                                </td>
                            @endfor
                        </tr>
                    </table>
                </div>
            </div>
            <div class="row mt-4 mb-2">
                <div class="col-lg-12">
                    <label>Observação</label>
                    <textarea rows="5" style="resize: none" class="form-control" name="observacao"></textarea>    
                </div>
            </div>
            <button class="btn btn-primary">Registrar</button>
            <a href="
                @php
                if(Auth::user()->role == 1) {
                    echo url("/faltas");
                } elseif(Auth::user()->role == 2) {
                    $id_professor = Request::get("id_professor");
                    echo url("/professores/{$id_professor}/faltas");
                }
                @endphp 
                " class="btn btn-warning">Voltar</a>
            @if($errors->any())
                <div class="alert alert-danger mt-3">
                    <ul>
                        @foreach($errors->all() as $erro)
                            {{$erro}}
                        @endforeach    
                    </ul>
                </div>
            @endif
            @if(Session::has("success"))
                <div class="alert alert-success mt-3">
                    {{Session::get("success")}}
                </div>
            @endif
        </form>
    </div>
</div>
@endsection
@section("script")
<script>
    function desmarcarCheckBoxEAtivar() {
        checkboxs = $("input[type='checkbox']");
        checkboxs.prop("disabled", false);
        checkboxs.prop("checked", false);

    }
    function pesquisarHorariosExistentes(data_falta, id_professor) {
        $.ajax({
            method: "GET",
            url: "{{url("faltas/ajax/data")}}",
            data: {data: data_falta, id_professor: id_professor},
            success: function(horarios) {
                console.log(horarios)
                for(hora of horarios["M"] ) {
                    preencherHorariosExistentes(hora, "M");
                }
                for(hora of horarios["T"] ) {
                    console.log("tarde");
                    preencherHorariosExistentes(hora, "T");
                }
                for(hora of horarios["N"] ) {
                    preencherHorariosExistentes(hora, "N");
                }
            },
            error: function(err) {
                alert(err.responseText);
            }
        })
    }
    function preencherHorariosExistentes(horario, periodo) {
        let periodo_correto = ""
        if(periodo == "M") {
            periodo_correto = "horarios_manha[]";
        } else if (periodo == "T") {
            periodo_correto = "horarios_tarde[]";
        } else {
            periodo_correto = "horarios_noite[]";
        }
        $(`input[value='${horario}'][name='${periodo_correto}']`).prop("disabled", true);
    }
    
    $(document).ready(function() {
        let select_prof = $("select[name='id_professor']");
        if(select_prof.val() != "") {
            pesquisarHorariosExistentes($("#data_falta").val(), select_prof.val());
        }

        select_prof.on('change', function(evt) {
            desmarcarCheckBoxEAtivar();
            pesquisarHorariosExistentes($("#data_falta").val(), evt.target.value);
        });

        $("#data_falta").on("change", function(evt) {
            desmarcarCheckBoxEAtivar();
            pesquisarHorariosExistentes(evt.target.value,  $("select[name='id_professor']").val());
        });

    });

</script>
@endsection