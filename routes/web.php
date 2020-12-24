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

// Route::get('/home', 'HomeController@index')->name('home');
Route::get('/', 'HomeController@dashboard')->name('dashboard');

Route::get('/kriteria', 'KriteriaController@index')->name('kriteria.index');
Route::post('/kriteria', 'KriteriaController@store')->name('kriteria.store');
Route::get('/kriteria/{kriteria}/edit', 'KriteriaController@edit')->name('kriteria.edit');
Route::delete('/kriteria/{kriteria}', 'KriteriaController@destroy')->name('kriteria.delete');
Route::patch('/kriteria/{kriteria}', 'KriteriaController@update')->name('kriteria.update');

Route::get('/alternatif', 'AlternatifController@index')->name('alternatif.index');
Route::post('/alternatif', 'AlternatifController@store')->name('alternatif.store');
Route::get('/alternatif/{alternatif}/edit', 'AlternatifController@edit')->name('alternatif.edit');
Route::delete('/alternatif/{alternatif}', 'AlternatifController@destroy')->name('alternatif.delete');
Route::patch('/alternatif/{alternatif}', 'AlternatifController@update')->name('alternatif.update');

Route::get('/penilaian-kriteria/detail', 'PKriteriaController@detail')->name('pkriteria.detail');
Route::get('/penilaian-kriteria', 'PKriteriaController@index')->name('pkriteria.index');
Route::post('/penilaian-kriteria', 'PKriteriaController@store')->name('pkriteria.store');
Route::delete('/penilaian-kriteria/{pkriteria}', 'PKriteriaController@destroy')->name('pkriteria.delete');

Route::get('/penilaian-alternatif/detail', 'PAlternatifController@detail')->name('palternatif.detail');
Route::get('/penilaian-alternatif', 'PAlternatifController@index')->name('palternatif.index');
Route::post('/penilaian-alternatif', 'PAlternatifController@store')->name('palternatif.store');
Route::delete('/penilaian-alternatif/{palternatif}', 'PAlternatifController@destroy')->name('palternatif.delete');

Route::get('/bobot', 'BobotController@index')->name('bobot.index');
Route::post('/bobot', 'BobotController@store')->name('bobot.store');
Route::get('/bobot/{bobot}/edit', 'BobotController@edit')->name('bobot.edit');
Route::delete('/bobot/{bobot}', 'BobotController@destroy')->name('bobot.delete');
Route::patch('/bobot/{bobot}', 'BobotController@update')->name('bobot.update');

// Route::get('/alternatif', 'HomeController@alternatif')->name('alternatif');
