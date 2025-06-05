<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;
use App\Models\Company;

class CompanyControllerTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_creates_a_company_with_valid_data(): void
    {
        $response = $this->postJson('/api/companies', [
            'name' => 'Test Company',
            'tax_id' => '1234567890',
            'address' => '123 Main Street',
            'phone' => '3001234567'
        ]);

        $response->assertStatus(201)
        ->assertJsonStructure([
            'message',
            'data' => ['id', 'name', 'tax_id', 'address', 'phone', 'created_at', 'updated_at']
        ]);

        $this->assertDatabaseHas('companies', ['tax_id' => '1234567890']);
    }

    #[Test]
    public function it_fails_to_create_company_with_invalid_data(): void
    {
        $response = $this->postJson('/api/companies', []);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['name', 'tax_id']);
    }

    #[Test]
    public function it_lists_all_companies(): void
    {
        Company::factory()->create(['name' => 'Company A']);
        Company::factory()->create(['name' => 'Company B']);

        $response = $this->getJson('/api/companies');

        $response->assertJsonStructure([
            'message',
            'data' => [
                ['id', 'name', 'tax_id', 'address', 'phone', 'created_at', 'updated_at']
            ]
        ]);
    }

    #[Test]
    public function it_updates_an_existing_company(): void
    {
        // Create original company
        $company = Company::factory()->create([
            'name' => 'Original Company',
            'tax_id' => '1234567890',
            'address' => 'Old Address',
            'phone' => '3000000000'
        ]);

        // Update it
        $response = $this->putJson("/api/companies/{$company->tax_id}", [
            'name' => 'Updated Company',
            'tax_id' => '1234567890',
            'address' => 'New Address',
            'phone' => '3111234567'
        ]);

        // Assert
        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Company updated successfully',
                'data' => [
                    'id' => $company->id,
                    'name' => 'Updated Company',
                    'address' => 'New Address',
                    'phone' => '3111234567',
                    'tax_id' => '1234567890'
                ]
            ]);

        $this->assertDatabaseHas('companies', [
            'id' => $company->id,
            'name' => 'Updated Company',
            'address' => 'New Address'
        ]);
    }

    #[Test]
    public function it_shows_a_company_by_tax_id(): void
    {
        // Create original company
        $company = Company::factory()->create([
            'name' => 'Visible Company',
            'tax_id' => '9876543210',
            'address' => 'Visible Street',
            'phone' => '3111234567'
        ]);

        // Query by tax_id
        $response = $this->getJson("/api/companies/{$company->tax_id}");

        // Assert
        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Company details retrieved successfully',
                'data' => [
                    'id' => $company->id,
                    'name' => 'Visible Company',
                    'tax_id' => '9876543210',
                    'address' => 'Visible Street',
                    'phone' => '3111234567'
                ]
            ]);
    }

    #[Test]
    public function it_returns_422_when_company_tax_id_does_not_exist(): void
    {
        $response = $this->getJson("/api/companies/0000000000");

        $response->assertStatus(422)
                ->assertJsonValidationErrors(['tax_id'])
                ->assertJsonFragment([
                    'tax_id' => ['The provided tax id does not match any registered company.']
                ]);
    }

}
