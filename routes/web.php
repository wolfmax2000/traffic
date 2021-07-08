<?php

//Route::get('/', 'NewsController@index');

Route::get('/', 'WeatherController@index');
Route::get('/gis', 'WeatherController@gis');


Route::get('/news/{id}', 'NewsController@show');
Route::get('/news_short/{id}', 'NewsController@show_short');

Route::get('/news_short_50/{id}', 'NewsController@show_short_50');
Route::get('/news_50/{id}', 'NewsController@show_50');

Route::get('/news_click/{id}', 'NewsController@click');
Route::get('/news_click_full/{id}', 'NewsController@click_full');

//Route::get('/tizers', 'TizerController@index');
//Route::get('/news50', 'NewsController@news');
Route::get('/tizers/{id}', 'TizerController@show');
//Route::get('/tizers/test/{id}/{click_by}', 'TizerController@click_test');
//Route::get('/stat/heare/{id}', 'StatController@heare');

//Route::get('/users_guide', 'NewsController@users_guide');
//Route::get('/users_coockie', 'NewsController@users_coockie');
//Route::get('/day_news', 'NewsController@random');
//Route::get('/social_news', 'NewsController@random');
//Route::get('/actions', 'NewsController@random');

//Route::get('/testpeople', 'NewsController@testpeople');
//Route::get('/testvue', 'NewsController@testvue');
//Route::get('/test/stat', 'TestController@stat');

//Route::get('/landing', 'LandingController@index')->name('landing');
Route::get('/showimage/{img}', 'NewsController@showimage')->name('showimage');

// WEB Push
//Route::post('/subscriber', 'SubController@subscriber')->middleware("cors");
Auth::routes(['register' => false]);

// Admin
Route::group(['prefix' => 'admin', 'as' => 'admin.', 'namespace' => 'Admin', 'middleware' => ['auth']], function () {
    Route::get('/', 'HomeController@index')->name('home');

    // Permissions
    Route::delete('permissions/destroy', 'PermissionsController@massDestroy')->name('permissions.massDestroy');
    Route::resource('permissions', 'PermissionsController');

    // Roles
    Route::delete('roles/destroy', 'RolesController@massDestroy')->name('roles.massDestroy');
    Route::resource('roles', 'RolesController');

    // Users
    Route::delete('users/destroy', 'UsersController@massDestroy')->name('users.massDestroy');
    Route::resource('users', 'UsersController');

    // Scripts
    //Route::delete('scripts/destroy', 'ScriptsController@massDestroy')->name('scripts.massDestroy');
    //Route::resource('scripts', 'ScriptsController');

    // Categories
    Route::delete('categories/destroy', 'CategoriesController@massDestroy')->name('categories.massDestroy');
    Route::resource('categories', 'CategoriesController');

    // Templates
    Route::delete('templates/destroy', 'TemplatesController@massDestroy')->name('templates.massDestroy');
    Route::resource('templates', 'TemplatesController');

    // Domains
    Route::delete('domains/destroy', 'DomainsController@massDestroy')->name('domains.massDestroy');
    Route::post('domains/media', 'DomainsController@storeMedia')->name('domains.storeMedia');
    Route::resource('domains', 'DomainsController');

    // Sources
    Route::delete('sources/destroy', 'SourcesController@massDestroy')->name('sources.massDestroy');
    Route::resource('sources', 'SourcesController');

    // News
    Route::delete('news/destroy', 'NewsController@massDestroy')->name('news.massDestroy');
    Route::get('news/null/{id}', 'NewsController@null')->name('news.null');
    Route::post('news/media', 'NewsController@storeMedia')->name('news.storeMedia');
    Route::post('news/ckmedia', 'NewsController@storeCKEditorImages')->name('news.storeCKEditorImages');
    Route::resource('news', 'NewsController', ['except' => ['show']]);

    // Tizers
    Route::delete('tizers/destroy', 'TizersController@massDestroy')->name('tizers.massDestroy');
    Route::get('tizers/null/{id}', 'TizersController@null')->name('tizers.null');
    Route::get('tizers/active', 'TizersController@active')->name('tizers.active');
    Route::post('tizers/media', 'TizersController@storeMedia')->name('tizers.storeMedia');
    Route::post('tizers/ckmedia', 'TizersController@storeCKEditorImages')->name('tizers.storeCKEditorImages');
    Route::resource('tizers', 'TizersController');

    // tizers-test
    Route::resource('tizers-test', 'TizersTestController');
    Route::get('tizerstest/active', 'TizersTestController@active')->name('tizerstest.active');
    Route::get('tizerstest/applyb', 'TizersTestController@applyb')->name('tizerstest.applyb');

    // TizersResult
    Route::resource('tizers-result', 'TizersResultController');

    // NewsResult
    Route::resource('news-result', 'NewsResultController');

    // TizersStatResult
    Route::resource('tizers-stat', 'TizersStatController');

    // Stats
    Route::resource('stats', 'StatsController');

    
    /// PUSH ROUTERS ///
    // Push templates
    Route::resource('push-templates', 'PushTemplatesController', ['except' => ['show']]);
    Route::delete('push-templates/destroy', 'PushTemplatesController@massDestroy')->name('push-templates.massDestroy');

    // Push clients
    Route::resource('push-clients', 'PushClientsController', ['except' => ['show']]);

    // Push  
    Route::resource('pushes', 'PushController', ['except' => ['show', 'index', 'create']]);
    Route::get('pushes/{push_template}', ['as' => 'pushes.index', 'uses' => 'PushController@index']);
    Route::get('pushes/create/{template}', ['as' => 'pushes.create', 'uses' => 'PushController@create']);
    Route::delete('pushes/destroy', 'PushController@massDestroy')->name('pushes.massDestroy');
    Route::post('pushes/media', 'PushController@storeMedia')->name('pushes.storeMedia');
    Route::get('pushes/status/{push}/{status}', 'PushController@status')->name('pushes.status');
    
});
