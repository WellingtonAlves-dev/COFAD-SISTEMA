<?php

namespace App\Http\Controllers;

use App\Models\RegistroLogs;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function __construct()
    {
        //
    }
    public function index() {
        return view("usuarios.index", [
            "usuarios" => User::all()
        ]);
    }

    public function novo() {
        return view("usuarios.form");
    }
    public function editarUser($id) {
        try {

            $user = User::findOrFail($id);

            return view("usuarios.form", [
                "user" => $user
            ]);
        } catch(\Exception $e) {
            return "Está página está em manutenção";
        }
    }
    public function store(Request $request, $id = null) {
        $rules = [
            "name" => ["required"],
            "email" => ["required", "email"],
            "password" => ["required", "min:6"],
            "role" => ["required"]
        ];
        $data = $request->validate($rules);
        try {
            $data["name"] = strtoupper($request->name);
            $data["password"] = md5($request->password);
            $data["ativo"] = isset($request->ativo) ? true : false;
            if($id == null) {
                $user = User::create($data);
            } else {
                $user = User::where("id", $id)->update($data);
            }

            return redirect()->back()->with("success", "O usuário {$data["name"]} foi salvo com sucesso");
        } catch(Exception $e) {
            return "Está página está em manutenção";
        }
    }
    public function editar($id) {
        try {
            $user = User::findOrFail($id);
            if(Auth::user()->role != 1 && Auth::user()->id != $user->id) {
                return redirect("/");
            }
            if(Auth::user()->role == 1 && $user->role == 1 && $user->id != Auth::user()->id) {
                return redirect("/");
            }
            return view("usuarios.edit", [
                "user" => $user
            ]);
        } catch(\Exception $e) {
            dd($e->getMessage());
            return "Está página está em manutenção. Volte mais tarde";
        }
    }
    public function atualizarSenha(Request $request) {
        $rules = [
            "password_atual" =>  ["required", "min:6"],
            "password" => ["required"]
        ];
        $credentials = $request->validate($rules);
        try {
            $user = User::find(Auth::user()->id);
            if($user->password != md5($credentials["password_atual"])) {
                return redirect()->back()->withErrors(["digite uma senha valida" => "A senha não corresponde a senha do usuário"]);
            }
            $user->password = md5($credentials["password"]);
            $user->save();
            return redirect()->back()->with("success", "Senha atualizada com sucesso");
        } catch(\Exception $e) {
            return "Está página está em manutenção";
        }
    }
}
