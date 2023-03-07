<?php

use App\Http\Controllers\Controller;
use App\Http\Controllers\FixtureController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\SimulationController;
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

Route::get('/', [Controller::class, 'home'])->name('home');
Route::get('/teams', [TeamController::class, 'index']);
Route::get('/fixtures', [FixtureController::class, 'index'])->name('fixtures.index');
Route::get('/simulations', [SimulationController::class, 'index'])->name('simulation.index');
Route::post('/generate-fixtures', [TeamController::class, 'generateFixtures']);
