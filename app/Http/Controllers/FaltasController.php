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
            $faltas = Faltas::select("users.id", "faltas.id as faltasID","nome", "matricula", "name", "data_falta", "horario", "periodo")
                ->leftJoin("users", "users.id", "=", "faltas.id_user")
                ->leftJoin("professores", "professores.id", "=", "faltas.id_professor")
                ->whereBetween("data_falta",[$request->input("periodo_inicial"), $request->input("periodo_final")])
				->orWhere("data_falta", $request->input("periodo_inicial"))
				->orWhere("data_falta", $request->input("periodo_final"))
				->orderBy("data_falta", "desc")
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
            $faltas = Faltas::select("nome", "matricula", "name", "data_falta", "horario", "periodo")
                    ->leftJoin("users", "users.id", "=", "faltas.id_user")
                    ->leftJoin("professores", "professores.id", "=", "faltas.id_professor")
                    ->whereBetween("data_falta",[$periodo_inicial, $periodo_final])
					->orWhere("data_falta", $periodo_inicial)
					->orWhere("data_falta", $periodo_final)
                    ->orderBy("data_falta", "desc")
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
        ]);
        //validar se pelo menos um horario foi preenchido
        $horarios_manha = is_array($request->horarios_manha) ? $request->horarios_manha : [];
        $horarios_tarde = is_array($request->horarios_tarde) ? $request->horarios_tarde : [];
        $horarios_noite = is_array($request->horarios_noite) ? $request->horarios_noite : [];
        $somaTotalHorariosPreenchido = count($horarios_manha) + count($horarios_tarde) + count($horarios_noite);
        if($somaTotalHorariosPreenchido == 0) {
            return redirect()->back()->withErrors(["falta_horario" => "Registre pelo menos um horário."]);
        }
        try {
            //registrar manhã
            $data["id_user"] = Auth::user()->id;

            foreach($horarios_manha as $horario) {
                $data["horario"] = $horario;
                $data["periodo"] = "M";
                $falta_existente = Faltas::where($data)->first();
                if(!empty($falta_existente)) {
                    return redirect()->back()->withErrors(["falta_existen" => "Já existe uma falta com essas mesmas especificações"]);
                }
                Faltas::create($data);  
            }

            foreach($horarios_tarde as $horario) {
                $data["horario"] = $horario;
                $data["periodo"] = "T";
                $falta_existente = Faltas::where($data)->first();
                if(!empty($falta_existente)) {
                    return redirect()->back()->withErrors(["falta_existen" => "Já existe uma falta com essas mesmas especificações"]);
                }
                Faltas::create($data);  
            }

            foreach($horarios_noite as $horario) {
                $data["horario"] = $horario;
                $data["periodo"] = "N";
                $falta_existente = Faltas::where($data)->first();
                if(!empty($falta_existente)) {
                    return redirect()->back()->withErrors(["falta_existen" => "Já existe uma falta com essas mesmas especificações"]);
                }
                Faltas::create($data);  
            }

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

    public function getFaltasData(Request $request) {
        $data = $request->input("data");
        $id_professor = $request->input("id_professor");
        if($data == null) {
            return response("A data não pode esta vázia", 500);
        }
        if($id_professor == null) {
            return response("Preencha o campo professor", 500);
        }
        $faltas = Faltas::where("id_professor", $id_professor)
            ->whereDate("data_falta", $data)->get()->toArray();
        
        $faltasOrganizada = [
            "M" => [],
            "T" => [],
            "N" => []
        ];

        foreach($faltas as $falta) {
            array_push($faltasOrganizada[$falta["periodo"]], $falta["horario"]);
        }
        return response()->json($faltasOrganizada);
    }

}
