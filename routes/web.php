<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\FaltasController;
use App\Http\Controllers\ProfessoresController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WelcomeController;
use App\Models\Faltas;
use GuzzleHttp\Middleware;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::get("/welcome", [WelcomeController::class, "welcome"]);
Route::post("/welcome", [WelcomeController::class, "primeiroCadastro"]);
Route::get("/login", [AuthController::class, "loginView"])->name("login");
Route::post("/login", [AuthController::class, "login"]);
Route::get("/logout", [AuthController::class, "logout"]);
Route::middleware(["auth"])->group(function() {
    Route::get('/', function () {
        return view('index');
    })->middleware(["role:1"]);
    #professores
    Route::get("/professores", [ProfessoresController::class, "index"]);
    Route::get("/professores/novo", [ProfessoresController::class, "novo"])->middleware(["role:1"]);
    Route::get("/professores/editar/{id}", [ProfessoresController::class, "editar"])->middleware(["role:1"]);
    Route::post("/professores/novo", [ProfessoresController::class, "save"])->middleware(["role:1"]);
    Route::post("/professores/editar/{id}", [ProfessoresController::class, "save"])->middleware(["role:1"]);
    Route::get("/professores/{id_professor}/faltas", [ProfessoresController::class, "faltas"])->middleware("role:2");
    #usuarios
    Route::get("/usuarios", [UserController::class, "index"])->middleware(["role:1"]);
    Route::get("/usuarios/novo", [UserController::class, "novo"])->middleware(["role:1"]);
    Route::post("/usuarios/novo", [UserController::class, "store"])->middleware(["role:1"]);
    Route::get("/usuarios/{id}/editar", [UserController::class, "editarUser"])->middleware(["role:1"]);
    Route::post("/usuarios/{id}/editar", [UserController::class, "store"])->middleware(["role:1"]);
    Route::get("/usuarios/{id}", [UserController::class, "editar"])->middleware(["role:2"]);
    Route::post("/usuarios/senha/atualizar", [UserController::class, "atualizarSenha"])->middleware(["role:2"]);
    #Faltas 
    Route::get("/faltas", [FaltasController::class, "index"])->middleware(["role:1"]);
    Route::get("/faltas/registrar", [FaltasController::class, "registarFaltaView"])->middleware(["role:2"]);
    Route::post("/faltas/registrar", [FaltasController::class, "registar"])->middleware(["role:2"]);
    Route::post("/faltas/anular", [FaltasController::class, "anular"])->middleware(["role:1"]);
    #faltas downloads
    Route::get("/faltas/excel/{periodo_inicial}/{periodo_final}", [FaltasController::class, "downloadExcel"])->middleware(["role:1"]);
    Route::get("/faltas/pdf/{periodo_inicial}/{periodo_final}", [FaltasController::class, "downloadPDF"]);
});