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

//Route::get('/', function () {
////    dd(0);
////    return view('welcome');
//});




/*
 * Common 通用功能
 */
Route::group(['prefix'=>'common'], function () {

    $controller = "CommonController";

    // 验证码
    Route::match(['get','post'], 'change_captcha', $controller.'@change_captcha');

    //
    Route::get('dataTableI18n', function () {
        return trans('pagination.i18n');
    });
});






/*
 * YH 上海豫好
 */
Route::group(['domain'=>env('DOMAIN_ROOT'), 'namespace'=>'YH'], function () {
    require(__DIR__ . '/YH/route.php');
});
Route::group(['domain'=>env('DOMAIN_YH_SUPER'), 'namespace'=>'YH'], function () {
    require(__DIR__ . '/YH/route-super.php');
});
Route::group(['domain'=>env('DOMAIN_YH_ADMIN'), 'namespace'=>'YH'], function () {
    require(__DIR__ . '/YH/route-admin.php');
});
Route::group(['domain'=>env('DOMAIN_YH_STAFF'), 'namespace'=>'YH'], function () {
    require(__DIR__ . '/YH/route-staff.php');
});





/*
 * WL 物流
 */
Route::group(['domain'=>env('DOMAIN_WL_ROOT'), 'namespace'=>'WL'], function () {
    require(__DIR__ . '/WL/wl-root.php');
});
Route::group(['domain'=>env('DOMAIN_WL_SUPER'), 'namespace'=>'WL'], function () {
    require(__DIR__ . '/WL/wl-super.php');
});
Route::group(['domain'=>env('DOMAIN_WL_ADMIN'), 'namespace'=>'YH'], function () {
    require(__DIR__ . '/YH/route-admin.php');
});
Route::group(['domain'=>env('DOMAIN_WL_STAFF'), 'namespace'=>'WL'], function () {
    require(__DIR__ . '/WL/wl-staff.php');
});
Route::group(['domain'=>env('DOMAIN_WL_CLIENT'), 'namespace'=>'WL'], function () {
    require(__DIR__ . '/WL/wl-client.php');
});


