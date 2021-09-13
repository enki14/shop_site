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

Route::any('/', 'ShopsiteController@index');
// Route::get('/result', 'ShopsiteController@pagination');
Route::any('/result', 'ShopsiteController@result');
Route::get('/result-2', 'ShopsiteController@keyRes');
Route::any('/result-2', 'ShopsiteController@index');
Route::get('/map_modal', 'ShopsiteController@mapModal');
Route::get('/map_data', 'ShopsiteController@mapData');




// Route::get('/', 'KeysController@index');
// Route::post('/result-2', 'KeysController@keyRes');


// Route::get('/sample', 'SampleController@index');  //sampleはurl、controllerはどのメソッドまでかを指定
// ajax用データベース読み込み
Route::get('/ajax', 'AjaxController@index');
Route::post('/ajax_insert', 'AjaxController@insert');
Route::post('/ajax_update', 'AjaxController@update');
Route::get('/ajax_delete', 'AjaxController@delete');


//form用データベース読み込み
Route::get('form', 'FormController@index')->name('form');
Route::post('form_insert', 'FormController@insert')->name('form_insert');
Route::post('form_update', 'FormController@update')->name('form_update');
Route::post('form_delete', 'FormController@delete')->name('form_delete');

// scrapingTest
Route::get('/scrape','TestController@scrape');


// twitter api
Route::get('twitter', 'TwitterController@index');
// Route::get('twitter', 'TwitterController@s_Tweet');
// Route::get('twitter', 'TwitterController@uSearch');
// Route::get('twitter', 'TwitterController@imageText');


// google maps api（仮）　※とりあえず表示だけの仕様
Route::get('maps', function () {
   return view('maps');
});

// google maps api　(遊び)
Route::get('google', 'GMapController@index');
Route::get('google_shop', 'GMapController@shopInfo');


/*************************** 管理画面用↓↓↓ *************************/

// 自動で挿入されたもの
Auth::routes();
// 自動で挿入されたもの
// Route::get('/home', 'HomeController@index')->name('home');


//管理側ミドルウェア設定
Route::group(['middleware' => ['auth.admin']], function () {
	
	//管理側トップ
	Route::get('/admin', 'admin\AdminTopController@show');
	//ログアウト実行
	Route::post('/admin/logout', 'admin\AdminLogoutController@logout');
	//ユーザー一覧
	Route::get('/admin/user_list', 'admin\ManageUserController@showUserList');
	//ユーザー詳細
	Route::get('/admin/user/{id}', 'admin\ManageUserController@showUserDetail');

});

//管理側ログイン
Route::get('/admin/login', 'admin\AdminLoginController@showLoginform');
Route::post('/admin/login', 'admin\AdminLoginController@login');


// 実際の管理作業用
Route::get('/home', 'HomeController@index')->name('home');
// Route::post('/home_insert', 'HomeController@insert')->name('home_insert');
Route::post('/home_syokai', 'HomeController@syokai')->name('home_syokai');
Route::post('/home_local', 'HomeController@localData')->name('home_local');
Route::post('/home_dataplus', 'HomeController@dataplus')->name('home_dataplus');
Route::post('/home_seiyu', 'HomeController@seiyuData');
// Route::post('/home_update', 'HomeController@update')->name('home_update');
// Route::post('/home_delete', 'HomeController@delete')->name('home_delete');