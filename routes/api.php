<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\LabelController;
use App\Http\Controllers\NoteController;

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

Route::group([

    'middleware' => 'api',
    'prefix' => 'auth'

], function ($router) {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/refresh', [AuthController::class, 'refresh']);
    Route::get('/me', [AuthController::class, 'me']);
    Route::get('/isauth', [AuthController::class, 'isAuth']);
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/update', [AuthController::class, 'update']);
    Route::get('/get-avatar/{filename}', [AuthController::class, 'getAvatar']);
 
});
Route::group([

    'middleware' => 'api',
], function ($router) {
    Route::delete('/delete-all', [NoteController::class,'delete'] );
    Route::get('/trash', [NoteController::class,'trash'] );
    Route::get('/note/{note}', [NoteController::class,'get'] );
    Route::get('/note', [NoteController::class,'index'] );
    Route::post('/note/{note}', [NoteController::class,'update'] );
    Route::delete('/note/{noteD}', [NoteController::class,'destroy'] );
    Route::delete('/force-delete/{note}', [NoteController::class,'forceDelete'] );
    Route::get('/restore/{note}', [NoteController::class,'restore'] );
    Route::get('/copy/{note}', [NoteController::class,'copy'] );
    Route::post('/note', [NoteController::class,'create'] );

    Route::post('/label-create', [LabelController::class,'create'] );
    Route::get('/label', [LabelController::class,'index'] );
    Route::get('/notes-by-label/{label}', [LabelController::class,'searchByLabel'] );
    Route::post('/label/{label}', [LabelController::class,'index'] );
    Route::post('/set-label', [LabelController::class,'setLabeltoNote'] );
    Route::delete('/label/{labelD}', [LabelController::class,'destroy'] );
});