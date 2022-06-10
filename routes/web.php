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
Route::get('/merge_sort', 'MergeSortController@mergeSortPage')->name('mergeSortPage');
Route::post('/mergesortpost', 'MergeSortController@mergeSortPost')->name('mergeSortPost');
Route::get('/quick_sort', 'QuickSortController@quickSortPage')->name('quickSortPage');
Route::post('/quicksortpost', 'QuickSortController@quickSortPost')->name('quickSortPost');
Route::get('/bucket_sort', 'BucketSortController@bucketSortPage')->name('bucketSortPage');
Route::post('/bucketsortpost', 'BucketSortController@bucketSortPost')->name('bucketSortPost');
Route::get('/heap_sort', 'HeapSortController@heapSortPage')->name('heapSortPage');
Route::post('/heapsortpost', 'HeapSortController@heapSortPost')->name('heapSortPost');
Route::get('/counting_sort', 'CountingSortController@countingSortPage')->name('countingSortPage');
Route::post('/countingsortpost', 'CountingSortController@countingSortPost')->name('countingSortPost');
Route::get('/binary_search', 'BinarySearchController@binarySearchPage')->name('binarySearchPage');
Route::post('/binarysearchpost', 'BinarySearchController@binarySearchPost')->name('binarySearchPost');