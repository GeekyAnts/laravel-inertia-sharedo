<?php

use Illuminate\Support\Facades\Route;
use Geekyants\ShareDialog\Controllers\ShareDialogController;


Route::group(['middleware' => config('share-dialog.middleware')], function () {
    Route::get('/share-dialog/{entity}/{entityId}', [ShareDialogController::class, 'showShareDialog'])->name('share-dialog');
    Route::post('/share-dialog', [ShareDialogController::class, 'assignAbility']);
});
