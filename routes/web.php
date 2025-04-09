<?php

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Routee::get('/setup', function () {
    $creds = [
        'email' => 'admin@admin.com',
        'password' => 'karim',
    ];

    if(!Auth::attempt($creds)) {
        $user = new User();

        $user->name = 'admin';
        $user->email = $creds['email'];
        $user->password = Hash::make($creds['password']);

        $user->save();
    }
});
