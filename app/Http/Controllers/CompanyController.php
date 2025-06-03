<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    // Display a list of all registered companies.
    public function index()
    {
        return Company::all();
    }

    // Store a newly created company in the database.
    public function store(CompanyRequest $request)
    {
        $company = Company::create($request->validated());
        return response()->json($company, 201);
    }

    // Display a specific company by its tax ID.
    public function show($tax_id)
    {
        $company = Company::where('tax_id', $tax_id)->first();

        if (!$company) {
            return response()->json(['message' => 'Company not found'], 404);
        }

        return $company;
    }

    // Update an existing company’s details.
    public function update(CompanyRequest $request, $tax_id)
    {
        $company = Company::where('tax_id', $tax_id)->first();

        if (!$company) {
            return response()->json(['message' => 'Company not found'], 404);
        }

        $company->update($request->validated());
        return response()->json($company);
    }

    // Delete a company only if it is inactive.
    public function destroy($tax_id)
    {
        $company = Company::where('tax_id', $tax_id)->first();

        if (!$company) {
            return response()->json(['message' => 'Company not found'], 404);
        }

        if ($company->active) {
            return response()->json(['message' => 'Cannot delete an active company'], 400);
        }

        $company->delete();
        return response()->json(['message' => 'Company deleted']);
    }
}
