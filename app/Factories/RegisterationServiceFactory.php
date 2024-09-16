<?php

namespace App\Factories;

use App\Services\CustomerRegisterationService;
use App\Services\DriverRegisterationService;

class RegisterationServiceFactory
{
    public function __construct(
        protected CustomerRegisterationService $customerRegisterationService,
        protected DriverRegisterationService $driverRegisterationService
    ) {
        //
    }

    public function make(string $type)
    {
        if ($type === 'CUSTOMER') {
            return $this->customerRegisterationService;
        } else if ($type === 'DRIVER') {
            return $this->driverRegisterationService;
        } else {
            throw new \InvalidArgumentException('Unregistered user type ' . $type);
        }
    }
}
