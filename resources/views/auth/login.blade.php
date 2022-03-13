@extends("template.app")
@section("content")
<div class="container" style="margin-top: 100px; margin-bottom: 100px;">
    <div class="text-center">
        <img src="{{asset("COFAD.png")}}" style="width: 350px">
    </div>
    <div class="row justify-content-center">
        <div class="col-lg-6 shadow-sm p-3">
            <form method="POST">
                @csrf
                <div class="form-group">
                    <label>E-mail</label>
                    <input type="text" class="form-control" name="email" placeholder="Insira seu e-mail">
                </div>
                <div class="form-group">
                    <label>Senha</label>
                    <input type="password" class="form-control" name="password" placeholder="Insira sua senha">
                </div>
                <button class="btn btn-primary">Entrar</button>
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
        </div>
    </div>
</div>
@endsection