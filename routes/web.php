<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();
Route::get('/dashboard', 'HomeController@index')->name('dashboard');
Route::resource('bucket', 'BucketController');

Route::get('file/{bucket}', ['as' => 'file.index', 'uses' => 'FileController@index']);
Route::get('file/view/{id}/{bucket}', ['as' => 'file.show', 'uses' => 'FileController@show']);
Route::resource('file', 'FileController', ['except' => ['index', 'show', 'edit', 'create']]);

Route::get('/files/{filePath}', 'FileController@download')
    ->where(['filePath' => '.*'])->name('storage.file');
