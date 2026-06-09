<?php

use Illuminate\Support\Facades\Route;

Route::get('/streaming', function () {
    return view('streaming');
});
