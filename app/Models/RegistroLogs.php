<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class RegistroLogs extends Model
{
    use HasFactory;
    public $fillable = ["id_res", "action"];

    public function criarLogs($action) {
        try {
            $id_res = Auth::user()->id;
            self::create(["id_res" => $id_res, "action" => $action]);
        } catch(Exception $e) {
            print_r($e->getMessage());
        }
    }

}
