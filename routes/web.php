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

// Serve uploaded files directly from storage/app/public via PHP, instead of
// relying on the public/storage symlink. On Windows, `git clone` without
// Developer Mode checks out a symlink as a plain text file (not a real
// symlink), and depending on the web server's rewrite config that can also
// stop the request from ever reaching this router. New uploads use
// /uploads/{path} - a URL with no matching path in public/ at all - so
// there's nothing on disk for the web server to (mis)serve as a static
// file before falling through to Lumen. /storage/{path} is kept for any
// already-issued URLs using the old scheme.
$serveUpload = function ($path) {
    $base = storage_path('app/public');
    $filePath = realpath($base . '/' . $path);

    if ($filePath === false || strpos($filePath, realpath($base)) !== 0 || !is_file($filePath)) {
        abort(404);
    }

    // response()->file() returns a raw Symfony BinaryFileResponse, which has
    // no header() method - CorsMiddleware calls $response->header(...) on
    // every response and would fatal on it. response()->make() returns a
    // Laravel response that supports it.
    return response()->make(file_get_contents($filePath), 200, [
        'Content-Type' => mime_content_type($filePath) ?: 'application/octet-stream',
    ]);
};

$router->get('/uploads/{path:.*}', $serveUpload);
$router->get('/storage/{path:.*}', $serveUpload);

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
    $router->post('/auth/reset-password', 'AuthController@resetPassword');

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

    $router->group(['prefix' => 'admin', 'middleware' => ['auth', 'admin']], function () use ($router) {

        // Dashboard
        $router->get('/dashboard/stats', 'OrderController@statistics');
        $router->get('/dashboard/customers', 'UserController@statistics');
        $router->get('/dashboard/recent-orders', 'OrderController@recentOrders');
        $router->get('/dashboard/popular-products', 'ProductController@popularProducts');

        // Categories Management
        $router->post('/categories', 'CategoryController@store');
        $router->put('/categories/{id}', 'CategoryController@update');
        $router->delete('/categories/{id}', 'CategoryController@destroy');

        // Products Management
        $router->post('/products', 'ProductController@store');
        $router->put('/products/{id}', 'ProductController@update');
        $router->delete('/products/{id}', 'ProductController@destroy');

        // Product Design Templates Management
        $router->post('/products/{id}/design-templates', 'ProductDesignTemplateController@store');
        $router->delete('/products/{id}/design-templates/{templateId}', 'ProductDesignTemplateController@destroy');

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

        // Image Upload Management
        $router->post('/images/upload', 'ImageUploadController@uploadImage');
        $router->post('/products/{id}/images', 'ImageUploadController@uploadProductImage');
        $router->delete('/products/{id}/images', 'ImageUploadController@deleteProductImage');
        $router->post('/categories/{id}/image', 'ImageUploadController@uploadCategoryImage');
        $router->delete('/categories/{id}/image', 'ImageUploadController@deleteCategoryImage');

    });

});
