<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WelcomeController extends Controller
{
    public function welcome() {
        if(!empty(User::first())) {
            return redirect("login");
        }
        return view("welcome");
    }
    public function primeiroCadastro(Request $request) {
        if(!empty(User::first())) {
            return redirect("login");
        }
        $rules = [
            "name" => ["required"],
            "email" => ["required", "email"],
            "password" => ["required", "min:6"],
            "role" => ["required"]
        ];
        $request->validate($rules);
        try {
            $data = $request->all();
            $data["name"] = strtoupper($request->name);
            $data["password"] = md5($request->password);
            $data["ativo"] = isset($request->ativo) ? true : false;
            $user = User::create($data);
            if($user) {
                Auth::login($user);
                return redirect("/");
            } else {
                return redirect()->back()->withErrors(["cadastro invalido" => "não foi possivel realizar o cadastro"]);
            }
        } catch(\Exception $e) {
            dd($e->getMessage());
            return "Está página está em manutenção";
        }
    }
}
