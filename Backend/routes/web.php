<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return response()->file(base_path('../Frontend/login.html'));
});

Route::post('/login', [AuthController::class, 'login']);
Route::post('/registro', [AuthController::class, 'register']);

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

Route::get('/login.html', function () {
    return response()->file(base_path('../Frontend/login.html'));
})->name('login');

Route::get('/{asset}', function (string $asset) {
    $frontendAssets = [
        'crear-tarea.css',
        'crear-tarea.html',
        'crear-tarea.js',
        'index.html',
        'login.css',
        'login.html',
        'registro.css',
        'registro.html',
        'usuario.css',
        'usuario.html',
    ];

    abort_unless(in_array($asset, $frontendAssets, true), 404);

    $path = base_path('../Frontend/'.$asset);
    abort_unless(is_file($path), 404);

    $contentType = match (pathinfo($path, PATHINFO_EXTENSION)) {
        'css' => 'text/css; charset=UTF-8',
        'js' => 'text/javascript; charset=UTF-8',
        default => 'text/html; charset=UTF-8',
    };

    return response()->file($path, ['Content-Type' => $contentType]);
})->where('asset', '[A-Za-z0-9_-]+\.(?:html|css|js)');
