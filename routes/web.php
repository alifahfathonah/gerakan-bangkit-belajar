<?php

use Illuminate\Support\Facades\Auth;
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

// Route::get('/test', function () {
//     return view('berita.spesifik');
// });
Route::get('/', 'LandingPageController@about')->name('index');
// Berita
Route::get('/berita', 'BeritaController@index')->name('berita');
Route::post('/unpublish/{id}', 'BeritaController@unPublish')->name('unpublish');
Route::get('/berita/show/{seo_judul}', 'BeritaController@show')->name('show-berita');
Route::get('/about', 'AboutController@index')->name('about');


// API KOTA DAN KECAMATAN
Route::get('/api/city/', 'RelawanController@getCity');
Route::get('/api/district/', 'RelawanController@getDistrict');
Route::get('/api/village/', 'RelawanController@getVillage');


// ADMIN 
Route::group(['prefix' => 'gbb'], function () {
    Route::post('store-login', 'Admin\LoginController@store')->name('store-login');
    Route::get('login', 'Admin\LoginController@create')->name('login-admin');
    Route::get('register', 'Admin\RegisterController@create')->name('register-admin');
    Route::post('store-register', 'Admin\RegisterController@store')->name('store-register');
    Route::get('edit-profile/{id}', 'Admin\DashboardController@editProfile')->name('edit-profile-admin');
    Route::post('update-profile/{id}', 'Admin\DashboardController@updateProfile')->name('update-profile-admin');

    Route::group(['middleware' => 'admin'], function () {
        Route::get('dashboard', 'Admin\DashboardController@index')->name('dashboard');
        Route::get('logout', 'Admin\LoginController@logout')->name('logout-admin');

        // Laporan 
        Route::group(['prefix' => 'laporan'], function () {
            Route::get('admin', 'Admin\LaporanAdminController@index')->name('laporan-admin');
            Route::post('hasil', 'Admin\LaporanAdminController@hasil')->name('index-hasil-laporan');
            Route::get('cetak-pdf/{id}', 'Admin\LaporanAdminController@cetak')->name('cetak-laporan-admin');

        });

        // About
        Route::group(['prefix' => 'about'], function () {
            Route::get('/create', 'AboutController@create')->name('create-about');
            Route::get('/table', 'AboutController@table')->name('table-about');
            Route::post('/store', 'AboutController@store')->name('store-about');
            Route::get('/edit/{id}', 'AboutController@edit')->name('edit-about');
            Route::post('update/{id}', 'AboutController@update')->name('update-about');
            Route::post('delete/{id}', 'AboutController@destroy')->name('delete-about');
        });

        // BERITA
        Route::group(['prefix' => 'berita'], function () {
            Route::get('table', 'Admin\BeritaController@table')->name('table-berita-admin');
            Route::get('create', 'Admin\BeritaController@create')->name('create-berita-admin');
            Route::post('store', 'Admin\BeritaController@store')->name('store-berita-admin');
            Route::post('confirm/{id}', 'Admin\BeritaController@confirm')->name('confirm-berita');
            Route::get('edit/{id}', 'Admin\BeritaController@edit')->name('edit-berita-admin');
            Route::post('update/{id}', 'Admin\BeritaController@update')->name('update-berita-admin');
            Route::post('delete/{id}', 'Admin\BeritaController@destroy')->name('delete-berita-admin');
        });
        // SANGGAR
        Route::group(['prefix' => 'sanggar'], function () {
            Route::get('create', 'Admin\SanggarController@create')->name('create-sanggar');
            Route::get('index', 'Admin\SanggarController@index')->name('index-sanggar');
        });

        // ANGGOTA DEWAN
        Route::group(['prefix' => 'anggota-dewan'], function () {
            Route::get('create', 'Admin\AnggotaDewanController@create')->name('create-anggota-dewan');
            Route::post('store', 'Admin\AnggotaDewanController@store')->name('store-dewan');
            Route::get('index', 'Admin\AnggotaDewanController@index')->name('index-dewan');
            Route::get('edit/{id}', 'Admin\AnggotaDewanController@edit')->name('edit-dewan');
            Route::post('update/{id}', 'Admin\AnggotaDewanController@update')->name('update-dewan');
            Route::post('delete/{id}', 'Admin\AnggotaDewanController@destroy')->name('delete-dewan');
        });
        // RELAWAN
        Route::group(['prefix' => 'relawan'], function () {
            Route::get('create', 'Admin\DataRelawanController@create')->name('create-data-relawan');
            Route::get('index', 'Admin\DataRelawanController@index')->name('index-data-relawan');
            Route::get('edit/{id}', 'Admin\DataRelawanController@edit')->name('edit-data-relawan');
            Route::post('store', 'Admin\DataRelawanController@store')->name('store-data-relawan');
            Route::post('update/{id}', 'Admin\DataRelawanController@update')->name('update-data-relawan');
            Route::post('delete/{id}', 'Admin\DataRelawanController@destroy')->name('delete-data-relawan');

            // AKUN USER
            Route::group(['prefix' => 'account'], function () {
                Route::get('/index', 'Admin\DataRelawanController@indexAccount')->name('index-account-relawan');
                Route::get('/create', 'Admin\DataRelawanController@createAccount')->name('create-account-relawan');
                Route::post('/store', 'Admin\DataRelawanController@storeAccount')->name('store-account-relawan');
                Route::get('/edit/{id}', 'Admin\DataRelawanController@editAccount')->name('edit-account-relawan');
                Route::post('/update/{id}', 'Admin\DataRelawanController@updateAccount')->name('update-account-relawan');
                Route::post('/update/password/{id}', 'Admin\DataRelawanController@updatePassword')->name('update-password-account');
                Route::post('/delete/{id}', 'Admin\DataRelawanController@deleteAccount')->name('delete-account-relawan');
            });

            // Jenjang 
            Route::group(['prefix' => 'jenjang'], function () {
                Route::get('/index', 'Admin\DataRelawanController@indexJenjang')->name('index-jenjang-relawan');
                Route::get('/create', 'Admin\DataRelawanController@createJenjang')->name('create-jenjang-relawan');
                Route::get('/edit/{id}', 'Admin\DataRelawanController@editJenjang')->name('edit-jenjang-relawan');
                Route::post('/update/{id}', 'Admin\DataRelawanController@updateJenjang')->name('update-jenjang-relawan');
                Route::post('/delete/{id}', 'Admin\DataRelawanController@deleteJenjang')->name('delete-jenjang-relawan');
                Route::post('/store', 'Admin\DataRelawanController@storeJenjang')->name('store-jenjang-relawan');
            });
        });
    });
});




Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/profile/edit/{id}', 'ProfileUserController@edit')->name('profile-edit');

