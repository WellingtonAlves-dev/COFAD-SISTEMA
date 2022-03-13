@extends("template.app")
@section("content")
<div class="container" style="margin-top: 10px; margin-bottom: 100px;">
    <div class="text-center">
        <img src="{{asset("COFAD.png")}}" style="width: 350px">
    </div>
    <div class="row justify-content-center align-items-center" style="flex-direction: column">
        <div class="col-lg-6 shadow-sm text-center p-3">
            <h1>Seja bem-vindo ao {{env("APP_NAME")}}!</h1>
            <br/>
            <p>
                É com muita alegria e de braços abertos que o <strong>{{env("APP_NAME")}} - Controle de Faltas de Docentes </strong> recebe você!
            </p>
        </div>
        <br/>
        <div class="col-lg-6 shadow-sm text-center p-3">
            <h5>
                Agora é hora de você realizar o seu primeiro acesso. Para isso, insira as informações abaixo.
            </h5>
            <br/>
            <form method="POST" style="text-align: start">
                @csrf
                <div class="form-group">
                    <label>Nome</label>
                    <input type="text" class="form-control" placeholder="Ex.: Wellington Alves" name="name">
                </div>
                <div class="form-group">
                    <label>Email</label>
                    <input type="text" class="form-control" placeholder="Ex.: e228ti@cps.sp.gov.br" name="email">
                </div>
                <div class="form-group">
                    <label>Senha</label>
                    <input type="text" class="form-control" placeholder="Ex.: 110ReaisUmBotijao" name="password">
                    <small>Crie uma senha bem segura. Ninguém mais poderá acessá-la!</small>
                </div>
                <div style="display: none">
                    <input type="hidden" name="role" value="1">
                    <input type="hidden" name="ativo" value="true">
                </div>
                <button class="btn btn-primary">Cadastrar</button>
            </form>
            @if($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $erro)
                            <li>{{$erro}}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection