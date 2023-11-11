<?php

use App\Http\Resources\CategoryCollection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Resources\CategoryResource;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::get("/categories/{id}", function($id){
    $category = \App\Models\Category::findOrFail($id);
    return new CategoryResource($category);
});


Route::get("/categories", function(){
    $category = \App\Models\Category::all();
    return  CategoryResource::collection($category);
});

    
Route::get("/custom-categories", function(){
    $category = \App\Models\Category::all();
    return  new CategoryCollection($category);
});