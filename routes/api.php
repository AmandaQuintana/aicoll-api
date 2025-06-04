<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CompanyController;

// Group all company-related routes under the /companies prefix
Route::prefix('companies')->group(function () {
    // Retrieve a list of all companies
    Route::get('/', [CompanyController::class, 'index']);

    // Create a new company
    Route::post('/', [CompanyController::class, 'store']);

    // Retrieve a specific company by tax_id
    Route::get('{tax_id}', [CompanyController::class, 'show']);

    // Update a specific company by tax_id
    Route::put('{tax_id}', [CompanyController::class, 'update']);

    // Delete a specific company by tax_id
    Route::delete('{tax_id}', [CompanyController::class, 'destroy']);
});
