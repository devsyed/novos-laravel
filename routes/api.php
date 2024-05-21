<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TextFilesController;

Route::prefix('novos/v1')->group(function () {
    Route::post('/login', [AuthController::class,'login']);

    Route::middleware('auth:sanctum')->group(function(){
        Route::get('/files', [TextFilesController::class, 'index']);
        Route::post('/files', [TextFilesController::class, 'createFile']);
        Route::get('/files/{file_name}', [TextFilesController::class,'getFile']);
        Route::delete('/files/{file_name}', [TextFilesController::class,'deleteFile']);
        Route::get('/get_contents', [TextFilesController::class,'getContents']);
    });
});