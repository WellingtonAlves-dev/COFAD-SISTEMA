<?php

namespace App\Exports;
use App\Models\Faltas;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class FaltasExport implements FromView
{
    /**
    * @return \Illuminate\Support\Collection
    */
    protected $periodo_inicial;
    protected $periodo_final;
    public function __construct($periodo_inicial, $periodo_final)
    {   
        $this->periodo_inicial = $periodo_inicial;
        $this->periodo_final = $periodo_final;
    }
    public function view() : View
    {
        $faltas = Faltas::select("*")
        ->leftJoin("users", "users.id", "=", "faltas.id_user")
        ->leftJoin("professores", "professores.id", "=", "faltas.id_professor")
        ->whereBetween("data_falta",[$this->periodo_inicial, $this->periodo_final])
		->orWhere("data_falta", $this->periodo_inicial)
		->orWhere("data_falta", $this->periodo_final)
        ->orderBy("data_falta", "desc");
        return view('exports.faltas', [
            "faltas" => $faltas->get()
        ]);
    }   
}
