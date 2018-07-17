<?php

use Illuminate\Http\Request;

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

/**
 Доброго дня уважаемые разработчики! Посмотрев мой код вы поймете что написанно совсем не профессионально. Если меня направить в правильное русло то я смогу все. Я очень хочу работать с профессиональными программистами и развивать свой опыт. Я писал уже крупные проекты с рабочими моб. приложениями. Которое живут и по сей день. Задача котороя была поставлена по факту работает. Да я согласен не на проффесиональном уровне. Но к результату я пришел. Я был бы рад работать в команде проффесионалов и выкладываться на все сто. Не судите строго)
**/

Route::get('products', 'ApiController@getProducts');
Route::get('statuses', 'ApiController@getStatuses');
Route::get('users', 'ApiController@getUsers');
Route::get('order/{user_id?}', 'ApiController@getOrder');

Route::post('add/order', 'ApiController@addOrder');
Route::post('edit/order', 'ApiController@editOrder');
