<?php

namespace Tests\Unit\Http\Requests;

use App\Http\Requests\CustomerRegisterationRequest;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use Tests\TestCase;

class CustomerRegisterationRequestTest extends TestCase
{
    use RefreshDatabase;

    public function testMappedAttributes()
    {
        $request = new CustomerRegisterationRequest();

        $request->merge([
            'data' => [
                'attributes' => [
                    'firstName' => 'John',
                    'lastName' => 'Doe',
                    'companyName' => 'Acme Corp',
                    'dateOfBirth' => '1990-01-01',
                    'email' => 'email@example.com',
                ],
            ],
        ]);
        $mappedAttributes = $request->mappedAttributes();

        $this->assertInstanceOf(Collection::class, $mappedAttributes);
        $this->assertArrayHasKey('first_name', $mappedAttributes->toArray());
        $this->assertArrayHasKey('last_name', $mappedAttributes->toArray());
        $this->assertArrayHasKey('company_name', $mappedAttributes->toArray());
        $this->assertArrayHasKey('date_of_birth', $mappedAttributes->toArray());
        $this->assertArrayHasKey('email', $mappedAttributes->toArray());
    }

    public function testRules()
    {
        $request = new CustomerRegisterationRequest();

        $rules = $request->rules();

        $this->assertIsArray($rules);
        $this->assertArrayHasKey('data.attributes.firstName', $rules);
        $this->assertArrayHasKey('data.attributes.lastName', $rules);
        $this->assertArrayHasKey('data.attributes.companyName', $rules);
        $this->assertArrayHasKey('data.attributes.dateOfBirth', $rules);
        $this->assertArrayHasKey('data.attributes.email', $rules);

        $validator = Validator::make([
            'data' => [
                'attributes' => [
                    'firstName' => 'John',
                    'lastName' => 'Doe',
                    'companyName' => 'Acme Corp',
                    'dateOfBirth' => '1990-01-01',
                    'email' => 'john.doe@example.com',
                ],
            ],
        ], $rules);

        $this->assertTrue($validator->passes());
    }
}
