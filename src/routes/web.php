<?php

use Illuminate\Support\Facades\Route;
use Geekyants\Sharedo\Controllers\SharedoController;


Route::group(['middleware' => config('sharedo.middleware')], function () {
    Route::get('sharedo/searchUsers/{query}', [SharedoController::class, 'searchUsers']);
    Route::get('/sharedo/{entity}/{entityId}', [SharedoController::class, 'showSharedo'])->middleware('restrict-entities')->name('sharedo');
    Route::post('/sharedo', [SharedoController::class, 'assignAbility']);
});
