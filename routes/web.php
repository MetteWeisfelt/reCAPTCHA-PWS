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

Auth::routes();

Route::view('/', 'index')->name('index');

Route::view('/pws', 'pws')->name('pws.index');

Route::get('/recaptcha', 'RecaptchaController@index')->name('recaptcha.index');
Route::post('/recaptcha/get', 'RecaptchaController@recaptcha')->name('recaptcha.get');
Route::post('/recaptcha/result', 'RecaptchaController@result')->name('recaptcha.result');

Route::middleware(['is_admin'])->group(function() {
    Route::get('/admin', 'AdminController@index')->name('home');

    Route::resource('/admin/category', 'CategoryController', ['as' =>  'admin'])->except([
        'create',
        'show',
        'edit'
    ]);
    Route::post('/admin/category/data', 'CategoryController@data')->name('admin.category.data');
    Route::resource('/admin/subcategory', 'SubcategoryController', ['as' =>  'admin'])->except([
        'index',
        'create',
        'show',
        'edit'
    ]);
    Route::post('/admin/subcategory/data', 'SubcategoryController@data')->name('admin.subcategory.data');
    Route::resource('/admin/image', 'ImageController', ['as' =>  'admin'])->except([
        'create',
        'show',
        'edit',
        'update'
    ]);
    Route::resource('/admin/controlimage', 'ControlImageController', ['as' =>  'admin'])->except([
        'create',
        'show',
        'edit',
        'update'
    ]);
    Route::get('/admin/result', 'ResultController@index', ['as' =>  'admin'])->name('admin.result.index');
    Route::post('/admin/result/image/pieces', 'ResultController@image_pieces', ['as' =>  'admin'])->name('admin.result.image.pieces');
    Route::post('/admin/result/piece/result', 'ResultController@piece_result', ['as' =>  'admin'])->name('admin.result.image.piece.result');
});