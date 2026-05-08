<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::view('/type-field-visibility-demo', 'type-field-visibility-demo')
    ->name('type-field-visibility.demo');
