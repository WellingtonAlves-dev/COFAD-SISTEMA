@extends("template.app")
@section("title")
    Informações do usuário 
@endsection
@section("content")
<div class="row shadow-sm p-3">
    <div class="col-lg-12">
        <form method="POST" class="row">
            <div class="col-lg-6">
                <div class="form-group">
                    <label>Nome</label>
                    <input type="text" class="form-control" value="{{$user->name}}" name="name"
                    @if(Auth::user()->role > 0)
                        disabled
                    @endif
                    />
                </div>
            </div>
            <div class="col-lg-6">
                <div class="form-group">
                    <label>E-mail</label>
                    <input type="email" class="form-control" value="{{$user->email}}" name="email"
                    @if(Auth::user()->role > 0)
                        disabled
                    @endif
                    />
                </div>
            </div>
            <div class="col-lg-12">
                <div>
                    <button class="btn btn-primary"
                    @if(Auth::user()->role > 0)
                        disabled
                    @endif
                    >Salvar</button>
                </div>
            </div>
        </form>
    </div>
</div>
<div class="row shadow-sm p-3">
    <div class="col-lg-12">
        <form method="POST" class="row" action="{{url("usuarios/senha/atualizar")}}"
        >
            @csrf
            <div class="col-lg-12">
                    <div class="form-group">
                        <label>Senha atual</label>
                        <input type="text" class="form-control" name="password_atual"/>
                    </div>
                    <div class="form-group">
                        <label>Nova senha</label>
                        <input type="text" class="form-control" name="password"/>
                    </div>
            </div>
            <div class="col-lg-12">
                <div>
                    <button class="btn btn-primary">Salvar</button>
                </div>
            </div>
        </form>
    </div>
</div>
<div class="row">
    <div class="col-12 mt-3">
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