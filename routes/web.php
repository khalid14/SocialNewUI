<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use Illuminate\Support\Facades\Route;


\URL::forceScheme('https');
\URL::forceRootUrl(env('APP_URL'));

Route::middleware(['web', 'payment'])->group(function () {

    // ----- entry point for app
//    Route::get('/', [
//        'as'    => 'landing-page',
//        'uses'  => 'ShopifyController@access'
//    ])->middleware('odin');
    Route::get('/', 'ShopifyController@dev');

    // ----- auth callback
    Route::get('/auth-callback', [
        'as' => 'shopify-auth-callback',
        'uses' => 'ShopifyController@authCallback'
    ])->middleware('odin');

    // ----- payment confirmation
    Route::get('/charge-callback', [
        'as' => 'shopify-charge-callback',
        'uses' => 'ShopifyController@chargeCallback'
    ]);

    // ----- uninstall hook callback
    Route::post('/hooks/uninstall', [
        'as' => 'hooks.uninstall',
        'uses'  => 'IntegrityController@uninstall'
    ]);

    // ----- GDPR Customer data erasure callback
    Route::post('/hooks/erase-customers', [
        'as' => 'hooks.eraseCustomers',
        'uses'  => 'IntegrityController@eraseCustomers'
    ]);

    // ----- GDPR Shop Data erasure callback
    Route::post('/hooks/erase-shop', [
        'as' => 'hooks.eraseShop',
        'uses'  => 'IntegrityController@eraseShop'
    ]);

    // ----- billing status page
    Route::get('/accept-billing', [
        'as' => 'pages.requestBilling',
        'uses'  => 'ShopifyController@requestBilling'
    ]);

    // ----- billing status page
    Route::get('/restart-billing', [
        'as' => 'pages.restartBilling',
        'uses'  => 'ShopifyController@restartBilling'
    ]);

    // ----- affiliate intermediate page
    Route::get('/affiliate', [
        'as' => 'pages.affiliate',
        'uses'  => 'ShopifyController@affiliate'
    ]);

    // ----- free pass intermediate page
    Route::get('/free-pass', [
        'as' => 'pages.freePass',
        'uses'  => 'ShopifyController@freePass'
    ]);

    // ----- normal script hook route
    Route::get('/pull/lazy/js/{shop}', [
        'as' => 'lazy.js',
        'uses'  => 'PullController@lazy'
    ])->middleware('cors');

    // ----- hook routes for urls injected via theme injection
    Route::get('/pull/js/{shop}/{integrity?}', [
        'as' => 'pull.js',
        'uses'  => 'PullController@js'
    ])->middleware('cors');
    Route::get('/pull/css/{shop}', [
        'as' => 'pull.css',
        'uses'  => 'PullController@css'
    ])->middleware('cors');

    // ----- integrity routes
    Route::get('/check-integrity.json', 'IntegrityController@check');
    Route::get('/fix-integrity.json', 'IntegrityController@fix');
    Route::patch('/clean-uninstall.json', 'IntegrityController@cleanUninstall');

    // ------ settings route
    Route::get('/get-settings.json', 'SettingsController@get');
    Route::patch('/save-settings.json', 'SettingsController@save');
    Route::get('/social-media-config', 'SocialMediaController@get');
    Route::get('/fb-call-back', 'SocialMediaController@fbCallBack');
    Route::get('/fb-pages', 'SocialMediaController@fbpages');
    Route::get('/twit-call-back', 'SocialMediaController@twitCallBack');
    Route::get('/pin-call-back', 'SocialMediaController@pinCallBack');

});

// ----- billing status page
Route::get('/free-pass-expired', [
    'as' => 'pages.freePassExpired',
    'uses'  => 'ShopifyController@freePassExpired'
]);

Route::get('/kiki/{shop}', 'ShopifyController@kiki');
Route::view('/faqs', 'faqs');
Route::view('/theme-injection', 'theme-injection');
Route::view('/quarantine-list', 'quarantine-list');
Route::get('/contact', 'ShopifyController@contact');
Route::post('/contact', 'ShopifyController@contactSend');

// ----- REST routes for Sprints -> autopilots & manual sprints
Route::post('/filter_products', 'SprintController@getFilteredProducts');
Route::post('/filter_type', 'SprintController@getProductByFilterType');

Route::resource('sprint', 'SprintController');
