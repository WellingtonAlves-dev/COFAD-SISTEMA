<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Faltas extends Model
{
    use HasFactory;
    protected $fillable = ["id", "id_professor", "id_user", "data_falta", "horario", "periodo"];
}
