<?php

use App\Http\Controllers\FileUploader;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/user/add',function(){
    return view('userdetail');
})->name('user.add');

Route::post('/store', [UserController::class, 'store'])->name('store');

Route::get('/uploader',[FileUploader::class,'getfileUploader'])->name('get.fileUploader');
Route::post('/uploader',[FileUploader::class,'postfileUploader'])->name('post.fileUploader');
Route::get('/downloaduploader/{id}',[FileUploader::class,'downloadFile'])->name('post.downloadFile');
Route::get('/delete/file/{id}',[FileUploader::class,'deleteFile'])->name('post.deleteFile');


    

