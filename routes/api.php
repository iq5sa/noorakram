<?php
/*
 * Copyright (c) 2024 Noorakram.
 * Developed By: Jodx.
 * Contact: in@jodx.dev
 */

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::middleware('auth:api')->get('/user', function (Request $request) {
  return $request->user();
});


