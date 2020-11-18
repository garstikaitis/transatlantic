<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\LocaleController;
use App\Http\Controllers\TranslationController;
use App\Http\Controllers\OrganizationController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\UserController;

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

Route::post('login', [AuthController::class, 'login']);
Route::post('register', [RegisterController::class, 'register']);

Route::group(['middleware' => 'api'], function() {
    Route::group(['prefix' => 'auth'], function() {
        Route::post('logout', [AuthController::class, 'logout']);
        Route::post('refresh', [AuthController::class, 'refresh']);
        Route::post('me', [AuthController::class, 'me']);
    });
    Route::group(['prefix' => 'users'], function() {
        Route::post('update', [UserController::class, 'updateUser']);
    });
    Route::group(['prefix' => 'locales'], function() {
        Route::get('/', [LocaleController::class, 'getAllLocales']);
    });
    Route::group(['prefix' => 'organizations'], function() {
        Route::get('/', [OrganizationController::class, 'getAllOrganizations']);
        Route::get('/{id}', [OrganizationController::class, 'getOrganizationById']);
        Route::post('/', [OrganizationController::class, 'createOrganization']);
        Route::post('/user', [OrganizationController::class, 'addUserToOrganization']);
        Route::post('/user/delete', [OrganizationController::class, 'removeUserFromOrganization']);
        Route::post('/locale', [OrganizationController::class, 'addLocaleToOrganization']);
        Route::post('/locale/delete', [OrganizationController::class, 'removeLocaleFromOrganization']);
    });
    Route::group(['prefix' => 'translations'], function() {
        Route::post('/', [TranslationController::class, 'getTranslations']);
        Route::post('/create', [TranslationController::class, 'createTranslation']);
        Route::post('/update', [TranslationController::class, 'updateTranslation']);
        Route::post('/delete', [TranslationController::class, 'deleteTranslation']);
    });
    Route::group(['prefix' => 'projects'], function() {
        Route::post('/', [ProjectController::class, 'getProjects']);
        Route::post('/create', [ProjectController::class, 'createProject']);
        Route::post('/delete', [ProjectController::class, 'deleteProject']);
    });
});
