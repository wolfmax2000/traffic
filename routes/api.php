<?php

Route::group(['prefix' => 'v1', 'as' => 'api.', 'namespace' => 'Api\V1\Admin', 'middleware' => ['auth:api']], function () {
    // Permissions
    Route::apiResource('permissions', 'PermissionsApiController');

    // Roles
    Route::apiResource('roles', 'RolesApiController');

    // Users
    Route::apiResource('users', 'UsersApiController');

    // News
    Route::post('news/media', 'NewsApiController@storeMedia')->name('news.storeMedia');
    Route::apiResource('news', 'NewsApiController', ['except' => ['show']]);

    // Tizers
    Route::post('tizers/media', 'TizersApiController@storeMedia')->name('tizers.storeMedia');
    Route::apiResource('tizers', 'TizersApiController');
});
Route::apiResource('cats', 'CatController'); 
