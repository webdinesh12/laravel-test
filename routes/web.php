<?php

use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

Route::controller(HomeController::class)->group(function () {
    Route::get('/', 'index')->name('index');
    Route::get('/users', 'users')->name('users');
    Route::get('/create-user', 'createuser')->name('user.create');
    Route::get('/user/edit/{id}', 'editUser')->name('user.edit');
    Route::get('/user/delete/{id}', 'deleteUser')->name('user.delete');
    Route::post('/create-user', 'doCreateUser')->name('do.create.user');
    Route::get('/user/export', 'exportUsers')->name('user.export');

    Route::get('/audio-length', 'audioLength')->name('audio.length');
    Route::post('/audio-length', 'doCheckAudioLength')->name('do.audio.length');

    Route::get('/find-distence', 'findDistence')->name('distence');
    Route::post('/find-distence', 'doFindDistence')->name('do.find.distence');
});

Route::get('/ss', function(){
    phpinfo();
});
