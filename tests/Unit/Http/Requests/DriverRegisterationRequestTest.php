<?php

namespace Tests\Unit\Http\Requests;

use App\Http\Requests\DriverRegisterationRequest;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Validator;
use Tests\TestCase;

class DriverRegisterationRequestTest extends TestCase
{
    use RefreshDatabase;

    public function testRules()
    {
        $request = new DriverRegisterationRequest();

        $rules = $request->rules();

        $this->assertEquals([
            'data.attributes.firstName' => ['required', 'string', 'max:255'],
            'data.attributes.lastName' => ['required', 'string', 'max:255'],
            'data.attributes.dateOfBirth' => ['nullable', 'date'],
            'data.attributes.email' => ['nullable', 'string', 'email', 'max:255', 'unique:users,email'],
        ], $rules);
    }

    public function testValidationPasses()
    {
        $data = [
            'data' => [
                'attributes' => [
                    'firstName' => 'John',
                    'lastName' => 'Doe',
                    'dateOfBirth' => '1990-01-01',
                    'email' => 'john.doe@example.com',
                ],
            ],
        ];

        $request = new DriverRegisterationRequest();
        $validator = Validator::make($data, $request->rules());

        $this->assertTrue($validator->passes());
    }

    public function testValidationFails()
    {
        $data = [
            'data' => [
                'attributes' => [
                    'firstName' => '',
                    'lastName' => '',
                    'dateOfBirth' => 'invalid-date',
                    'email' => 'invalid-email',
                ],
            ],
        ];

        $request = new DriverRegisterationRequest();
        $validator = Validator::make($data, $request->rules());

        $this->assertFalse($validator->passes());
        $this->assertArrayHasKey('data.attributes.firstName', $validator->errors()->toArray());
        $this->assertArrayHasKey('data.attributes.lastName', $validator->errors()->toArray());
        $this->assertArrayHasKey('data.attributes.dateOfBirth', $validator->errors()->toArray());
        $this->assertArrayHasKey('data.attributes.email', $validator->errors()->toArray());
    }
}
