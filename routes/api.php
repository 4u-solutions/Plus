<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
// use App\Http\Controllers\api\Auth\AuthController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
// routes/api.php
Route::get('/unauthenticated','Auth\unauthController@redirect')-> name("noautorizado");
Route::post('/register', 'api\Auth\AuthController@register');
Route::post('/login', 'api\Auth\AuthController@login');
Route::post('logout', 'api\Auth\AuthController@logout')->middleware('auth:sanctum');

Route::post('email/resend', 'api\Auth\EmailVerificationController@sendVerificationEmail')->name('verification.resend')->middleware('auth:sanctum');
Route::get('email/verify/{id}/{hash}', 'api\Auth\EmailVerificationController@verify')->name('verification.verify')->middleware('auth:sanctum');


Route::post('forgot-password', 'api\Auth\NewPasswordController@forgotPassword');
Route::post('reset-password', 'api\Auth\NewPasswordController@reset');

// Route::get("/listarTickets",'ticketsController@index');
Route::get("/user",'api\Auth\userController@index')->middleware('auth:sanctum','verified');

Route::get("/listarTickets",'api\ticketsController@index')->middleware('auth:sanctum');
Route::post("/guardarTicket",'api\carretillaController@store')->middleware('auth:sanctum');

Route::get("/comprobar/{idEvento}",'api\carretillaController@comprobar');
Route::get("/consultarcompra/{idCompra}",'api\carretillaController@consultar_compra');
Route::get("/listarcompras",'api\carretillaController@obtener_compras')->middleware('auth:sanctum');
Route::delete("/eliminarboleto/{idCompra}/{idAsiento}",'api\carretillaController@eliminar_boleto');

Route::post("/sendpay",'api\paymentController@sendpay')->middleware('auth:sanctum');

Route::post("/reversepay",'api\paymentController@reversepay');//no
Route::get("/events",'api\EventController@index');
Route::get("/event/{id}",'api\EventController@show');

Route::get("/comprobar/{idLocalidad}/{idEvento}",'api\carretillaController@comprobar');



Route::get("/home",'api\homeController@index');

// Route::get("/events",'api\EventController@index');
