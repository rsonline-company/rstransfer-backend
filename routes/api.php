<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FileController;

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

Route::post('files/store', [FileController::class, 'store']);

Route::get('download/{name}', function($fileName) {
    $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);
    $filePath = public_path().'/storage/files/'.$fileName;
    $headers = ["Content-Type: application/$fileExtension"];

    return response()->download($filePath, $fileName, $headers);
});