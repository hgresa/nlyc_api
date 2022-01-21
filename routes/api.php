<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ArticlesController;
use App\Http\Controllers\TagsController;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('articles', [ArticlesController::class, 'getArticles']);
Route::get('articles/{id}/comments', [ArticlesController::class, 'getComments']);

Route::get('tags', [TagsController::class, 'getTags']);
Route::get('tags/{id}/articles', [TagsController::class, 'getArticles']);
