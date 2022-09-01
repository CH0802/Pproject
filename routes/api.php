<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\DemoController;
use App\Http\Controllers\Api\Applet\AppletLoginController;
use App\Http\Controllers\Api\SelfdisciplineApplet\MyAdministrationController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });


Route::group(['namespace' => 'Api'], function () { 
    
    //身份验证
    Route::group(['prefix' => 'Demo'],function () {

        Route::get('test', [DemoController::class,'Testing']);
    });


    //打卡
    Route::group(['prefix' => 'PunchCard'],function () {
    	Route::post('WechatLogin', [AppletLoginController::class,'WechatLogin']);
        Route::get('test', [AppletLoginController::class,'Testing']);
        Route::post('getMyClassList', [MyAdministrationController::class,'GetMyClassificationList'])->middleware('Dk-Auth');
    });
});