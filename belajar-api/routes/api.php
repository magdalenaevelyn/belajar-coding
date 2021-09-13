<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DummyAPI;
use App\Http\Controllers\BukuController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthControler;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['middleware' => 'auth:sanctum'], function(){
    Route::get("getAllBuku", [BukuController::class, 'getAllBuku']);
    Route::get("user", [AuthControler::class, 'user']);
});

Route::get("data", [DummyAPI::class, 'getData']);

// Buku
// Route::get("getAllBuku", [BukuController::class, 'getAllBuku']);
Route::get("getBuku/{id}", [BukuController::class, 'getBukuById']);
Route::get("getBukuu/{id?}", [BukuController::class, 'getBuku']); // cara lain untuk efisiensi function
Route::post("addBuku", [BukuController::class, 'addBuku']);
Route::put("updateBuku/{id}", [BukuController::class, 'updateBuku']);
Route::get("searchBuku/{keyword}", [BukuController::class, 'searchBuku']);
Route::delete("deleteBuku/{id}", [BukuController::class, 'deleteBuku']);
Route::post("validateBuku", [BukuController::class, 'validateBuku']);
Route::post("uploadFoto", [BukuController::class, 'uploadFoto']);

// api resource
Route::apiResource("member", MemberController::class);

// auth using sanctum
// Route::post("login", [UserController::class, 'index']);

// jwt
// Route::get("user", [AuthControler::class, 'user']);
Route::post("register", [AuthControler::class, 'register']);
Route::post("login", [AuthControler::class, 'login']);