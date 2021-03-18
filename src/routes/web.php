<?php

use Illuminate\Support\Facades\Route;
use Geekyants\ShareDialog\Controllers\ShareDialogController;


Route::group(['middleware' => config('share-dialog.middleware')], function () {
    Route::get('share-dialog/searchUsers/{query}', [ShareDialogController::class, 'searchUsers']);
    Route::get('/share-dialog/{entity}/{entityId}', [ShareDialogController::class, 'showShareDialog'])->middleware('restrict-entities')->name('share-dialog');
    Route::post('/share-dialog', [ShareDialogController::class, 'assignAbility']);
    Route::post('/share-link', [ShareDialogController::class, 'copyInviteLink']);
    Route::get('/invite-user', [ShareDialogController::class, 'inviteLink']);
});
