<?php

namespace App\Http\Controllers;

use App\Exports\FaltasExport;
use App\Models\Faltas;
use App\Models\Professores;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class FaltasController extends Controller
{
    public function index(Request $request)
    {
        $faltas = null;
        if($request->input("periodo_inicial") != null && $request->input("periodo_final") != null) {
            $faltas = Faltas::select("users.id", "nome", "matricula", "name", "data_falta", "horario")
                ->leftJoin("users", "users.id", "=", "faltas.id_user")
                ->leftJoin("professores", "professores.id", "=", "faltas.id_professor")
                ->whereBetween("data_falta",[$request->input("periodo_inicial"), $request->input("periodo_final")])
				->orWhere("data_falta", $request->input("periodo_inicial"))
				->orWhere("data_falta", $request->input("periodo_final"))
				->orderBy("faltas.id", "desc")
                ->get();
        }
        return view("faltas.index", [
            "faltas" => $faltas
        ]);
    }

    public function downloadExcel($periodo_inicial, $periodo_final) {
        return Excel::download(new FaltasExport($periodo_inicial, $periodo_final), "faltas.xlsx");
    }

    public function downloadPDF($periodo_inicial, $periodo_final) {
        try {
            $faltas = Faltas::select("nome", "matricula", "name", "data_falta", "horario")
                    ->leftJoin("users", "users.id", "=", "faltas.id_user")
                    ->leftJoin("professores", "professores.id", "=", "faltas.id_professor")
                    ->whereBetween("data_falta",[$periodo_inicial, $periodo_final])
					->orWhere("data_falta", $request->input("periodo_inicial"))
					->orWhere("data_falta", $request->input("periodo_final"))
                    ->orderBy("faltas.id", "desc")
                    ->get();
            $pdf = PDF::loadView('exports.faltas_pdf', [
                "faltas" => $faltas,
                "periodo_inicial" => $periodo_inicial,
                "periodo_final" => $periodo_final
            ]);
            return $pdf->stream();
            //return $pdf->download('invoice.pdf');
        } catch(Exception $e) {
            return "Está página está em manutenção";
        }
    
    }

    public function registarFaltaView(Request $request) {
        $professores = Professores::where("ativo", true)->get();
        return view("faltas.form", [
            "professores" => $professores
        ]);
    }
    public function registar(Request $request) {
        $data = $request->validate([
            "id_professor" => "required",
            "data_falta" => "required|date",
            "horario" => "required"
        ]);
        try {
            $falta_existente = Faltas::where($data)->first();
            if(!empty($falta_existente)) {
                return redirect()->back()->withErrors(["falta_existen" => "Já existe uma falta com essas mesmas especificações"]);
            }
            $data["id_user"] = Auth::user()->id;
            Faltas::create($data);  
            return 
                redirect(url("faltas/registrar?id_professor=".$data["id_professor"]."&"."data_falta=".$data["data_falta"]))
                ->with("success", "Falta registrada com sucesso");          
        } catch(Exception $e) {
            return "Está página está em manutenção";
        }
    }
    
    public function anular(Request $request) {
        try {
            $id_falta = $request->id_falta;
            Faltas::where("id", $id_falta)->delete();
            return redirect()->back();
        } catch(Exception $e) {
            return "Está página está em manutenção";
        }
    }
}
