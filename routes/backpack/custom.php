<?php

// --------------------------
// Custom Backpack Routes
// --------------------------
// This route file is loaded automatically by Backpack\Base.
// Routes you generate using Backpack\Generators will be placed here.

Route::group([
    'prefix'     => config('backpack.base.route_prefix', 'admin'),
    'middleware' => array_merge(
        (array) config('backpack.base.web_middleware', 'web'),
        (array) config('backpack.base.middleware_key', 'admin')
    ),
    'namespace'  => 'App\Http\Controllers\Admin',
], function () { // custom admin routes
    Route::crud('appuser', 'AppuserCrudController');
    Route::crud('expenses', 'ExpensesCrudController');
    Route::crud('bill', 'BillCrudController');
    Route::crud('category', 'CategoryCrudController');
    Route::crud('limit', 'LimitCrudController');
    Route::crud('income', 'IncomeCrudController');
    Route::crud('tag', 'TagCrudController');
    Route::crud('source', 'SourceCrudController');
}); // this should be the absolute last line of this file