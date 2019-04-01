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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

/*
 * Admin routes
 */
Route::group(['prefix' => 'dp', 'middleware'=> 'auth'], function () {

    Route::get('', 'Admin\\MainController@index')->name('admin.index');
    Route::get('/about', 'Admin\\MainController@about')->name('admin.about');

    // Users
    Route::group(['prefix' => 'users'], function () {
        Route::get('', 'Admin\\UsersController@index')->name('admin.users');
        Route::get('/add', 'Admin\\UsersController@add')->name('admin.users.add');
        Route::post('/store', 'Admin\\UsersController@store')->name('admin.users.store');
        Route::get('/edit/{id}', 'Admin\\UsersController@edit')->name('admin.users.edit');
        Route::post('/update/{id}', 'Admin\\UsersController@update')->name('admin.users.update');
        Route::post('/remove/{id}', 'Admin\\UsersController@remove')->name('admin.users.remove');
        Route::get('/view/{id}', 'Admin\\UsersController@view')->name('admin.users.view');
    });

    // Pages
    Route::group(['prefix' => 'pages'], function () {
        Route::get('', 'Admin\\PagesController@index')->name('admin.pages');
        Route::get('index/{id}', 'Admin\\PagesController@index')->name('admin.pages.index');
        Route::get('/add/{parentId}', 'Admin\\PagesController@add')->name('admin.pages.add');
        Route::post('/store/{parentId}', 'Admin\\PagesController@store')->name('admin.pages.store');
        Route::get('/edit/{id}', 'Admin\\PagesController@edit')->name('admin.pages.edit');
        Route::post('/update/{id}', 'Admin\\PagesController@update')->name('admin.pages.update');
        Route::post('/remove/{id}', 'Admin\\PagesController@remove')->name('admin.pages.remove');
        Route::get('/media/add/{pageId}', 'Admin\\PagesController@mediaAdd')->name('admin.pages.media.add');
    });

    // Settings
    Route::group(['prefix' => 'settings'], function () {
        Route::get('', 'Admin\\SettingsController@index')->name('admin.settings.index');
        Route::post('', 'Admin\\SettingsController@update')->name('admin.settings.update');
    });
});

/*
 * CMS page routes
 */
Route::get('{slug}', 'PagesController@view')->where('search', '.*');
