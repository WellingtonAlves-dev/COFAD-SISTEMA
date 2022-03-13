<?php

namespace App\Http\Controllers;

use App\Models\User;
use Exception;
use Illuminate\Contracts\Session\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function loginView() {
        if(empty(User::first())) {
            return redirect("welcome");
        }
        return view("auth.login");
    }
    public function login(Request $request) {
        $credentials = $request->validate([
            "email" => "required|email",
            "password" => "required"
        ]);
        try {
            $credentials["password"] = md5($credentials["password"]);
            $user = User::where($credentials)->first();
            if(empty($user)) {
                return redirect()->back()->withErrors(["login invalido" => "O usuário ou senha está incorreto"]);
            }
            Auth::login($user);
            return redirect("/");
        } catch(Exception $e) {
            print_r($e->getMessage()) . "<br/>";
            return "Está página está em manutenção";
        }
    }
    public function logout(Request $request) {
        Auth::logout();
 
        $request->session()->invalidate();
     
        $request->session()->regenerateToken();
     
        return redirect('/');
    }
}