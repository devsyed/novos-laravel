<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TextFilesController;

Route::prefix('novos/v1')->group(function () {
    Route::post('/login', [AuthController::class,'login']);

    Route::middleware('auth:sanctum')->group(function(){
        Route::post('/create_file', [TextFilesController::class, 'createFile']);
        Route::get('/get_file/{file_name}', [TextFilesController::class,'getFile']);
        Route::delete('/delete_file/{file_name}', [TextFilesController::class,'deleteFile']);
        Route::get('/get_contents', [TextFilesController::class,'getContents']);
    });
});