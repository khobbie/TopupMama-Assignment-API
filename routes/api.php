<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\CharacterController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('comments')->group(function () {
    Route::get('/', [CommentController::class, 'getComments']);
    Route::post('/', [CommentController::class, 'create']);
    Route::get('/{book_id}', [CommentController::class, 'getSingleComment']);
});

Route::prefix('books')->group(function () {
    Route::get('/', [BookController::class, 'getBooks']);
    Route::get('/{book_id}', [BookController::class, 'getSingleBook']);
});


Route::prefix('characters')->group(function () {
    Route::get('/', [CharacterController::class, 'getCharacters']);
    Route::get('/{character_id}', [CharacterController::class, 'getSingleCharacter']);
});