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
                <input type="date" class="form-control" value="{{Request::get("data_falta") ?? date("Y-m-d")}}" name="data_falta">
            </div>
            <div class="form-group">
                <label>Aula da falta</label>
                <select class="form-control" name="horario">
                    @for($i = 0; $i < 10; $i++) 
                        <option value="{{$i + 1}}">{{$i + 1}}º aula</option>
                    @endfor
                </select>
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