// Berita
Route::group(['prefix' => 'berita'], function () {
    Route::get('create', 'BeritaController@create')->name('create-berita');
    Route::post('store', 'BeritaController@store')->name('store-berita');
    Route::get('table', 'BeritaController@table')->name('table-berita');
    Route::get('/edit/{id}', 'BeritaController@edit')->name('edit-berita');
    Route::post('/update/{id}', 'BeritaController@update')->name('update-berita');
    Route::post('/delete/{id}', 'BeritaController@destroy')->name('delete-berita');
});


Route::group(['prefix' => 'relawan', 'middleware' => 'auth'], function () {
    Route::get('/create', 'RelawanController@create')->name('create-relawan');
    Route::post('/store', 'RelawanController@store')->name('store-relawan');
    Route::get('sanggar/create', 'SanggarController@create')->name('create-sanggar-user');
    Route::post('sanggar/store', 'SanggarController@store')->name('store-sanggar-user');
});

Route::group(['prefix' => 'laporan'], function () {
    Route::get('/monev/create', 'MonevController@create')->name('create-monev');
    Route::post('/monev/store', 'MonevController@store')->name('store-monev');
    Route::get('/index', 'LaporanTeamController@index')->name('laporan');
    Route::post('/hasil', 'LaporanTeamController@hasil')->name('hasil-laporan');
    Route::get('/export/{id}', 'LaporanTeamController@export')->name('laporan-export');
});
