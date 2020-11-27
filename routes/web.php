<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });

Route::namespace('Admin')->prefix('admin')->name('admin.')->group(function () {

    // ログイン認証関連
    Auth::routes([
        'register' => false,
        'reset'    => false,
        'verify'   => false
    ]);

    // ログイン認証後
    Route::middleware('auth:admin')->group(function () {

        // TOPページ
        Route::resource('home', 'HomeController', ['only' => 'index']);

        // User
        Route::resource('users', 'UsersController');

        Route::post('/users/enable', 'UsersController@enable')->name('users.enable');
        Route::post('/users/disable', 'UsersController@disable')->name('users.disable');
        Route::get('/users/import/create', 'Users\ImportController@create')->name('users.import.create');
        Route::post('/users/import/confirm', 'Users\ImportController@confirm')->name('users.import.confirm');
        Route::post('/users/import/save', 'Users\ImportController@save')->name('users.import.save');
        Route::post('/users/import/download', 'Users\ImportController@csvDownload')->name('users.import.download');
        Route::get('/users/import/sample_download', 'Users\ImportController@sampleDownload')->name('users.import.sample_download');

    });
});
