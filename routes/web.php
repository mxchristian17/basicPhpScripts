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

Route::get('/', 'HomeController@index');

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/merge_sort', 'FunctionsController@mergeSortPage')->name('mergeSortPage');
Route::post('/mergesortpost', 'FunctionsController@mergeSortPost')->name('mergeSortPost');
Route::get('/quick_sort', 'FunctionsController@quickSortPage')->name('quickSortPage');
Route::post('/quicksortpost', 'FunctionsController@quickSortPost')->name('quickSortPost');
Route::get('/bucket_sort', 'FunctionsController@bucketSortPage')->name('bucketSortPage');
Route::post('/bucketsortpost', 'FunctionsController@bucketSortPost')->name('bucketSortPost');
Route::get('/heap_sort', 'FunctionsController@heapSortPage')->name('heapSortPage');
Route::post('/heapsortpost', 'FunctionsController@heapSortPost')->name('heapSortPost');
Route::get('/counting_sort', 'FunctionsController@countingSortPage')->name('countingSortPage');
Route::post('/countingsortpost', 'FunctionsController@countingSortPost')->name('countingSortPost');