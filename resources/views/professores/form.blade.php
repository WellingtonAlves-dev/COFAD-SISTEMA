@extends("template.app")
@section("title")
@if(Request::is("*/editar/*"))
    Editar professor
@else
    Adicionar professor
@endif
@endsection
@section("content")
<div class="container">
    <div class="row shadow-sm p-3">
        <div class="col-lg-12">
            <form method="POST">
                @csrf
                <div class="form-group">
                    <label>ativo?</label>
                    <input type="checkbox" name="ativo"
                    @if(Request::is("*/editar/*"))
                        @if($prof->ativo)
                            checked
                        @endif
                    @else
                        checked
                    @endif
                    >
                </div>
                <div class="form-group">
                    <label>MATRÍCULA<span class="text-danger">*</span></label>
                    <input type="text" value="{{$prof->matricula ?? ""}}" class="form-control" placeholder="Ex.: 06562" name="matricula">
                </div>
                <div class="form-group">
                    <label>NOME<span class="text-danger">*</span></label>
                    <input type="text" value="{{$prof->nome ?? ""}}" class="form-control" placeholder="Ex.: Sidnei Paixão" name="nome">
                </div>
                <button class="btn btn-primary">Salvar</button>
                <a href="{{url("/professores")}}" class="btn btn-warning">Voltar</a>
            </form>
            @if($errors->any())
                <div class="alert alert-danger mt-3">
                    <ul>
                        @foreach ( $errors->all() as $erro)
                            <li>{{$erro}}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            @if(Session::has("success"))
                <div class="alert alert-success mt-3">
                    {{Session::get("success")}}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection