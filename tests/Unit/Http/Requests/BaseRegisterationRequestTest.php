<?php

namespace Tests\Unit\Http\Requests;

use App\Http\Requests\BaseRegisterationRequest;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class BaseRegisterationRequestTest extends TestCase
{
    use RefreshDatabase;

    public function testMappedAttributes()
    {
        $request = new BaseRegisterationRequest();

        $request->merge([
            'data' => [
                'attributes' => [
                    'firstName' => 'John',
                    'lastName' => 'Doe',
                    'dateOfBirth' => '1990-01-01',
                    'email' => 'john.doe@example.com',
                ],
            ],
        ]);

        $mappedAttributes = $request->mappedAttributes();

        $this->assertEquals('John', $mappedAttributes->get('first_name'));
        $this->assertEquals('Doe', $mappedAttributes->get('last_name'));
        $this->assertEquals('1990-01-01', $mappedAttributes->get('date_of_birth'));
        $this->assertEquals('john.doe@example.com', $mappedAttributes->get('email'));
    }

    public function testRules()
    {
        $request = new BaseRegisterationRequest();

        $validator = Validator::make([
            'data' => [
                'attributes' => [
                    'type' => 'CUSTOMER',
                    'phone' => '+962798341234',
                ],
            ],
            'authorization' => 'Bearer some-token',
        ], $request->rules());

        $this->assertTrue($validator->passes());
    }

    public function testRulesFail()
    {
        $request = new BaseRegisterationRequest();

        $validator = Validator::make([
            'data' => [
                'attributes' => [
                    'type' => 'INVALID_TYPE',
                    'phone' => 'invalid-phone',
                ],
            ],
            'authorization' => 'Invalid token',
        ], $request->rules());

        $this->assertFalse($validator->passes());
    }
}
