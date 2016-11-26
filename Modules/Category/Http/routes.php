<?php

Route::group(['middleware' => 'web', 'prefix' => 'category', 'namespace' => 'Modules\Category\Http\Controllers'], function()
{
    //show all categories
    Route::get('/', 'CategoryController@index');
    Route::get ('/id/{id_category}', 'CategoryController@showId');
    Route::get ('/{slug_category}', 'CategoryController@showSlug');
});