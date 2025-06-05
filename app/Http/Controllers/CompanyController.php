<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Http\Requests\CompanyRequest;
use Illuminate\Http\Response;

class CompanyController extends Controller
{
    /**
     * List all companies
     */
    public function index()
    {
        $companies = Company::all();

        return response()->json([
            'message' => 'List of all companies',
            'data' => $companies
        ]);
    }

    /**
     * Store a new company
     */
    public function store(CompanyRequest $request)
    {
        $validated = $request->validated();
        $company = Company::create($validated);

        return response()->json([
            'message' => 'Company created successfully',
            'data' => $company
        ], 201);
    }

    /**
     * Show a company by tax_id
     */
    public function show(CompanyRequest $request)
    {
        $tax_id = $request->validated()['tax_id'];
        $company = Company::where('tax_id', $tax_id)->first();

        return response()->json([
            'message' => 'Company details retrieved successfully',
            'data' => $company
        ]);
    }

    /**
     * Update a company by tax_id
     */
    public function update(CompanyRequest $request)
    {
        $validated = $request->validated();
        $company = Company::where('tax_id', $validated['tax_id'])->first();

        $company->update($validated);

        return response()->json([
            'message' => 'Company updated successfully',
            'data' => $company
        ]);
    }

    /**
     * Delete a company by tax_id
     */
    public function destroy(CompanyRequest $request)
    {
        $tax_id = $request->route('tax_id');
        $company = Company::where('tax_id', $tax_id)->first();

        $company->delete();

        return response()->json(['message' => 'Company deleted successfully.']);
    }
}
