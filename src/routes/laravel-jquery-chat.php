<?php

/**
 * Helpers.
 */
// Route helper.
$route = function ($accessor, $default = '') {
    return $this->app->config->get('ljc.routes.'.$accessor, $default);
};
// Middleware helper.
$middleware = function ($accessor, $default = []) {
    return $this->app->config->get('ljc.middleware.'.$accessor, $default);
};
// Authentication middleware helper.
$authMiddleware = function ($accessor) use ($middleware) {
    return array_unique(
        array_merge((array) $middleware($accessor), ['auth'])
    );
};

/*
 * ljc routes.
 */
Route::group([
    'as'         => 'ljc.',
    'prefix'     => $route('home'),
    'middleware' => $middleware('global', 'web'),
    'namespace'  => 'Dieegogd\LaravelJqueryChat\Http\Controllers',
], function () use ($route, $middleware, $authMiddleware) {
    Route::post(
        'laravel-jquery-chat',
        [
            'as' => 'laravel-jquery-chat.index',
            'uses' => 'LaravelJqueryChatController@index'
        ]
    );
});
