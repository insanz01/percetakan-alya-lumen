<?php

use Carbon\Carbon;

/** @var \Laravel\Lumen\Routing\Router $router */

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
*/

$router->get('/', function () use ($router) {
    return response()->json([
        'name' => 'PrintMaster API',
        'version' => '1.0.0',
        'status' => 'running',
        'lumen' => $router->app->version(),
    ]);
});

// Health check
$router->get('/health', function () {
    return response()->json([
        'status' => 'ok',
        'timestamp' => Carbon::now()->toIso8601String(),
    ]);
});

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

$router->group(['prefix' => 'api/v1'], function () use ($router) {

    // ==========================================
    // PUBLIC ROUTES (No Auth Required)
    // ==========================================

    // Auth
    $router->post('/auth/register', 'AuthController@register');
    $router->post('/auth/login', 'AuthController@login');
    $router->post('/auth/admin/login', 'AuthController@adminLogin');

    // Categories (Public)
    $router->get('/categories', 'CategoryController@index');
    $router->get('/categories/{id}', 'CategoryController@show');
    $router->get('/categories/slug/{slug}', 'CategoryController@showBySlug');

    // Products (Public)
    $router->get('/products', 'ProductController@index');
    $router->get('/products/search', 'ProductController@search');
    $router->get('/products/{id}', 'ProductController@show');
    $router->get('/products/slug/{slug}', 'ProductController@showBySlug');
    $router->get('/products/category/{categorySlug}', 'ProductController@byCategory');

    // Promos (Public - validate only)
    $router->post('/promos/validate', 'PromoController@validateCode');

    // Settings (Public)
    $router->get('/settings/public', 'SettingController@publicSettings');

    // Contact Form (Public)
    $router->post('/contact', 'ContactController@submit');

    // Newsletter (Public)
    $router->post('/newsletter/subscribe', 'NewsletterController@subscribe');
    $router->get('/newsletter/unsubscribe/{token}', 'NewsletterController@unsubscribe');

    // Shipping (Public)
    $router->get('/shipping/methods', 'ShippingController@getMethods');
    $router->post('/shipping/calculate', 'ShippingController@calculate');
    $router->get('/shipping/provinces', 'ShippingController@getProvinces');

    // ==========================================
    // AUTHENTICATED ROUTES
    // ==========================================

    $router->group(['middleware' => 'auth'], function () use ($router) {

        // Auth
        $router->get('/auth/me', 'AuthController@me');
        $router->put('/auth/profile', 'AuthController@updateProfile');
        $router->put('/auth/password', 'AuthController@changePassword');
        $router->post('/auth/logout', 'AuthController@logout');

        // User Orders
        $router->get('/my-orders', 'OrderController@userOrders');
        $router->post('/orders', 'OrderController@store');
        $router->get('/orders/{id}', 'OrderController@show');
        $router->get('/orders/number/{orderNumber}', 'OrderController@showByOrderNumber');

        // Shipping Addresses
        $router->get('/addresses', 'ShippingAddressController@index');
        $router->post('/addresses', 'ShippingAddressController@store');
        $router->put('/addresses/{id}', 'ShippingAddressController@update');
        $router->delete('/addresses/{id}', 'ShippingAddressController@destroy');

        // File Upload
        $router->post('/files/upload', 'FileUploadController@upload');
        $router->get('/files/{id}', 'FileUploadController@show');
        $router->get('/files/{id}/download', 'FileUploadController@download');
        $router->delete('/files/{id}', 'FileUploadController@destroy');

    });

    // ==========================================
    // ADMIN ROUTES
    // ==========================================

    $router->group(['prefix' => 'admin', 'middleware' => 'auth'], function () use ($router) {

        // Dashboard
        $router->get('/dashboard/stats', 'OrderController@statistics');
        $router->get('/dashboard/customers', 'UserController@statistics');

        // Categories Management
        $router->post('/categories', 'CategoryController@store');
        $router->put('/categories/{id}', 'CategoryController@update');
        $router->delete('/categories/{id}', 'CategoryController@destroy');

        // Products Management
        $router->post('/products', 'ProductController@store');
        $router->put('/products/{id}', 'ProductController@update');
        $router->delete('/products/{id}', 'ProductController@destroy');

        // Orders Management
        $router->get('/orders', 'OrderController@index');
        $router->put('/orders/{id}/status', 'OrderController@updateStatus');
        $router->put('/orders/{id}/payment-status', 'OrderController@updatePaymentStatus');

        // Customers Management
        $router->get('/customers', 'UserController@index');
        $router->get('/customers/{id}', 'UserController@show');
        $router->put('/customers/{id}', 'UserController@update');
        $router->delete('/customers/{id}', 'UserController@destroy');

        // Promos Management
        $router->get('/promos', 'PromoController@index');
        $router->get('/promos/{id}', 'PromoController@show');
        $router->post('/promos', 'PromoController@store');
        $router->put('/promos/{id}', 'PromoController@update');
        $router->delete('/promos/{id}', 'PromoController@destroy');
        $router->post('/promos/{id}/increment-usage', 'PromoController@incrementUsage');

        // Settings Management
        $router->get('/settings', 'SettingController@index');
        $router->get('/settings/group/{group}', 'SettingController@byGroup');
        $router->get('/settings/{key}', 'SettingController@show');
        $router->put('/settings', 'SettingController@update');
        $router->put('/settings/{key}', 'SettingController@updateSingle');

        // Contact Messages Management
        $router->get('/contact-messages', 'ContactController@index');
        $router->get('/contact-messages/stats', 'ContactController@statistics');
        $router->get('/contact-messages/{id}', 'ContactController@show');
        $router->put('/contact-messages/{id}/status', 'ContactController@updateStatus');
        $router->delete('/contact-messages/{id}', 'ContactController@destroy');

        // Newsletter Management
        $router->get('/newsletter', 'NewsletterController@index');
        $router->get('/newsletter/stats', 'NewsletterController@statistics');
        $router->get('/newsletter/export', 'NewsletterController@export');
        $router->delete('/newsletter/{id}', 'NewsletterController@destroy');

        // Files Management
        $router->get('/files', 'FileUploadController@forRelated');

    });

});
