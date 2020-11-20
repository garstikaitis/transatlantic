<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\LocaleController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\TranslationController;
use App\Http\Controllers\OrganizationController;

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
Route::post('auth/logout', [AuthController::class, 'logout']);
Route::post('auth/refresh', [AuthController::class, 'refresh']);
Route::get('/client/translations', [ClientController::class, 'getOrganizationProjectTranslations']);

Route::group(['middleware' => ['api', 'throttleIp']], function() {
    Route::group(['prefix' => 'auth'], function() {
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
        Route::get('/user', [OrganizationController::class, 'getUserOrganizations']);
        Route::get('/{id}', [OrganizationController::class, 'getOrganizationById']);
        Route::post('/', [OrganizationController::class, 'createOrganization']);
        Route::post('/user', [OrganizationController::class, 'addUserToOrganization']);
        Route::post('/user/delete', [OrganizationController::class, 'removeUserFromOrganization']);
        Route::post('/locale', [OrganizationController::class, 'addLocaleToOrganization']);
        Route::post('/locale/delete', [OrganizationController::class, 'removeLocaleFromOrganization']);
        Route::post('/settings/api-keys', [OrganizationController::class, 'generateApiKeyForOrganization']);
        Route::get('/settings/api-keys', [OrganizationController::class, 'getApiKeyForOrganization']);
    });
    Route::group(['prefix' => 'translations'], function() {
        Route::post('/', [TranslationController::class, 'getTranslations']);
        Route::post('/create', [TranslationController::class, 'createTranslation']);
        Route::post('/update', [TranslationController::class, 'updateTranslation']);
        Route::post('/delete', [TranslationController::class, 'deleteTranslation']);
    });
    Route::group(['prefix' => 'projects'], function() {
        Route::post('/', [ProjectController::class, 'getProjects']);
        Route::get('/{id}', [ProjectController::class, 'getProject']);
        Route::post('/create', [ProjectController::class, 'createProject']);
        Route::post('/update', [ProjectController::class, 'updateProject']);
        Route::post('/delete', [ProjectController::class, 'deleteProject']);
    });
});
