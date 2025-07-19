<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/test-login', function () {
    $request = Request::create('/api/login', 'POST', [
        'phone' => '09120000000',
        'password' => '123456',
    ]);

    $response = app()->handle($request);
    return $response;
});

Route::get('/test-update-location', function () {
    $token = 'WtzF9dIEOtfoBWCFp72e7zUTEf3ftz4fQ08DfLdae51b755b';

    $request = Request::create('/api/provider/location/update', 'POST', [
        'lat' => 35.7001,
        'lng' => 51.4099,
        'is_online' => true
    ]);

    $request->headers->set('Authorization', 'Bearer ' . $token);

    $response = app()->handle($request);
    return $response;
});


Route::get('/test-nearby', function () {
    $token = 'WtzF9dIEOtfoBWCFp72e7zUTEf3ftz4fQ08DfLdae51b755b';

    $request = \Illuminate\Http\Request::create('/api/providers/nearby?lat=35.7021&lng=51.4031', 'GET');
    $request->headers->set('Authorization', 'Bearer ' . $token);

    $response = app()->handle($request);
    return $response;
});