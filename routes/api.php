<?php

use App\Http\Controllers\QuestController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('quest', [QuestController::class, 'index']);
Route::post('quest', [QuestController::class, 'store']);
Route::get('quest/{quest}', [QuestController::class, 'show']);
Route::put('quest/{quest}', [QuestController::class, 'update']);
Route::delete('quest/{quest}', [QuestController::class, 'destroy']);

Route::middleware('auth:sanctum')->group(function () {
    //
});
