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


Route::view('/', 'builder.builder');
Route::view('/customdemo', 'builder.custombuilder');
Route::view('/sfra', 'builder.sfrabuilder');
Route::view('/nto', 'builder.ntobuilder');
Route::post('/check-selector', 'ApiController@checkSelector');
Route::post('/check-sandbox-credentials', 'ApiController@checkSandboxCredentials');
Route::post('/build-demo', 'ApiController@buildDemo');
Route::post('/build-sfra', 'ApiController@buildSfraDemo');
Route::get('/test', 'TestController@test');
Route::get('/refresh-csrf', function(){
    return csrf_token();
});

