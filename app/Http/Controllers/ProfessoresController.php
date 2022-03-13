<?php

namespace App\Http\Controllers;

use App\Models\Faltas;
use App\Models\Professores;
use Exception;
use Illuminate\Http\Request;

class ProfessoresController extends Controller
{
    public function index(Request $request) {
        $search = $request->input("search");
        $professores = Professores::select("matricula", "nome", "ativo", "id");
        if($search != null) {
            $professores->where("nome", "like", "%". $search."%")
                ->orWhere("matricula", $search);
        }
        return view("professores.professores", [
            "professores" => $professores->get()
        ]);
    }

    public function novo() {
        return view("professores.form");
    }
    public function editar($id) {
        try {
            return view("professores.form", ["prof" => Professores::findOrFail($id)]);
        } catch(Exception $e) {
            echo "Está página está em manutenção";
        }
    }
    public function faltas(Request $request, $id_professor) {
        $professor_info = Professores::findOrFail($id_professor);
        $faltas = Faltas::where("id_professor", $id_professor);
        if($request->input("data_falta")) {
            $faltas->whereDate("data_falta", $request->input("data_falta"));
        } else {
            $faltas->whereDate("data_falta", date("Y-m-d"));
        }
        return view("professores.faltas", [
            "faltas" => $faltas->get(),
            "professor" => $professor_info
        ]);
    }
    public function save(Request $request, $id = null) {
        $rules = [
            "nome" => ["required"],
            "matricula" => ["required", "numeric"]
        ];
        $request->validate($rules);
        try {
            if($id == null) {
                $professor = new Professores();
            } else {
                $professor = Professores::findOrFail($id);
            }
            $professor->nome = strtoupper($request->nome);
            $professor->matricula = $request->matricula;
            $professor->ativo = $request->ativo ? true : false;
            $professor->save();
            return redirect()->back()->with("success", "O professor {$professor->nome} foi salvo com sucesso");
        } catch(Exception $e) {
            return redirect()->back()->withErrors(["inesperado" => $e->getMessage()]);
        }
    }
}
