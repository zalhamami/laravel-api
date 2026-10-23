<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Laravel\Passport\Http\Controllers\AccessTokenController;
use Laravel\Passport\Http\Controllers\ScopeController;

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

Route::group(['prefix' => 'type'], function () {
    Route::get('/', 'TypeController@index');
    Route::get('/{id}', 'TypeController@show');
    Route::group(['middleware' => 'auth:api'], function () {
        Route::post('/', 'TypeController@store');
        Route::put('/{id}', 'TypeController@update');
    });
});

Route::group(['prefix' => 'oauth'], function () {
    Route::post('login', 'AuthController@login');
    Route::post('register', 'AuthController@signup');
    Route::group(['middleware' => 'auth:api'], function() {
        Route::delete('/revoke', 'AuthController@revokeUserToken');
        Route::get('/info', 'AuthController@checkAuth');
    });
    Route::group(['middleware' => 'client.credentials'], function() {
        Route::get('/client/info', 'AuthController@checkAuth');
    });
});

Route::prefix('oauth')->group(function () {
    Route::post('/token', [AccessTokenController::class, 'issueToken'])->name('api.oauth.token');
    Route::get('/scopes', [ScopeController::class, 'all'])->name('api.oauth.scopes.index');
});

//Route::post('password/reset', 'Auth\ResetPasswordController@reset');

Route::group(['prefix' => 'email/verify'], function () {
    // Not named "verification.verify": that name belongs to the web route in routes/auth.php,
    // which is the link VerifyEmailNotification sends.
    Route::get('/{id}', 'EmailVerificationController@verify')->name('api.verification.verify');
    Route::post('/{value}/resend', 'EmailVerificationController@resend')->name('verification.resend');
});

Route::group(['prefix' => 'me', 'middleware' => 'auth:api'], function () {
    Route::get('user', 'UserController@showMyData');
    Route::put('user', 'UserController@updateMyData');
    Route::post('user/password/create', 'UserController@createPassword');
    Route::post('user/password/change', 'UserController@changePassword');
});

Route::group(['prefix' => 'user'], function () {
    Route::post('/', 'UserController@store');
    Route::group(['middleware' => ['auth:api', 'role:admin']], function () {
        Route::get('/', 'UserController@index');
        Route::delete('/{id}', 'UserController@destroy');
    });
});

Route::post('upload', function (Request $request) {
    $request->validate([
        'image' => ['file', 'max:4096', 'mimes:jpg'],
    ]);
    $storage = \Illuminate\Support\Facades\Storage::disk('gdrive');
    $result = $storage->put(config('filesystems.gdrive.folder_id'), $request->file('image'));
    return response()->json([
       'code' => 200,
       'data' => $result,
    ]);
});
