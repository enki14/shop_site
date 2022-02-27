<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
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
Route::any('/result', 'ShopsiteController@result')->name('result');
Route::any('/result-2', 'ShopsiteController@keyRes')->name('result-2');
Route::get('/map_modal', 'ShopsiteController@mapModal');
Route::post('/map_data', 'ShopsiteController@mapData');
Route::get('/eventCalendar_2', 'ShopsiteController@eventCalendar_2');
Route::get('/policy', function () {
	return view('page.policyPage');
 })->name('policy');
Route::get('/profile', function() {
	return view('page.profile_P');
})->name('profile');
Route::get('/disclaimer', function() {
	return view('page.disclaimer_P');
})->name('disclaimer');


/********* 問い合わせフォーム **********/
//入力ページ
Route::get('/contact', 'ContactController@index')->name('page.contact_P');
//確認ページ
Route::post('/contact/confirm', 'ContactController@confirm')->name('page.confirm_P');
//送信完了ページ
Route::post('/contact/thanks', 'ContactController@send')->name('page.thanks_P');


// store登録
Route::post('/daiei_store', 'StoreSetController@daiei_store');
Route::post('/summit_store', 'StoreSetController@summit_store');
Route::post('/maruetu_store', 'StoreSetController@maruetu_store');
Route::post('/inageya_store', 'StoreSetController@inageya_store');
Route::post('/comodiIida_store', 'StoreSetController@comodiIida_store');
Route::post('/ozeki_store', 'StoreSetController@ozeki_store');
Route::post('/tokyu_store', 'StoreSetController@tokyu_store');
Route::post('/peacock_store', 'StoreSetController@peacock_store');
Route::post('/sanwa_store', 'StoreSetController@sanwa_store');
Route::post('/keio_store', 'StoreSetController@keio_store');
Route::post('/santoku_store', 'StoreSetController@santoku_store');
Route::post('/tobu_store', 'StoreSetController@tobu_store');
Route::post('/ozam_store', 'StoreSetController@ozam_store');
Route::post('/itoyokado', 'StoreSetController@itoyokado');
Route::post('/aeon_store', 'StoreSetController@aeon_store');
Route::post('/superalps', 'StoreSetController@superalps');
Route::post('/york', 'StoreSetController@york');
Route::post('/ok_store', 'StoreSetController@ok_store');

// event登録
Route::post('/itoyokado_event', 'EventSetController@itoyokado_event');
Route::post('/summit_event', 'EventSetController@summit_event');
Route::post('/maruetsu_5off', 'EventSetController@maruetsu_5off');
Route::post('/maruetsu_5times', 'EventSetController@maruetsu_5times');
Route::post('/inageya_sannichi', 'EventSetController@inageya_sannichi');
Route::post('/comodi_donichi', 'EventSetController@comodi_donichi');
Route::post('/keio_3times', 'EventSetController@keio_3times');
Route::post('/tobu_Tmoney', 'EventSetController@tobu_Tmoney');
Route::post('/alps_doniti', 'EventSetController@alps_doniti');
Route::post('/aeon_wakuwaku', 'EventSetController@aeon_wakuwaku');
Route::post('/aeon_thanks', 'EventSetController@aeon_thanks');
Route::post('/tobu_bonus', 'EventSetController@tobu_bonus');
Route::post('/tokyu_5off', 'EventSetController@tokyu_5off');
Route::post('/aeon_bonus', 'EventSetController@aeon_bonus');
Route::post('/aeon_arigato', 'EventSetController@aeon_arigato');


// event_listテーブルにセットするものは'/event_list'へ
Route::get('/seiyu_list', 'EventSetController@seiyu_list');
Route::post('/donki_list', 'EventSetController@megadonki_list');
Route::post('/aeon_list', 'EventSetController@aeon_list');
Route::post('/york_list', 'EventSetController@york_list');
Route::post('/seizyo_list', 'EventSetController@seizyo_list');

// 不定期イベント出力用
Route::get('/event_list', 'EventSetController@event_list');



// サムネイル作成（ url先で更新すると一枚作成される ）
// storage_path のファイル名と $file のファイル名を、作成したいファイル名に差し替える
Route::get('thumbnail', function(){
	$image = Image::make(storage_path('app') . '/public/image/maruetu_test.png');
	$file = 'maruetu_test.png';
	$path = public_path(). '/img/';
	$image->resize(180, 180, function($constraint){
		$constraint->aspectRatio();
	// crop()で黒い土台座標の高さと幅を調整することができる
	// crop()は基本、resize()に合わせておく
	})->crop(180, 180)->save($path . 'thumbnail-' . $file);
	return $image->response('png');
});


// 画像の文字認識
Route::get('business_card', 'DetectDocumentController@detect_document_text');
Route::get('business_card_2', 'DetectDocumentController@detect_document_text_2');
Route::get('itoyokado_event', 'DetectDocumentController@itoyokado_event');

// twitter api
Route::get('twitter', 'TwitterController@index');



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
Route::post('/home_life', 'HomeController@lifeinfo');