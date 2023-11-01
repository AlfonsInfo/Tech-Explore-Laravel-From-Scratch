<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('helloview', [
        "quotes" => 'It is never too late to be what you might have been. - George Eliot'
    ]);
})->name("landing-page");

Route::get("/test1", function(){
    return "test 1";
});


Route::get("/test2", function(){
    return  "test 2";
});


Route::redirect('/youtube','/');


Route::fallback(function(){
    return "redirect page";
});

Route::get('products/{productId}/items/{itemId}', function($productId,$itemId){
    return "Product $productId, item $itemId";
})->name("product.detail");

//*Route parameter regex
Route::get('/categories/{id}', function(String $categoryId){
    return 'Categories : ' . $categoryId;
})->where('id', '[0-9]+');



//*Optional Route Parameter

Route::get('/users/{id?}', function (string $userId = '404'){
    return "user $userId";
});



//* Routing conflict will not error, first router will become priority
//* Route can have name


//* Route + Controller
Route::get("/controller/hello",[App\Http\Controllers\HelloController::class,'hello']);

//* Request Input
Route::get("/input/hello",[App\Http\Controllers\InputController::class,'hello']);
Route::post("/input/hello",[App\Http\Controllers\InputController::class,'hello']);
Route::post("/input/hello/first",[App\Http\Controllers\InputController::class,'hello']);
Route::post("/input/hello/data",[App\Http\Controllers\InputController::class,'returnAllInput']);


//* Upload
Route::post("/file/upload", [App\Http\Controllers\UploadController::class, 'upload']);


//* Response
Route::get("/response", [App\Http\Controllers\ResponseController::class, 'response']);
