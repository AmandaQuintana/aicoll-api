<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CompanyRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function validationData()
    {
        $data = $this->all();

        if (in_array($this->method(), ['GET', 'DELETE', 'PUT', 'PATCH'])) {
            $data['tax_id'] = $this->route('tax_id');
        }

        return $data;
    }


    public function rules()
    {
        if ($this->isMethod('get')) {
            return [
                'tax_id' => 'required|exists:companies,tax_id',
            ];
        }

        if ($this->isMethod('post')) {
            return [
                'name'     => 'required|string|max:255',
                'address'  => 'required|string|max:255',
                'phone'    => 'required|string|max:20',
                'active'   => ['boolean', function ($attribute, $value, $fail) {
                    if ($value === false) {
                        $fail('Companies must be created as active.');
                    }
                }],
                'tax_id'   => 'required|string|max:20|unique:companies,tax_id',
            ];
        }

        if ($this->isMethod('put') || $this->isMethod('patch')) {
            return [
                'name'     => 'sometimes|string|max:255',
                'address'  => 'sometimes|string|max:255',
                'phone'    => 'sometimes|string|max:20',
                'active'   => 'sometimes|boolean',
                'tax_id'   => 'required|exists:companies,tax_id|in:' . $this->route('tax_id'),
            ];
        }

        if ($this->isMethod('delete')) {
            return [
                'tax_id' => [
                    'required',
                    'exists:companies,tax_id',
                    function ($attribute, $value, $fail) {
                        $company = \App\Models\Company::where('tax_id', $value)->first();

                        if ($company && $company->active) {
                            $fail('Cannot delete an active company.');
                        }
                    }
                ],
            ];
        }

        return [];
    }

    public function messages()
    {
        return [
            'tax_id.exists'  => 'The provided tax id does not match any registered company.',
            'tax_id.unique'  => 'The tax id has already been taken.',
            'tax_id.in'      => 'The tax ID cannot be changed.',
        ];
    }
}
