<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CodeSmellController;


Route::get('/', function () {
    return view('welcome');
  

});

Route::get('/analyze', function () {
    return view('code-smell.analyze');
});

Route::post('/analyze', [CodeSmellController::class, 'analyze'])
    ->name('analyze.code');



