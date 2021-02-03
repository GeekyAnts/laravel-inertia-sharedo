<?php

use Geekyants\ShareDialog\Controllers\ShareDialogController;



Route::group(['middleware' => ['web', 'auth']], function () {
    Route::get('/share-dialog/{entity}/{entityId}', [ShareDialogController::class, 'showShareDialog'])->name('share-dialog');
    Route::post('/share-dialog', [ShareDialogController::class, 'assignAbility']);
});
