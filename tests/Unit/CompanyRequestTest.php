<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\CompanyRequest;

class CompanyRequestTest extends TestCase
{
    use RefreshDatabase;

    public function testFailsIfCompanyIsInactive(): void
    {
        $data = [
            'name' => 'Company B',
            'tax_id' => '0987654321',
            'address' => 'Street B',
            'phone' => '3007654321',
            'active' => false
        ];

        $request = new CompanyRequest();
        $request->setMethod('POST');

        $validator = Validator::make($data, $request->rules());

        $this->assertFalse($validator->passes());
        $this->assertEquals(
            ['Companies must be created as active.'],
            $validator->errors()->get('active')
        );
    }

    public function testCannotDeleteActiveCompany(): void
    {
        // Create an active company
        $company = \App\Models\Company::factory()->create([
            'tax_id' => '1111111111',
            'active' => true
        ]);

        // Simulate a DELETE
        $request = new \App\Http\Requests\CompanyRequest();
        $request->setMethod('DELETE');

        // Data to be passed to validator
        $data = ['tax_id' => $company->tax_id];

        $validator = \Illuminate\Support\Facades\Validator::make($data, $request->rules(), $request->messages());

        // Assert
        $this->assertFalse($validator->passes());
        $this->assertEquals(
            ['Cannot delete an active company.'],
            $validator->errors()->get('tax_id')
        );
    }

    public function testInvalidTaxIdMessage(): void
    {
        // Simulates a GET where tax_id does not exist
        $request = new \App\Http\Requests\CompanyRequest();
        $request->setMethod('GET');

        $data = ['tax_id' => 'nonexistent'];

        $validator = \Illuminate\Support\Facades\Validator::make($data, $request->rules(), $request->messages());

        $this->assertFalse($validator->passes());

        $this->assertEquals(
            ['The provided tax id does not match any registered company.'],
            $validator->errors()->get('tax_id')
        );
    }

    public function testTaxIdUniqueMessage(): void
    {
        // Creates a company with a duplicate tax_id
        $existing = \App\Models\Company::factory()->create([
            'tax_id' => '9999999999'
        ]);

        $request = new \App\Http\Requests\CompanyRequest();
        $request->setMethod('POST');

        $data = [
            'name' => 'Another Company',
            'tax_id' => '9999999999',
            'address' => 'Anywhere',
            'phone' => '3001234567',
            'active' => true
        ];

        $validator = \Illuminate\Support\Facades\Validator::make($data, $request->rules(), $request->messages());

        $this->assertFalse($validator->passes());

        $this->assertEquals(
            ['The tax id has already been taken.'],
            $validator->errors()->get('tax_id')
        );
    }

}
