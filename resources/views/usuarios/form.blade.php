@extends("template.app")
@section("title")
@if(Request::is("*/editar"))
    Editar usuário
@else
    Adicionar usuário
@endif
@endsection
@section("content")
<div class="row shadow-sm p-3">
    <div class="col-lg-12">
        <form method="POST">
            @csrf
            <div class="form-group">
                <label>ativo?</label>
                <input type="checkbox" name="ativo"
                @if(Request::is("*/editar/*"))
                    @if($user->ativo)
                        checked
                    @endif
                @else
                    checked
                @endif
                >
            </div>
            <div class="form-group">
                <label>Nome</label>
                <input type="text" class="form-control" name="name" value="{{$user->name ?? ""}}" 
                @if(Request::is("*/editar") && $user->role == 1)
                    disabled
                @endif
                >
            </div>
            <div class="form-group">
                <label>E-mail</label>
                <input type="email" class="form-control" name="email" value="{{$user->email ?? ""}}"
                @if(Request::is("*/editar") && $user->role == 1)
                    disabled
                @endif
                >
            </div>
            <div class="form-group">
                <label>Senha</label>
                <input type="password" class="form-control" name="password"
                @if(Request::is("*/editar") && $user->role == 1)
                    disabled
                @endif
                >
            </div>
            <div class="form-group">
                <div class="form-check">
                    <input class="form-check-input" type="radio" value="1" name="role" id="adm"
                    @if(Request::is("*/editar") && $user->role == 1)
                        checked
                    @endif
                    >
                    <label class="form-check-label" for="adm">
                      ADMINISTRADOR
                    </label>
                  </div>
                  <div class="form-check">
                    <input class="form-check-input" type="radio" value="2" name="role" id="coordenador" 
                    @if(Request::is("*/editar"))
                        @if(Request::is("*/editar") && $user->role == 2)
                            checked
                        @endif
                    @else
                        checked
                    @endif
                    >
                    <label class="form-check-label" for="coordenador">
                      COORDENADOR
                    </label>
                  </div>
            </div>
            <button class="btn btn-primary"
                @if(Request::is("*/editar") && $user->role == 1)
                    disabled
                @endif
            >Salvar</button>
            <a href="{{url("/usuarios")}}" class="btn btn-warning">Voltar</a>
        </form>
        @if($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ( $errors->all() as $erro )
                        <li>{{$erro}}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        @if(Session::has("success"))
            <div class="alert alert-success">
                {{Session::get("success")}}
            </div>
        @endif
    </div>
</div>
@endsection